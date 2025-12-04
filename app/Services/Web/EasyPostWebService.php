<?php

namespace App\Services\Web;

use Illuminate\Support\Facades\Http;

class EasyPostWebService
{
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('easypost.api_key');
        $this->baseUrl = config('easypost.base_url');
    }

    public function createShipment(array $from, array $to, array $parcel, string $reference)
    {
        try {

            $response = Http::withBasicAuth($this->apiKey, '')
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($this->url('shipments'), [
                'shipment' => [
                    'to_address' => [
                        'name'    => $from['name'] ?? 'EasyPost',
                        'street1' => $from['street1'] ?? '417 Redondo Ave',
                        'city'    => $from['city'] ?? 'Redondo Beach',
                        'state'   => $from['state'] ?? 'CA',
                        'zip'     => $from['zip'] ?? '90277',
                        'country' => $from['country'] ?? 'US',
                        'phone'   => $from['phone'] ?? '310-808-5243'
                    ],
                    'from_address' => [
                        'name'    => $to['name'] ?? 'EasyPost',
                        'street1' => $to['street1'] ?? '118 2nd St',
                        'city'    => $to['city'] ?? 'San Francisco',
                        'state'   => $to['state'] ?? 'CA',
                        'zip'     => $to['zip'] ?? '94105',
                        'country' => $to['country'] ?? 'US',
                        'phone'   => $to['phone'] ?? '415-528-7555'
                    ],
                    'parcel' => [
                        'length' => $parcel['length'] ?? 10,
                        'width'  => $parcel['width'] ?? 8,
                        'height' => $parcel['height'] ?? 4,
                        'weight' => $parcel['weight'] ?? 15
                    ],
                    'reference' => $reference
                ]
            ]);
            
            if ($response->failed()) {
                throw new \Exception('EasyPost API error: ' . $response->body());
            }

            return $response->json();

        } catch (\Throwable $e) {
            throw new \Exception('Error creating shipment: ' . $e->getMessage());
        }
    }

    public function retrieveShipment(string $shipmentId)
    {
        try {

            $response = Http::withBasicAuth($this->apiKey, '')
                ->withHeaders(['Content-Type' => 'application/json'])
                ->get($this->url("shipments/{$shipmentId}"));
            
            if ($response->failed()) {
                throw new \Exception('EasyPost API error: ' . $response->body());
            }

            return $response->json();

        } catch (\Throwable $e) {
            throw new \Exception('Error retrieving shipment: ' . $e->getMessage());
        }
    }

    public function printLabel(string $shipmentId, string $fileFormat = 'ZPL')
    {
        try {

            $reponse = Http::post($this->url("shipments/{$shipmentId}/label"))
                ->withBasicAuth($this->apiKey, '')
                ->withBody(json_encode([
                    'file_format' => $fileFormat
                ]))
                ->withHeader('Content-Type', 'application/json')
                ->send();
        
            if ($reponse->failed()) {
                throw new \Exception('EasyPost API error: ' . $reponse->body());
            }

        } catch (\Throwable $e) {
            throw new \Exception('Error printing label: ' . $e->getMessage());
        }
    }

    private function url(string $url): string
    {
        return $this->baseUrl . ltrim($url, '/');
    }
}

// curl -X GET https://api.easypost.com/v2/shipments/shp_.../label?file_format=ZPL \
//   -u "EASYPOST_API_KEY":