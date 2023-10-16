<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\HrOrganization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::query()->insert([
            $this->userArray(111111, '1okuridashi_hanoi@gmail.vn', SUPER_ADMIN),
            $this->userArray(222222,'2okuridashi_hanoi@gmail.vn',COMPANY_MANAGER),
            $this->userArray(333333,'3okuridashi_hanoi@gmail.vn',HR_MANAGER),
            $this->userArray(444444,'4okuridashi_hanoi@gmail.vn',COMPANY),
            $this->userArray(555555,'5okuridashi_hanoi@gmail.vn',HR)
        ]);
        $userCompany = User::query()->where('type', COMPANY)->first();
        $userHRO = User::query()->where('type', HR)->first();
        $this->createUserInfo($userCompany->id,Company::class);
        $this->createUserInfo($userHRO->id,HrOrganization::class);


    }

    private function userArray($loginId, $mail, $type): array
    {
        return [
            'login_id' => $loginId,
            'mail_address' => $mail,
            'password' => Hash::make('123456789CCk'),
            'type' => $type,
            'status' => CONFIRM,
            'created_at' => now()
        ];
    }

    private function createUserInfo($userID, $model){
        $data = $model::factory()->create();
        $data->user_id = $userID;
        $data->save();
    }

}
