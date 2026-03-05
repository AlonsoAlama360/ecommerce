<?php

namespace App\Console\Commands;

use App\Mail\AbandonedCartMail;
use App\Models\AbandonedCart;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendAbandonedCartEmails extends Command
{
    protected $signature = 'abandoned-carts:send';
    protected $description = 'Send emails for abandoned carts older than 24 hours';

    public function handle(): int
    {
        $abandonedCarts = AbandonedCart::with('user')
            ->whereNull('email_sent_at')
            ->whereNull('recovered_at')
            ->where('updated_at', '<', now()->subHours(24))
            ->get();

        $sent = 0;

        foreach ($abandonedCarts as $abandonedCart) {
            if (!$abandonedCart->user || !$abandonedCart->user->email) {
                continue;
            }

            $products = $this->resolveProducts($abandonedCart->cart_data);

            if (empty($products)) {
                $abandonedCart->delete();
                continue;
            }

            Mail::to($abandonedCart->user->email)->send(new AbandonedCartMail($abandonedCart, $products));
            $abandonedCart->update(['email_sent_at' => now()]);
            $sent++;
        }

        $this->info("Abandoned cart emails sent: {$sent}");

        return Command::SUCCESS;
    }

    private function resolveProducts(array $cartData): array
    {
        $productIds = array_keys($cartData);
        $dbProducts = Product::whereIn('id', $productIds)
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->get()
            ->keyBy('id');

        $products = [];

        foreach ($cartData as $productId => $item) {
            $product = $dbProducts->get($productId);
            if (!$product) {
                continue;
            }

            $image = $product->primaryImage?->thumbnail_url
                ?? $product->primaryImage?->image_url
                ?? null;

            if ($image && !str_starts_with($image, 'http')) {
                $image = url($image);
            }

            $products[] = [
                'name' => $product->name,
                'price' => (float) $product->price,
                'sale_price' => $product->sale_price ? (float) $product->sale_price : null,
                'quantity' => $item['quantity'] ?? 1,
                'image' => $image,
                'slug' => $product->slug,
            ];
        }

        return $products;
    }
}
