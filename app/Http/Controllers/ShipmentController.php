<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\UserShipmentRequest;
use App\Repositories\UserShipmentRepository;
use App\Http\Resources\UserShipmentResource;
use App\Services\Web\EasyPostWebService;

class ShipmentController extends Controller
{
    public function __construct(
        private UserShipmentRepository $userShipmentRepository, 
        private EasyPostWebService $easyPostWebService
    ){}

    public function index(): Response
    {
        return Inertia::render('Shipments/Index', [
            'shipments' => UserShipmentResource::collection(
                $this->userShipmentRepository->all()
            )
        ]);
    }

    public function create()
    {
        return Inertia::render('Shipments/Create');
    }

    public function store(UserShipmentRequest $request): UserShipmentResource
    {
        $user = auth()->user();

        $response = $this->easyPostWebService->createShipment(
            $request->validatedShipment()['from'],
            $request->validatedShipment()['to'],
            $request->validatedShipment()['parcel'],
            $user->reference
        );

        return UserShipmentResource::make(
            $this->userShipmentRepository->create([
                'status' => $response['status'],
                'user_id' => $user->id,
                'shipment_id' => $response['id'],
            ])
        );
    }

    public function show(string $id): Response
    {
        $shipment = $this->userShipmentRepository->findByShipmentId($id);
        $response = $this->easyPostWebService->retrieveShipment($shipment->shipment_id);

        return Inertia::render('Shipments/Show', [
            'shipment' => $response
        ]);
    }
}
