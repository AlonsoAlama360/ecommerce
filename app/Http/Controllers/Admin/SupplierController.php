<?php

namespace App\Http\Controllers\Admin;

use App\Application\Supplier\DTOs\CreateSupplierDTO;
use App\Application\Supplier\DTOs\SupplierFiltersDTO;
use App\Application\Supplier\DTOs\UpdateSupplierDTO;
use App\Application\Supplier\UseCases\CreateSupplier;
use App\Application\Supplier\UseCases\DeleteSupplier;
use App\Application\Supplier\UseCases\ListSuppliers;
use App\Application\Supplier\UseCases\UpdateSupplier;
use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    public function index(Request $request, ListSuppliers $listSuppliers)
    {
        $dto = SupplierFiltersDTO::fromRequest($request);
        $data = $listSuppliers->execute($dto);

        return view('admin.suppliers.index', $data);
    }

    public function create()
    {
        return redirect()->route('admin.suppliers.index');
    }

    public function store(Request $request, CreateSupplier $createSupplier)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:suppliers,email',
            'phone' => 'nullable|string|max:20',
            'ruc' => 'nullable|string|max:20|unique:suppliers,ruc',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:2000',
            'is_active' => 'boolean',
        ]);

        $dto = CreateSupplierDTO::fromRequest($request);
        $createSupplier->execute($dto);

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Proveedor creado exitosamente.');
    }

    public function edit(Supplier $supplier)
    {
        return redirect()->route('admin.suppliers.index');
    }

    public function update(Request $request, Supplier $supplier, UpdateSupplier $updateSupplier)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'email' => ['nullable', 'email', 'max:255', Rule::unique('suppliers')->ignore($supplier->id)],
            'phone' => 'nullable|string|max:20',
            'ruc' => ['nullable', 'string', 'max:20', Rule::unique('suppliers')->ignore($supplier->id)],
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:2000',
            'is_active' => 'boolean',
        ]);

        $dto = UpdateSupplierDTO::fromRequest($request);
        $updateSupplier->execute($dto, $supplier);

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Proveedor actualizado exitosamente.');
    }

    public function destroy(Supplier $supplier, DeleteSupplier $deleteSupplier)
    {
        $deleteSupplier->execute($supplier);

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Proveedor eliminado exitosamente.');
    }
}
