<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\CnpjService;
use Illuminate\Support\Facades\Cache;
use Mockery;

class SupplierCnpjFetchTest extends TestCase
{
    public function test_fetch_cnpj_not_found()
    {
        $cnpjServiceMock = Mockery::mock(CnpjService::class);
        $cnpjServiceMock->shouldReceive('fetchCnpj')
            ->once()
            ->with('00000000000000')
            ->andReturn(null);

        $this->app->instance(CnpjService::class, $cnpjServiceMock);

        $response = $this->getJson('/api/suppliers/cnpj/00000000000000/fetch');

        $response->assertStatus(404)
                 ->assertJson(['message' => 'CNPJ inválido ou não encontrado']);
    }
}
