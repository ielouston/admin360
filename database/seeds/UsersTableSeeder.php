<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use admin360\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        User::create([
        	'name' => 'alan',
        	'password' => bcrypt('Tru3nala!'),
        	'nombres' => 'Alan Mauricio',
        	'apellidos' => 'Sosa Garcia',
        	'email' => 'alan.sosa.g@hotmail.com',
        	'type' => 1,
        	'device' => 'Generated',
        ]);
    }
}
