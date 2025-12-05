<?php

namespace App\Services;

use App\Models\User;
use App\DTO\ShipmentDTO;
use App\Repositories\UserShipmentRepository;
use App\Services\Web\EasyPostWebService;
use Illuminate\Support\Facades\Cache;

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

        $cacheKey = "shipment_remote_{$shipment->shipment_id}";

        $remote = Cache::remember($cacheKey, now()->addMinutes(60), function () use ($shipment) {
            return $this->easyPostWebService->retrieveShipment($shipment->shipment_id);
        });

        return new ShipmentDTO(
            local: $shipment,
            remote: $remote
        );
    }

    public function printLabel(string $shipmentId, string $format)
    {
        return $this->easyPostWebService->printLabel($shipmentId, $format);
    }
}