<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingAgency;
use App\Models\ShippingAgencyAddress;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ShippingAgencyController extends Controller
{
    public function index(Request $request)
    {
        $query = ShippingAgency::withCount('addresses');

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->has('status') && $request->get('status') !== '') {
            $query->where('is_active', (bool) $request->get('status'));
        }

        $agencies = $query->latest()->paginate($request->get('per_page', 10))->withQueryString();

        return view('admin.shipping-agencies.index', [
            'agencies' => $agencies,
            'totalAgencies' => ShippingAgency::count(),
            'activeAgencies' => ShippingAgency::where('is_active', true)->count(),
            'inactiveAgencies' => ShippingAgency::where('is_active', false)->count(),
            'totalAddresses' => ShippingAgencyAddress::count(),
        ]);
    }

    public function create()
    {
        return redirect()->route('admin.shipping-agencies.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:shipping_agencies,name',
            'is_active' => 'boolean',
        ]);

        ShippingAgency::create([
            'name' => $request->name,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.shipping-agencies.index')
            ->with('success', 'Agencia creada exitosamente.');
    }

    public function edit(ShippingAgency $shippingAgency)
    {
        return redirect()->route('admin.shipping-agencies.index');
    }

    public function update(Request $request, ShippingAgency $shippingAgency)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('shipping_agencies')->ignore($shippingAgency->id)],
            'is_active' => 'boolean',
        ]);

        $shippingAgency->update([
            'name' => $request->name,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.shipping-agencies.index')
            ->with('success', 'Agencia actualizada exitosamente.');
    }

    public function destroy(ShippingAgency $shippingAgency)
    {
        $shippingAgency->delete();

        return redirect()->route('admin.shipping-agencies.index')
            ->with('success', 'Agencia eliminada exitosamente.');
    }

    // ---- Address management (AJAX) ----

    public function addresses(ShippingAgency $shippingAgency)
    {
        return response()->json(
            $shippingAgency->addresses()->orderBy('address')->get()
        );
    }

    public function storeAddress(Request $request, ShippingAgency $shippingAgency)
    {
        $request->validate([
            'address' => 'required|string|max:500',
        ]);

        $address = $shippingAgency->addresses()->firstOrCreate(
            ['address' => trim($request->address)],
        );

        return response()->json($address, 201);
    }

    public function toggleAddress(ShippingAgencyAddress $address)
    {
        $address->update(['is_active' => !$address->is_active]);

        return response()->json(['is_active' => $address->is_active]);
    }

    public function destroyAddress(ShippingAgencyAddress $address)
    {
        $address->delete();

        return response()->json(['message' => 'Dirección eliminada']);
    }
}
