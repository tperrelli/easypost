<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Services\ShipmentService;
use App\Http\Requests\UserShipmentRequest;
use App\Repositories\UserShipmentRepository;
use App\Http\Resources\UserShipmentResource;

class ShipmentController extends Controller
{
    public function __construct(
        private UserShipmentRepository $userShipmentRepository, 
        private ShipmentService $shipmentService,
    ){}

    public function index(): Response
    {
        return Inertia::render('Shipments/Index', [
            'shipments' => UserShipmentResource::collection(
                $this->userShipmentRepository->all()
            )
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Shipments/Create');
    }

    public function store(UserShipmentRequest $request): UserShipmentResource
    {
        $user = $request->user();

        return UserShipmentResource::make(
            $this->shipmentService->createShipment(
                $request->validatedShipment()['from'],
                $request->validatedShipment()['to'],
                $request->validatedShipment()['parcel'],
                $user
            )
        );
    }

    public function show(string $id): Response
    {
        $shipment = $this->shipmentService->findShipment($id);

        return Inertia::render('Shipments/Show', [
            'shipment' => new UserShipmentResource($shipment)
        ]);
    }

    public function print(string $shipmentId)
    {
        $labelData = $this->shipmentService->printLabel($shipmentId, 'PDF');

        return response($labelData, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="label.pdf"');
    }
}
