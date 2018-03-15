<?php

use Illuminate\Database\Seeder;
use Muebleria\Admin;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::truncate();

        Admin::create([
        	'name' => 'alan',
        	'password' => bcrypt('sosa'),
        	'email' => 'alan.sosa.g@hotmail.com',
        	'device' => 'Generado',
        	'type' => 1,
        	'situacion' => 'Activo'
        ]);

        Admin::create([
        	'name' => 'paco',
        	'password' => bcrypt('rangel'),
        	'email' => 'pako2746@gmail.com',
        	'device' => 'Generado',
        	'type' => 2,
        	'situacion' => 'Activo'
        ]);


    }
}
