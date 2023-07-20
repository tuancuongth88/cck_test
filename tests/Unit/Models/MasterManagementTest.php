<?php

namespace Tests\Unit\Models;

use App\Models\MasterManagement;
use App\Models\MasterManagementOption;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MasterManagementTest extends TestCase
{
    use  WithFaker;
    /** @test */
    public function a_master_manager_has_a_option()
    {
        $masterManager = MasterManagement::factory()->has(MasterManagementOption::factory()
                                                                                ->count(3)
                                                                                ->state(function (array $attributes, MasterManagement $master){
                                                                                            return ['master_management_id' => $master->id];
                                                                                        }))->create();
        // Method 1:
        $this->assertInstanceOf(MasterManagement::class, $masterManager);

        // Method 2:
        $this->assertEquals(3, $masterManager->masterManagementOption->count());
    }
}
