<?php

namespace App\DTO;

use App\Models\UserShipment;

class ShipmentDTO
{
    public function __construct(
        public readonly ?UserShipment $local,
        public readonly array $remote,
    ) {}

    public function toArray(): array
    {
        return [
            // Dados vindos da API (EasyPost)
            'shipment_id' => $this->remote['shipment_id'] ?? $this->local->shipment_id,
            'status'      => $this->remote['status'] ?? $this->local->status,

            'to_address' => $this->remote['to_address'] ?? '',
            'from_address' => $this->remote['from_address'] ?? '',
            'parcel' => $this->remote['parcel'] ?? '',

            // Dados persistidos no banco (UserShipment)
            'user_id'     => $this->local->user_id,
            'id'          => $this->local->id,

            // Se quiser expor tudo:
            'local'       => $this->local->toArray(),
            'remote'      => $this->remote,
        ];
    }
}