<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\JsonResponse;
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

    public function store(UserShipmentRequest $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validatedShipment();

        return (new UserShipmentResource(
            $this->shipmentService->createShipment(
                $data['from'],
                $data['to'],
                $data['parcel'],
                $user
            )
        ))->response()->setStatusCode(201);
    }

    public function show(string $id): Response
    {
        $dto = $this->shipmentService->findShipment($id);

        return Inertia::render('Shipments/Show', [
            'shipment' => new UserShipmentResource($dto),
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
