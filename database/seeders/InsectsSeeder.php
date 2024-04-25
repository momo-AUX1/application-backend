<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InsectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $insects = [
            [
                'nom_commun' => 'Fourmi',
                'nom_scientifique' => 'Formicidae',
                'taille' => '0.3 cm',
                'poids' => 0.000001,
              	'photo' => 'https://images.pexels.com/photos/1104972/pexels-photo-1104972.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260',
                'couleur' => 'noir',
                
            ],
            [
                'nom_commun' => 'Punaise de lit',
                'nom_scientifique' => 'Cimex lectularius',
                'taille' => '0.5 cm',
                'poids' => 0.00007,
              	'photo' => 'https://static.actu.fr/uploads/2022/04/punaise-de-lit.jpeg',
                'couleur' => 'rouge brun',
                
            ],
            [
                'nom_commun' => 'Scarabée',
                'nom_scientifique' => 'Scarabaeidae',
                'taille' => '1.5 cm',
                'poids' => 0.00007,
              	'photo' => 'https://th.bing.com/th/id/OIP.grt1MGg6fBOJysrVLbFYagHaE8?rs=1&pid=ImgDetMain',
                'couleur' => 'vert métallique',
                
            ],
            [
                'nom_commun' => 'Sauterelle',
                'nom_scientifique' => 'Caelifera',
                'taille' => '5 cm',
                'poids' => 0.000001,
              	'photo' => 'https://th.bing.com/th/id/R.653c3603df630ffd2879f8bc9ce1a9d7?rik=huJR2r%2fj%2bXRnpQ&riu=http%3a%2f%2flcornithologie.fr%2fwp-content%2fgallery%2fGrande-sauterelle-verte%2fDSC02637.JPG&ehk=hAlZPkYS9qzh98vUbcgbRuNflP5OQQXtEphUo0uc9tY%3d&risl=&pid=ImgRaw&r=0',
                'couleur' => 'vert',
                
            ],
            [
                'nom_commun' => 'Guêpe',
                'nom_scientifique' => 'Vespidae',
                'taille' => '1.5 cm',
                'poids' => 0.00010,
              	'photo' => 'https://www.aquaportail.com/pictures1802/guepe-commune-vespula-vulgaris-vol.jpg',
                'couleur' => 'jaune et noir',
                
            ],
        ];

        DB::table('insects')->delete();
        DB::table('insects')->insert([
            'nom_commun' => 'Papillon',
            'nom_scientifique' => 'Papilio machaon',
            'taille' => '10 cm',
            'poids' => 0.00005,
          	'photo' => 'https://th.bing.com/th/id/R.82ecd61944bece1f93969bed5b8f21f6?rik=IkHvfB1Bi%2fq0jQ&riu=http%3a%2f%2f1.bp.blogspot.com%2f-JOG6zzen7m4%2fU3IuTzMvPUI%2fAAAAAAAAFoU%2fAHCE4SGfg9w%2fs1600%2fButterfly%2bHd%2bPhotos%2b%26%2bWallpapers%2b(1).jpg&ehk=0nj0z%2f4bRTsjnBQh3HV8oUOlfM5qY%2fNJ4XGpn3xO70Y%3d&risl=&pid=ImgRaw&r=0',
            'couleur' => 'jaune',
            
            ]);
        DB::table('insects')->insert([
            'nom_commun' => 'Coccinelle',
            'nom_scientifique' => 'Coccinella septempunctata',
            'taille' => '0.5 cm',
            'poids' => 0.00007,
          	'photo' => 'https://jardinerfacile.fr/wp-content/uploads/2019/08/iStock-140470780.jpg',
            'couleur' => 'rouge',
            
        ]);
        DB::table('insects')->insert([
            'nom_commun' => 'Moustique',
            'nom_scientifique' => 'Culex pipiens',
            'taille' => '0.5 cm',
            'poids' => 0.0015,
          	'photo' => 'https://th.bing.com/th/id/OIP.h_BZxoGKOUAJ2V_TldGgRQHaE1?rs=1&pid=ImgDetMain',
            'couleur' => 'noir',
            
        ]);
        DB::table('insects')->insert([
            'nom_commun' => 'Abeille',
            'nom_scientifique' => 'Apis mellifera',
            'taille' => '1 cm',
            'poids' => 0.00007,
          	'photo' => 'https://th.bing.com/th/id/OIP.WmUrEHbJLHb9V6SH-mosbgHaFN?rs=1&pid=ImgDetMain',
            'couleur' => 'jaune',
            
        ]);
        DB::table('insects')->insert([
            'nom_commun' => 'Mouche',
            'nom_scientifique' => 'Musca domestica',
            'taille' => '0.5 cm',
            'poids' => 0.000004,
          	'photo' => 'https://th.bing.com/th/id/OIP.irXPuzQ-xePyPkO5cCJw9QHaE7?rs=1&pid=ImgDetMain',
            'couleur' => 'noir',
            
        ]);
        DB::table('insects')->insert($insects);
    }
}
