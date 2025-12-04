<?php

namespace App\Repositories;

use App\Models\UserShipment;
use Illuminate\Support\Facades\Auth;

class UserShipmentRepository
{
    private $userId;
    private $model = UserShipment::class;

    public function __construct(Auth $auth)
    {
        $this->userId = $auth::id();
    }

    public function all()
    {
        return ($this->model)::where('user_id', $this->userId)->get();
    }
   
    public function create(array $data): UserShipment
    {
        return ($this->model)::create($data);
    }

    public function findByShipmentId(string $shipmentId): ?UserShipment
    {
        return ($this->model)::where('id', $shipmentId)
            ->where('user_id', $this->userId)
            ->first();
    }
}