<?php

namespace App\Services;

use App\Models\User;
use App\DTO\ShipmentDTO;
use App\Repositories\UserShipmentRepository;
use App\Services\Web\EasyPostWebService;

class ShipmentService
{
    public function __construct(
        private UserShipmentRepository $userShipmentRepository, 
        private EasyPostWebService $easyPostWebService
    ) {}

    public function createShipment(array $from, array $to, array $parcel, User $user): ShipmentDTO
    {
        $response = $this->easyPostWebService->createShipment($from, $to, $parcel, $user->reference);

        return new ShipmentDTO(
            local: $this->userShipmentRepository->create([
                'status' => $response['status'],
                'user_id' => $user->id,
                'shipment_id' => $response['id'],
            ]),
            remote: $response
        );
    }

    public function findShipment(string $id): ShipmentDTO
    {
        $shipment = $this->userShipmentRepository->findByShipmentId($id);

        return new ShipmentDTO(
            local: $shipment,
            remote: $this->easyPostWebService->retrieveShipment($shipment->shipment_id)
        );
    }

    public function printLabel(string $shipmentId, string $format)
    {
        return $this->easyPostWebService->printLabel($shipmentId, $format);
    }
}