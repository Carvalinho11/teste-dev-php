<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_supplier_with_cnpj()
    {
        $user = User::factory()->create();

        $token = $user->createToken('api-token')->plainTextToken;

        $payload = [
            'name' => 'Empresa Exemplo',
            'document' => '12345678000195',
            'email' => 'contato@empresa.com',
            'phone' => '(11) 91234-5678',
            'address' => [
                'zipcode' => '12345-678',
                'street' => 'Rua das Flores',
                'number' => '100',
                'complement' => 'Sala 12',
                'neighborhood' => 'Centro',
                'city' => 'São Paulo',
                'state' => 'SP'
            ]
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/suppliers', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'Empresa Exemplo',
                'document' => '12.345.678/0001-95',
                'email' => 'contato@empresa.com',
                'phone' => '(11) 91234-5678',
                'zipcode' => '12345-678',
                'city' => 'São Paulo'
            ]);
    }

    public function test_authenticated_user_can_create_supplier_with_cpf()
    {
        $user = User::factory()->create();

        $token = $user->createToken('api-token')->plainTextToken;

        $payload = [
            'name' => 'Empresa Exemplo',
            'document' => '12345678912',
            'email' => 'contato@empresa.com',
            'phone' => '(11) 91234-5678',
            'address' => [
                'zipcode' => '12345-678',
                'street' => 'Rua das Flores',
                'number' => '100',
                'complement' => 'Sala 12',
                'neighborhood' => 'Centro',
                'city' => 'São Paulo',
                'state' => 'SP'
            ]
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/suppliers', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'Empresa Exemplo',
                'document' => '123.456.789-12',
                'email' => 'contato@empresa.com',
                'phone' => '(11) 91234-5678',
                'zipcode' => '12345-678',
                'city' => 'São Paulo'
            ]);
    }

    public function test_authenticated_user_can_update_supplier()
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $supplier = Supplier::factory()
            ->hasAddress()
            ->create([
                'name' => 'Fornecedor Original',
                'document' => '12345678000195',
                'email' => 'original@empresa.com',
                'phone' => '11912345678',
            ]);

        $payload = [
            'name' => 'Fornecedor Atualizado',
            'document' => '12345678000195',
            'email' => 'atualizado@empresa.com',
            'phone' => '(11) 99999-9999',
            'address' => [
                'zipcode' => '54321-000',
                'street' => 'Rua Atualizada',
                'number' => '200',
                'complement' => 'Sala 99',
                'neighborhood' => 'Bairro Novo',
                'city' => 'Campinas',
                'state' => 'SP'
            ]
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                        ->putJson("/api/suppliers/{$supplier->id}", $payload);

        $response->assertStatus(200)
                ->assertJsonFragment([
                    'name' => 'Fornecedor Atualizado',
                    'email' => 'atualizado@empresa.com',
                    'phone' => '(11) 99999-9999',
                    'city' => 'Campinas',
                    'zipcode' => '54321-000',
                ]);
    }

   public function test_authenticated_user_can_delete_supplier()
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $supplier = Supplier::factory()->create();

        $supplier->address()->create(
            Address::factory()->make()->toArray()
        );

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                        ->deleteJson("/api/suppliers/{$supplier->id}");

        $response->assertStatus(204);
    }

    public function test_authenticated_user_can_list_suppliers_with_filters_and_sorting()
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $supplier1 = Supplier::factory()->create([
            'name' => 'Alpha Ltda',
            'document' => '12345678000195',
            'email' => 'alpha@example.com',
            'phone' => '11912345678',
        ]);

        $supplier1->address()->create(
            Address::factory()->make([
                'city' => 'São Paulo',
                'state' => 'SP',
            ])->toArray()
        );

        $supplier2 = Supplier::factory()->create([
            'name' => 'Beta Ltda',
            'document' => '98765432000195',
            'email' => 'beta@example.com',
            'phone' => '11987654321',
        ]);

        $supplier2->address()->create(
            Address::factory()->make([
                'city' => 'Rio de Janeiro',
                'state' => 'RJ',
            ])->toArray()
        );

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/suppliers?city=São Paulo&sortBy=name&per_page=10');

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Alpha Ltda']);
        $response->assertJsonMissing(['name' => 'Beta Ltda']);
    }
}
