<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
{
    private function formatPhone($phone)
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (strlen($phone) == 11) {
            return '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 5) . '-' . substr($phone, 7);
        } elseif (strlen($phone) == 10) {
            return '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 4) . '-' . substr($phone, 6);
        }

        return $phone;
    }

    private function formatDocument($document)
    {
        $document = preg_replace('/\D/', '', $document);

        if (strlen($document) == 11) {
            return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $document);
        } elseif (strlen($document) == 14) {
            return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $document);
        }

        return $document;
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'document' => $this->formatDocument($this->document),
            'email' => $this->email,
            'phone' => $this->formatPhone($this->phone),
            'address' => new AddressResource($this->whenLoaded('address')),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
