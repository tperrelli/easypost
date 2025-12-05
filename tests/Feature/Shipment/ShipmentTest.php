<?php

namespace Tests\Feature\Shipment;

use App\DTO\ShipmentDTO;
use App\Models\User;
use App\Models\UserShipment;
use App\Repositories\UserShipmentRepository;
use App\Services\ShipmentService;
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
    public function test_store_controller_returns_resource()
{
    $from = [
        'name' => 'EasyPost',
        'street1' => '417 Montgomery Street',
        'city' => 'San Francisco',
        'state' => 'CA',
        'zip' => '94104',
        'country' => 'US',
        'phone' => '4153334445',
        'email' => 'support@easypost.com',
    ];

    $to = [
        'name' => 'Dr. Steve Brule',
        'street1' => '179 N Harbor Dr',
        'city' => 'Redondo Beach',
        'state' => 'CA',
        'zip' => '90277',
        'country' => 'US',
        'phone' => '8573875756',
        'email' => 'dr_steve_brule@gmail.com',
    ];

    $parcel = [
        'length' => 20.2,
        'width' => 10.9,
        'height' => 5,
        'weight' => 65.9,
    ];

    // Fake model retornado pelo serviço
    $fakeModel = \App\Models\UserShipment::factory()->make([
        'shipment_id' => 'shp_test_123',
        'status' => 'created',
        'user_id' => $this->user->id,
    ]);

    // Mock do ShipmentService
    $mockService = Mockery::mock(\App\Services\ShipmentService::class);
    $mockService->shouldReceive('createShipment')
        ->once()
        ->with($from, $to, $parcel, $this->user)
        ->andReturn(new \App\DTO\ShipmentDTO(
            local: $fakeModel,
            remote: [
                'id' => 'shp_test_123',
                'status' => 'created',
                'shipment_id' => 'shp_test_123',
            ]
        ));

    $this->app->instance(\App\Services\ShipmentService::class, $mockService);

    $payload = [
        'from' => $from,
        'to' => $to,
        'parcel' => $parcel,
    ];

    $response = $this->actingAs($this->user)->postJson(route('shipments.store'), $payload);

    $response->assertStatus(201)
        ->assertJsonFragment([
            'shipment_id' => 'shp_test_123',
            'status' => 'created',
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function test_shipment_service_creates_shipment()
    {
        $from = [
            'name' => 'EasyPost',
            'street1' => '417 Montgomery Street',
            'city' => 'San Francisco',
            'state' => 'CA',
            'zip' => '94104',
            'country' => 'US',
            'phone' => '4153334445',
            'email' => 'support@easypost.com',
        ];

        $to = [
            'name' => 'Dr. Steve Brule',
            'street1' => '179 N Harbor Dr',
            'city' => 'Redondo Beach',
            'state' => 'CA',
            'zip' => '90277',
            'country' => 'US',
            'phone' => '8573875756',
            'email' => 'dr_steve_brule@gmail.com',
        ];

        $parcel = [
            'length' => 20.2,
            'width' => 10.9,
            'height' => 5,
            'weight' => 65.9,
        ];

        $fakeModel = \App\Models\UserShipment::factory()->make([
            'shipment_id' => 'shp_test_123',
            'status' => 'created',
            'user_id' => $this->user->id,
        ]);

        // Mock EasyPostWebService
        $mockWebService = Mockery::mock(\App\Services\Web\EasyPostWebService::class);
        $mockWebService->shouldReceive('createShipment')
            ->once()
            ->with($from, $to, $parcel, (string) $this->user->reference)
            ->andReturn([
                'id' => 'shp_test_123',
                'status' => 'created',
                'shipment_id' => 'shp_test_123',
            ]);

        // Mock Repository
        $mockRepository = Mockery::mock(\App\Repositories\UserShipmentRepository::class);
        $mockRepository->shouldReceive('create')
            ->once()
            ->andReturn($fakeModel);

        $service = new \App\Services\ShipmentService($mockRepository, $mockWebService);

        $dto = $service->createShipment($from, $to, $parcel, $this->user);

        $this->assertInstanceOf(\App\DTO\ShipmentDTO::class, $dto);
        $this->assertEquals('shp_test_123', $dto->local->shipment_id);
        $this->assertEquals('created', $dto->local->status);
        $this->assertEquals('shp_test_123', $dto->remote['id']);
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
