<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{

    private function formatZipcode($zipcode)
    {
        $numbers = preg_replace('/\D/', '', $zipcode);

        if (strlen($numbers) === 8) {
            return substr($numbers, 0, 5) . '-' . substr($numbers, 5);
        }

        return $zipcode;
    }
    public function toArray($request)
    {
        return [
            'zipcode' => $this->formatZipcode($this->zipcode),
            'street' => $this->street,
            'number' => $this->number,
            'complement' => $this->complement,
            'neighborhood' => $this->neighborhood,
            'city' => $this->city,
            'state' => $this->state,
        ];
    }
}
