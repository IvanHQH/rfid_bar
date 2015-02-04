<?php

class UsersTypesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('users_types') -> delete();

        UserType::create(array(
            'type' => 'admin',
            'description' => 'Usuario Administrador',
        ));

        UserType::create(array(
            'type' => 'viewer',
            'description' => 'Usuario solo lectura',
        ));
    }
}
