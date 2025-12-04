<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Http\Requests\UserShipmentRequest;
use App\Repositories\UserShipmentRepository;
use App\Http\Resources\UserShipmentResource;
use App\Services\Web\EasyPostWebService;
use Illuminate\Support\Facades\Auth;

class ShipmentController extends Controller
{
    public function __construct(
        private Auth $auth,
        private UserShipmentRepository $userShipmentRepository, 
        private EasyPostWebService $easyPostWebService
    ){}

    public function index()
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
        $response = $this->easyPostWebService->createShipment(
            $request->input('from'),
            $request->input('to'),
            $request->input('parcel'),
            $this->auth->user()->reference
        );

        return UserShipmentResource::make(
            $this->userShipmentRepository->create([
                'status' => $response['status'],
                'user_id' => $this->auth->id(),
                'shipment_id' => $response['id'],
            ])
        );
    }

    public function show(string $id)
    {
        $shipment = $this->userShipmentRepository->findByShipmentId($id);
        $response = $this->easyPostWebService->retrieveShipment($shipment->shipment_id);

        return Inertia::render('Shipments/Show', [
            'shipment' => $response
        ]);
    }
}
