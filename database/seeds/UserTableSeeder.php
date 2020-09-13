<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            \App\Models\User::ID_FIELD => 1,
            \App\Models\User::USERNAME_FIELD => 'test123',
            \App\Models\User::PASSWORD_FIELD => \App\Utils\Encryptor\Encryptor::hashPassword('123456'),
            \App\Models\User::STATUS_FIELD => \App\Models\User::VALID_STATUS,
            \App\Models\User::PAY_PASSWORD_FIELD => \App\Utils\Encryptor\Encryptor::hashPassword('123456'),
            \App\Models\User::GAME_PASSWORD_FIELD => \App\Utils\Encryptor\Encryptor::hashPassword('123456'),
        ]);

        \App\Models\UserBalance::create([
            \App\Models\UserBalance::USER_ID_FIELD => 1,
           \App\Models\UserBalance::BALANCE_FIELD => '99999',
           \App\Models\UserBalance::STATUS_FIELD => \App\Models\UserBalance::VALID_STATUS
        ]);
    }
}
