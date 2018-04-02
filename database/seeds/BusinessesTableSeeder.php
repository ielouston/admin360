<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use admin360\Business;

class BusinessesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Business::truncate();

        Business::create([
            'nombre' => 'Negocio Virtual',
            'calle' => 'Ciberespacio',
            'numero' => '666',
            'colonia' => '999',
            'cod_postal' => '69696',
            'rfc' => 'RAGF69696969',
            'ciudad' => 'Baggins Shrine',
            'estado' => 'Mordor',
            'tipo' => 'Matriz',
            'usuario_id' => 1,
            'telefonos' => '0'
        ]);

    }
}
