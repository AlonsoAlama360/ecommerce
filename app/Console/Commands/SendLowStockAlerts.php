<?php

namespace App\Console\Commands;

use App\Mail\LowStockAlertMail;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Services\AdminNotificationService;
use Illuminate\Console\Command;

class SendLowStockAlerts extends Command
{
    protected $signature = 'stock:alert';
    protected $description = 'Send email alerts for products with low stock';

    public function handle(): int
    {
        if (!AdminNotificationService::isEnabled('notify_low_stock')) {
            $this->info('Low stock notifications are disabled.');
            return Command::SUCCESS;
        }

        $threshold = (int) SiteSetting::get('low_stock_threshold', 5);
        $emails = AdminNotificationService::getNotificationEmails();

        if (empty($emails)) {
            $this->warn('No notification emails configured.');
            return Command::FAILURE;
        }

        $products = Product::where('is_active', true)
            ->where('stock', '<=', $threshold)
            ->with('category')
            ->orderBy('stock')
            ->get();

        if ($products->isEmpty()) {
            $this->info('No products with low stock.');
            return Command::SUCCESS;
        }

        AdminNotificationService::send('notify_low_stock', new LowStockAlertMail($products, $threshold));

        $this->info("Low stock alert sent to " . implode(', ', $emails) . ": {$products->count()} product(s).");

        return Command::SUCCESS;
    }
}
