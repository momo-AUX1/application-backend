<?php

use App\Http\Controllers\UtilsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Insect;
use App\Models\Liens;
use App\Models\User;
use App\Http\Controllers\SocialAuthController;
use App\Http\Requests\ProduitRequest;
use App\Http\Controllers\AuthController;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// login/register:
// fait par Auth::routes()

// navigation:

// - get myinsects → return insects, total_insect where id_user = ?
Route::get('/myinsects/{id}', function ($id) {
    $insects = Insect::where("user", $id)->get();
    return $insects;
});

// all insects
Route::get('/insects', function () {
    $insects = Insect::all();
    return $insects;
});


// insects by id
Route::get('/insect/{id}', function ($id) {
    $insect = Insect::where("id", $id)->get();
    return $insect;
});

// Page InsectiDex:

// - get search/{search} → return insect where nom, type like $search
Route::get('/search', function (Request $request) {
    $search = $request->input('search');
    $insects = Insect::where("nom commun", "like", "%$search%")
        ->orWhere("nom scientifique", "like", "%$search%")
        ->orWhere("couleur", "like", "%$search%")
        ->get();
    return $insects;
});


// Page MyAccount:
// - delete confirm/{id} → User find($id) → delete()
Route::delete('/myaccount/delete/{id}', function ($id) {
    User::find($id)->delete();
});

// - put confirm/{id} → User find($id) , $user → mail = request → mail
Route::put('/confirm/mail/{id}', function (Request $request, $id) {
    $user = User::find($id);
    $user->email = $request->input('email');
    $user->save();
    return $user;
});

// - put confirm/{id} → User find($id) , $user → password = request → password
Route::put('/confirm/password/{id}', function (Request $request, $id) {
    $user = User::find($id);
    $user->password = $request->input('password');
    $user->save();
    return $user;
});


Route::put('confirm/username/{id}',function (Request $request,$id){
$user = User::find($id);
$user->username = $request->input('username');
$user->save();
return $user;
});


Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

//Route::get('/logout', [AuthController::class, 'logout']);

Route::get('/auth/redirect/{provider}', [SocialAuthController::class, 'redirect']);
Route::get('/auth/callback/{provider}', [SocialAuthController::class, 'callback']);


// - protected route group
//Route::group(['middleware' => ['auth:sanctum']], function () {
//    Route::get("/protected", function () {
//        return "Protected route";
//    });
//});


Route::middleware('auth:sanctum')->get('/insects/user', function () {
    $user = auth()->user();

    if ($user) {
        $liens = Liens::where('user', $user->id)->get();

        $insectIds = $liens->pluck('insect');

        $insects = Insect::whereIn('id', $insectIds)->get();

        return response()->json([
            'insects' => $insects
        ]);
    } else {
        return response()->json(['error' => 'Not authorized'], 403);
    }
});


Route::middleware('auth:sanctum')->get('/myaccount', function () {
    $user = auth()->user();

    if ($user) {
        return $user;
    } else {
        return response()->json(['error' => 'Not authorized'], 403);
    }
});

Route::middleware('auth:sanctum')->post('/insects/add', function (Request $request) {
    $user = Auth::user();
    if (!$user || !$user->is_admin) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    try {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'scientificName' => 'required|string',
            'photo' => 'required|image',
            'color' => 'required|string',
            'height' => 'required|string',
            'weight' => 'required|numeric'
        ]);

        $image = $request->file('photo');
        if (!$image) {
            throw new Exception('Image file not received');
        }

        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $imagePath = $image->storeAs('public/images', $imageName);

        $insect = new Insect([
            'nom_commun' => $validatedData['name'],
            'nom_scientifique' => $validatedData['scientificName'],
            'photo' => url('/storage/images/' . $imageName),
            'couleur' => $validatedData['color'],
            'taille' => $validatedData['height'],
            'poids' => $validatedData['weight'],
        ]);
        $insect->save();

        return response()->json($insect, 201);
    } catch (Exception $e) {
        Log::error('Failed to add insect: ' . $e->getMessage());
        return response()->json(['error' => 'Server Error: ' . $e->getMessage()], 500);
    }
});



Route::middleware('auth:sanctum')->delete('/insects/delete/{id}', function ($id) {
    $user = Auth::user();
    if (!$user || !$user->is_admin) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $insect = Insect::find($id);
    if (!$insect) {
        return response()->json(['error' => 'Insect not found'], 404);
    }

    try {
        DB::beginTransaction();

        Liens::where('insect', $id)->delete();

        $imageName = basename($insect->photo);
        Storage::delete('public/images/' . $imageName);
        $insect->delete();

        DB::commit();

        return response()->json(['message' => 'Insect and all related liens deleted successfully']);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => 'Failed to delete insect: ' . $e->getMessage()], 500);
    }
});



Route::middleware('auth:sanctum')->post('/myaccount/manage', function (Request $request) {
    $user = Auth::user();
    if (!$user) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $data = $request->only(['email', 'password', 'deleteAccount']);
    
    if ($request->has('email') && !is_null($user->email)) {
        $user->email = $data['email'];
    }

    if ($request->has('password') && !is_null($user->password)) {
        $user->password = Hash::make($data['password']);
    }

    if ($request->filled('deleteAccount') && $data['deleteAccount'] == 'true') {
        $user->delete();
        return response()->json(['message' => 'Account deleted successfully']);
    }

    $user->save();

    return response()->json(['message' => 'Account updated successfully']);
});


Route::middleware('auth:sanctum')->get('/logout', function (Request $request) {
    $user = auth()->user(); 

    if ($user) {
        $user->tokens()->delete(); 
        auth()->guard('web')->logout(); 

        return response()->json(['message' => 'Logged out successfully']);
    }

    return response()->json(['error' => 'Unauthenticated'], 401);
});


Route::middleware('auth:sanctum')->post('/imagedata', function (Request $request) {
    $user = Auth::user();
    if (!$user) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $base64_image = $request->input('imageData');
    if (!$base64_image) {
        return response()->json(['error' => 'No image data provided'], 400);
    }

    $insects = Insect::select('id', 'nom_commun', 'nom_scientifique', 'couleur')->get();
    $prompt = "Identify the insect in this image and provide its ID if available: ";

    foreach ($insects as $insect) {
        $prompt .= "{$insect->id}: {$insect->nom_commun} ({$insect->nom_scientifique}, Color: {$insect->couleur}); ";
    }

    $prompt = rtrim($prompt, "; ");

    $apiKey = "sk-proj-80GT1RbiDY7NRAfTOM0YT3BlbkFJERCMf0ZDuQVL9KAHco1F";
    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
    ];

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.openai.com/v1/chat/completions',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode([
            "model" => "gpt-4-turbo",
            "response_format" => ["type" => "json_object"],
            "messages" => [
                [
                    "role" => "system",
                    "content" => "You are a helpful assistant designed to output JSON you will only answer in JSON and NOTHING ELSE. you recognize the insect type in the db then you will write 'exists:true' else false and if true you will say 'ID:<id>' no more no less."
                ],
                [
                    "role" => "user",
                    "content" => [
                        [
                            "type" => "text",
                            "text" => $prompt
                        ],
                        [
                            "type" => "image_url",
                            "image_url" => [
                                "url" => "data:image/jpeg;base64," . $base64_image
                            ]
                        ]
                    ]
                ]
            ],
        ]),
        CURLOPT_HTTPHEADER => $headers,
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        return response()->json(['error' => 'CURL Error', 'details' => $err], 500);
    } else {
        $data = json_decode($response, true);
        if (isset($data['choices'][0]['message']['content'])) {
            $jsonContent = json_decode($data['choices'][0]['message']['content'], true);
            if ($jsonContent['exists'] && isset($jsonContent['ID'])) {
                $insectId = $jsonContent['ID'];
                $lien = new Liens([
                    'user' => $user->id,
                    'insect' => $insectId,
                ]);
                $lien->save();

                return response()->json(['message' => 'Insect linked successfully', 'lien' => $lien], 201);
            } else {
                return response()->json(['message' => 'No insect identified'], 404);
            }
        } else {
            return response()->json(['error' => 'Failed to process the response properly', 'data' => $data], 500);
        }
    }
});

