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
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function export(Request $request): StreamedResponse
    {
        $query = Supplier::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                  ->orWhere('contact_name', 'like', "%{$search}%")
                  ->orWhere('ruc', 'like', "%{$search}%");
            });
        }

        $filename = 'proveedores_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, [
                'Razón Social', 'Contacto', 'Email', 'Teléfono',
                'RUC', 'Dirección', 'Ciudad', 'Estado', 'Notas',
                'Fecha Registro',
            ], ';');

            $query->latest()->chunk(500, function ($suppliers) use ($handle) {
                foreach ($suppliers as $supplier) {
                    fputcsv($handle, [
                        $supplier->business_name,
                        $supplier->contact_name,
                        $supplier->email ?? '',
                        $supplier->phone ?? '',
                        $supplier->ruc ?? '',
                        $supplier->address ?? '',
                        $supplier->city ?? '',
                        $supplier->is_active ? 'Activo' : 'Inactivo',
                        $supplier->notes ?? '',
                        $supplier->created_at->format('d/m/Y H:i'),
                    ], ';');
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function destroy(Supplier $supplier, DeleteSupplier $deleteSupplier)
    {
        $deleteSupplier->execute($supplier);

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Proveedor eliminado exitosamente.');
    }
}
