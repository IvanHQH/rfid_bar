<?php

class UsersTableSeeder extends Seeder {

    public function run()
    {
        DB::table('users') -> delete();

        User::create(array(
            'username' => 'admin',
            'password' => Hash::make('admin'),
            'email' => 'asantosdl@gmail.com',
            'user_type' => 1,
            'remember_token' => ''
        ));

        User::create(array(
            'username' => 'reportes',
            'password' => Hash::make('reportes'),
            'email' => 'asantosdl@gmail.com',
            'user_type' => 2,
            'remember_token' => ''
        ));
    }
}
