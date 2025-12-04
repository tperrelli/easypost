<?php

namespace Tests\Feature\Shipment;

use App\Models\User;
use App\Models\UserShipment;
use App\Services\Web\EasyPostWebService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;
use Mockery;

class ShipmentTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // cria um usuário autenticado
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function index_returns_shipments_page()
    {
        $response = $this->get(route('shipments.index'));

        $response->assertStatus(200)
                 ->assertInertia(fn (Assert $page) =>
                     $page->component('Shipments/Index')
                          ->has('shipments')
                 );
    }

    /** @test */
    public function create_returns_create_page()
    {
        $response = $this->get(route('shipments.create'));

        $response->assertStatus(200)
                 ->assertInertia(fn (Assert $page) =>
                     $page->component('Shipments/Create')
                 );
    }

    /** @test */
    public function store_creates_shipment_and_returns_resource()
    {
        // Mock do serviço EasyPost
        $mockService = Mockery::mock(EasyPostWebService::class);
        $mockService->shouldReceive('createShipment')
            ->once()
            ->andReturn([
                'id' => 'shp_test_123',
                'status' => 'created',
                'shipment_id' => 'shp_test_123'
            ]);

        $this->app->instance(EasyPostWebService::class, $mockService);

        $payload = [
            'to' => [
                'name' => 'Dr. Steve Brule',
                'street1' => '179 N Harbor Dr',
                'city' => 'Redondo Beach',
                'state' => 'CA',
                'zip' => '90277',
                'country' => 'US',
                'phone' => '8573875756',
                'email' => 'dr_steve_brule@gmail.com',
            ],
            'from' => [
                'name' => 'EasyPost',
                'street1' => '417 Montgomery Street',
                'city' => 'San Francisco',
                'state' => 'CA',
                'zip' => '94104',
                'country' => 'US',
                'phone' => '4153334445',
                'email' => 'support@easypost.com',
            ],
            'parcel' => [
                'length' => 20.2,
                'width' => 10.9,
                'height' => 5,
                'weight' => 65.9,
            ],
        ];

        $response = $this->actingAs($this->user)->postJson(route('shipments.store'), $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'shipment_id' => 'shp_test_123',
                     'status' => 'created',
                     'user_id' => $this->user->id,
                 ]);

        $this->assertDatabaseHas('user_shipments', [
            'shipment_id' => 'shp_test_123',
            'user_id' => $this->user->id,
            'status' => 'created',
        ]);
    }

    /** @test */
    public function show_returns_show_page_with_shipment()
    {
        $mockService = Mockery::mock(EasyPostWebService::class);
        $mockService->shouldReceive('retrieveShipment')
            ->once()
            ->andReturn([
                'id' => 'shp_test_123',
                'status' => 'created',
                'shipment_id' => 'shp_test_123'
            ]);

        $this->app->instance(EasyPostWebService::class, $mockService);

        $shipment = UserShipment::factory()->create([
            'user_id' => $this->user->id,
            'shipment_id' => 'shp_test_123',
        ]);

        $response = $this->get(route('shipments.show', $shipment->id));
        $response->assertStatus(200)
                 ->assertInertia(fn (Assert $page) =>
                     $page->component('Shipments/Show')
                          ->has('shipment')
                 );
    }
}
