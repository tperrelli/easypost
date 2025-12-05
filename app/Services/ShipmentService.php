<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserShipment;
use App\Repositories\UserShipmentRepository;
use App\Services\Web\EasyPostWebService;

class ShipmentService
{
    public function __construct(
        private UserShipmentRepository $userShipmentRepository, 
        private EasyPostWebService $easyPostWebService
    ) {}

    public function createShipment(array $from, array $to, array $parcel, User $user): UserShipment
    {
        $response = $this->easyPostWebService->createShipment($from, $to, $parcel, $user->reference);

        return $this->userShipmentRepository->create([
            'status' => $response['status'],
            'user_id' => $user->id,
            'shipment_id' => $response['id'],
        ]);
    }

    public function findShipment(string $id): array
    {
        $shipment = $this->userShipmentRepository->findByShipmentId($id);
        return $this->easyPostWebService->retrieveShipment($shipment->shipment_id);
    }

    public function printLabel(string $shipmentId, string $format)
    {
        return $this->easyPostWebService->printLabel($shipmentId, $format);
    }
}