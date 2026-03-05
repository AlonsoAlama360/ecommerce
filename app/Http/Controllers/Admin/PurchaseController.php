<?php

namespace App\Http\Controllers\Admin;

use App\Application\Purchase\DTOs\CreatePurchaseDTO;
use App\Application\Purchase\DTOs\PurchaseFiltersDTO;
use App\Application\Purchase\DTOs\UpdatePurchaseDTO;
use App\Application\Purchase\UseCases\CreatePurchase;
use App\Application\Purchase\UseCases\DeletePurchase;
use App\Application\Purchase\UseCases\ListPurchases;
use App\Application\Purchase\UseCases\ShowPurchase;
use App\Application\Purchase\UseCases\UpdatePurchase;
use App\Application\Purchase\UseCases\UpdatePurchaseStatus;
use App\Domain\Purchase\Repositories\PurchaseRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index(Request $request, ListPurchases $listPurchases)
    {
        $dto = PurchaseFiltersDTO::fromRequest($request);
        $data = $listPurchases->execute($dto);

        return view('admin.purchases.index', $data);
    }

    public function create()
    {
        return redirect()->route('admin.purchases.index');
    }

    public function store(Request $request, CreatePurchase $createPurchase)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'expected_date' => 'nullable|date',
            'notes' => 'nullable|string|max:2000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
        ]);

        $dto = CreatePurchaseDTO::fromRequest($request);
        $purchase = $createPurchase->execute($dto);

        return redirect()->route('admin.purchases.index')
            ->with('success', "Compra {$purchase->purchase_number} creada exitosamente.");
    }

    public function show(Purchase $purchase, ShowPurchase $showPurchase)
    {
        $purchase = $showPurchase->execute($purchase);
        return response()->json($purchase);
    }

    public function update(Request $request, Purchase $purchase, UpdatePurchase $updatePurchase)
    {
        $request->validate([
            'supplier_id' => 'sometimes|exists:suppliers,id',
            'status' => 'sometimes|in:pendiente,aprobado,en_transito,recibido,cancelado',
            'expected_date' => 'nullable|date',
            'notes' => 'nullable|string|max:2000',
            'shipping_address' => 'nullable|string|max:1000',
            'items' => 'sometimes|array|min:1',
            'items.*.product_id' => 'required_with:items|exists:products,id',
            'items.*.quantity' => 'required_with:items|integer|min:1',
            'items.*.unit_cost' => 'required_with:items|numeric|min:0',
        ]);

        $dto = UpdatePurchaseDTO::fromRequest($request);
        $updatedPurchase = $updatePurchase->execute($dto, $purchase);

        if ($request->ajax()) {
            return response()->json(['message' => 'Compra actualizada', 'purchase' => $updatedPurchase]);
        }

        return redirect()->route('admin.purchases.index')
            ->with('success', 'Compra actualizada exitosamente.');
    }

    public function updateStatus(Request $request, Purchase $purchase, UpdatePurchaseStatus $updateStatus)
    {
        $validated = $request->validate([
            'status' => 'required|in:pendiente,aprobado,en_transito,recibido,cancelado',
        ]);

        $purchase = $updateStatus->execute($purchase, $validated['status']);

        return response()->json([
            'message' => 'Estado actualizado',
            'status' => $purchase->status,
            'status_label' => $purchase->status_label,
        ]);
    }

    public function destroy(Purchase $purchase, DeletePurchase $deletePurchase)
    {
        $deletePurchase->execute($purchase);

        return redirect()->route('admin.purchases.index')
            ->with('success', 'Compra eliminada exitosamente.');
    }

    public function searchSuppliers(Request $request, PurchaseRepositoryInterface $purchaseRepository)
    {
        $search = $request->get('q', '');
        return response()->json($purchaseRepository->searchSuppliers($search));
    }

    public function searchProducts(Request $request, PurchaseRepositoryInterface $purchaseRepository)
    {
        $search = $request->get('q', '');
        return response()->json($purchaseRepository->searchProducts($search));
    }
}
