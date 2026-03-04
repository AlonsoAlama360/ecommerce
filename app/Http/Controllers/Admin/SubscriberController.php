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

        $perPage = $request->get('per_page', 15);
        $subscribers = $query->latest()->paginate($perPage)->withQueryString();

        $ss = \DB::selectOne("
            SELECT COUNT(*) as total,
                SUM(is_active = 1) as active,
                SUM(is_active = 0) as inactive,
                SUM(created_at >= ?) as new_week
            FROM subscribers
        ", [now()->subWeek()->toDateTimeString()]);
        $totalSubscribers = (int) $ss->total;
        $activeSubscribers = (int) ($ss->active ?? 0);
        $inactiveSubscribers = (int) ($ss->inactive ?? 0);
        $newThisWeek = (int) ($ss->new_week ?? 0);

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
        return response()->stream(function () {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['Email', 'Estado', 'Fecha de suscripción']);

            Subscriber::where('is_active', true)->orderBy('email')->chunk(500, function ($subscribers) use ($file) {
                foreach ($subscribers as $sub) {
                    fputcsv($file, [
                        $sub->email,
                        $sub->is_active ? 'Activo' : 'Inactivo',
                        $sub->created_at->format('d/m/Y H:i'),
                    ]);
                }
            });

            fclose($file);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="suscriptores_' . date('Y-m-d') . '.csv"',
        ]);
    }
}
