<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Supplier\SupplierStoreRequest;
use App\Http\Requests\Api\Supplier\SupplierUpdateRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Resources\SupplierResource;
use App\Services\CnpjService;

class SupplierController extends Controller
{
    protected $cnpjService;

    public function __construct(CnpjService $cnpjService)
    {
        $this->cnpjService = $cnpjService;
    }

    public function fetchCnpj($cnpj)
    {
        $data = $this->cnpjService->fetchCnpj($cnpj);

        if (!$data) {
            return response()->json(['message' => 'CNPJ inválido ou não encontrado'], 404);
        }

        return response()->json($data);
    }

    public function index(Request $request)
    {
        $query = Supplier::query();

        if ($request->filled('document')) {
            $query->where('document', $request->document);
        }

        if ($request->filled('city')) {
            $query->whereHas('address', function ($q) use ($request) {
                $q->where('city', $request->city);
            });
        }

        if ($request->filled('state')) {
            $query->whereHas('address', function ($q) use ($request) {
                $q->where('state', strtoupper($request->state));
            });
        }

        $allowedSorts = ['name', 'document', 'email', 'phone', 'created_at'];
        $sortBy = $request->get('sortBy');

        if ($sortBy && in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, 'asc');
        }

        $perPage = $request->filled('per_page') ? (int)$request->per_page : 10;
        $suppliers = $query->with('address')->paginate($perPage);

        return SupplierResource::collection($suppliers);
    }

    public function store(SupplierStoreRequest $request)
    {
        $validated = $request->validated();

        $supplier = Supplier::create([
            'name' => $validated['name'],
            'document' => $validated['document'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
        ]);

        $supplier->address()->create([
            'zipcode' => $validated['address']['zipcode'],
            'street' => $validated['address']['street'] ?? null,
            'number' => $validated['address']['number'] ?? null,
            'complement' => $validated['address']['complement'] ?? null,
            'neighborhood' => $validated['address']['neighborhood'] ?? null,
            'city' => $validated['address']['city'] ?? null,
            'state' => $validated['address']['state'] ?? null,
        ]);

        return (new SupplierResource($supplier->load('address')))
            ->response()
            ->setStatusCode(201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        return SupplierResource::collection($supplier->load('address'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SupplierUpdateRequest $request, Supplier $supplier)
    {
        $validated = $request->validated();

        $supplier->update([
            'name' => $validated['name'],
            'document' => $validated['document'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
        ]);

        $supplier->address()->updateOrCreate(
            [],
            [
                'zipcode' => $validated['address']['zipcode'],
                'street' => $validated['address']['street'] ?? null,
                'number' => $validated['address']['number'] ?? null,
                'complement' => $validated['address']['complement'] ?? null,
                'neighborhood' => $validated['address']['neighborhood'] ?? null,
                'city' => $validated['address']['city'] ?? null,
                'state' => $validated['address']['state'] ?? null,
            ]
        );

        return (new SupplierResource($supplier->load('address')))
            ->response()
            ->setStatusCode(200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return response()->json(null, 204);
    }
}
