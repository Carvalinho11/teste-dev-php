<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;


class CnpjService
{
    public function fetchCnpj(string $cnpj): ?array
    {
        $cleanCnpj = preg_replace('/\D/', '', $cnpj);

        if (strlen($cleanCnpj) !== 14) {
            return null;
        }

        $cacheKey = "cnpj_{$cleanCnpj}";

        return Cache::remember($cacheKey, now()->addHours(12), function () use ($cleanCnpj) {
            $response = Http::get("https://brasilapi.com.br/api/cnpj/v1/{$cleanCnpj}");

            if ($response->failed()) {
                return null;
            }

            return $response->json();
        });
    }

}
