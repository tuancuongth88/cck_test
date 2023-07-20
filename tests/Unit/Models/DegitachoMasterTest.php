<?php

namespace Tests\Unit\Models;

use App\Models\DegitachoMaster;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DegitachoMasterTest extends TestCase
{
    use  WithFaker;

    public function testDatabase()
    {
        // Create a single App\Models\DegitachoMaster instance...
        $masterManager = DegitachoMaster::factory()->create();
        $this->assertInstanceOf(DegitachoMaster::class, $masterManager);
        // Create three App\Models\DegitachoMaster instances...
        $result = DegitachoMaster::factory()->count(3)->create();
        $this->assertEquals(3, $result->count());

    }

    public function testRelationShip(){
//        $masterManager = DegitachoMaster::factory()->has(MasterManagementOption::factory()
//            ->count(3)
//            ->state(function (array $attributes, MasterManagement $master){
//                return ['master_management_id' => $master->id];
//            }))->create();
    }
}
