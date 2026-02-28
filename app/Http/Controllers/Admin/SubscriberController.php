<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function index(Request $request)
    {
        $query = Subscriber::query();

        if ($search = $request->get('search')) {
            $query->where('email', 'like', "%{$search}%");
        }

        if ($request->has('status') && $request->get('status') !== '') {
            $query->where('is_active', $request->get('status'));
        }

        $subscribers = $query->latest()->paginate(15)->withQueryString();

        $totalSubscribers = Subscriber::count();
        $activeSubscribers = Subscriber::where('is_active', true)->count();
        $inactiveSubscribers = Subscriber::where('is_active', false)->count();
        $newThisWeek = Subscriber::where('created_at', '>=', now()->subWeek())->count();

        return view('admin.subscribers.index', compact(
            'subscribers', 'totalSubscribers', 'activeSubscribers', 'inactiveSubscribers', 'newThisWeek'
        ));
    }

    public function toggleStatus(Subscriber $subscriber)
    {
        $subscriber->update(['is_active' => !$subscriber->is_active]);

        $status = $subscriber->is_active ? 'activado' : 'desactivado';
        return back()->with('success', "Suscriptor {$status} exitosamente.");
    }

    public function destroy(Subscriber $subscriber)
    {
        $subscriber->delete();
        return back()->with('success', 'Suscriptor eliminado exitosamente.');
    }

    public function export()
    {
        $subscribers = Subscriber::where('is_active', true)->orderBy('email')->get();

        $callback = function () use ($subscribers) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['Email', 'Estado', 'Fecha de suscripción']);

            foreach ($subscribers as $sub) {
                fputcsv($file, [
                    $sub->email,
                    $sub->is_active ? 'Activo' : 'Inactivo',
                    $sub->created_at->format('d/m/Y H:i'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="suscriptores_' . date('Y-m-d') . '.csv"',
        ]);
    }
}
