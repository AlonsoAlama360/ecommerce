<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                  ->orWhere('contact_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('ruc', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->get('status') !== '') {
            $query->where('is_active', $request->get('status'));
        }

        if ($city = $request->get('city')) {
            $query->where('city', $city);
        }

        $perPage = $request->get('per_page', 10);
        $suppliers = $query->latest()->paginate($perPage)->withQueryString();

        $totalSuppliers = Supplier::count();
        $activeSuppliers = Supplier::where('is_active', true)->count();
        $inactiveSuppliers = Supplier::where('is_active', false)->count();
        $newSuppliersWeek = Supplier::where('created_at', '>=', now()->subWeek())->count();

        $cities = Supplier::whereNotNull('city')->where('city', '!=', '')->distinct()->orderBy('city')->pluck('city');

        return view('admin.suppliers.index', compact(
            'suppliers', 'totalSuppliers', 'activeSuppliers', 'inactiveSuppliers', 'newSuppliersWeek', 'cities'
        ));
    }

    public function create()
    {
        return redirect()->route('admin.suppliers.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
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

        $validated['is_active'] = $request->boolean('is_active');

        Supplier::create($validated);

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Proveedor creado exitosamente.');
    }

    public function edit(Supplier $supplier)
    {
        return redirect()->route('admin.suppliers.index');
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
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

        $validated['is_active'] = $request->boolean('is_active');

        $supplier->update($validated);

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Proveedor actualizado exitosamente.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Proveedor eliminado exitosamente.');
    }
}
