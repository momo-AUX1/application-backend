<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UtilsController extends Controller
{
    public function list(){
        $users = User::get();
        return $users;
    }
}
