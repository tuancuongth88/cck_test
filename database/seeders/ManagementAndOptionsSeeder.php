<?php

namespace Database\Seeders;

use App\Imports\MasterManagementOptionImport;
use App\Models\MasterManagementOption;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ManagementAndOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!MasterManagementOption::first()) {
            $pathFileImport = __DIR__.'/import-excel/master_management_and_options.xlsx';
            Excel::import(new MasterManagementOptionImport, $pathFileImport);
        }
    }
}
