<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_all_cars()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.car.index'));

        $response->assertStatus(200);
        
    }

    public function test_can_view_create_car_form()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('admin.car.create'));

        $response->assertStatus(200);
        
    }

    public function test_can_create_car()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $carData = [
            'make' => 'Toyota',
            'model' => 'Camry',
            'year' => 2022,
        ];

        $response = $this->post(route('admin.car.store'), $carData);

        $response->assertRedirect(route('admin.car.index'));
        $this->assertDatabaseHas('cars', $carData);
    }

    public function test_can_view_edit_car_form()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $car = Car::factory()->create();

        $response = $this->get(route('admin.car.edit', ['id' => $car->id]));

        $response->assertStatus(200);
        
    }

    public function test_can_update_car()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $car = Car::factory()->create();

        $updatedCarData = [
            'make' => 'Honda',
            'model' => 'Accord',
            'year' => 2023,
        ];

        $response = $this->post(route('admin.car.update', ['id' => $car->id]), $updatedCarData);

        $response->assertRedirect(route('admin.car.index'));
        $this->assertDatabaseHas('cars', $updatedCarData);
    }

    public function test_can_delete_car()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $car = Car::factory()->create();

        $response = $this->get(route('admin.car.destroy', ['id' => $car->id]));

        $response->assertRedirect(route('admin.car.index'));
        $this->assertDatabaseMissing('cars', ['id' => $car->id]);
    }
}
