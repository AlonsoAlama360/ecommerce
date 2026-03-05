<?php

namespace App\Http\Controllers\Admin;

use App\Application\Subscriber\DTOs\SubscriberFiltersDTO;
use App\Application\Subscriber\UseCases\DeleteSubscriber;
use App\Application\Subscriber\UseCases\ListSubscribers;
use App\Application\Subscriber\UseCases\ToggleSubscriberStatus;
use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function __construct(
        private ListSubscribers $listSubscribers,
        private ToggleSubscriberStatus $toggleSubscriberStatus,
        private DeleteSubscriber $deleteSubscriber,
    ) {}

    public function index(Request $request)
    {
        $dto = SubscriberFiltersDTO::fromRequest($request);
        $result = $this->listSubscribers->execute($dto);

        return view('admin.subscribers.index', [
            'subscribers' => $result['subscribers'],
            'totalSubscribers' => $result['totalSubscribers'],
            'activeSubscribers' => $result['activeSubscribers'],
            'inactiveSubscribers' => $result['inactiveSubscribers'],
            'newThisWeek' => $result['newThisWeek'],
        ]);
    }

    public function toggleStatus(Subscriber $subscriber)
    {
        $subscriber = $this->toggleSubscriberStatus->execute($subscriber);

        $status = $subscriber->is_active ? 'activado' : 'desactivado';
        return back()->with('success', "Suscriptor {$status} exitosamente.");
    }

    public function destroy(Subscriber $subscriber)
    {
        $this->deleteSubscriber->execute($subscriber);

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
