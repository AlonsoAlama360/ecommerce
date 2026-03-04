<?php

namespace App\Console\Commands;

use App\Models\ProductImage;
use App\Services\ImageService;
use Illuminate\Console\Command;

class GenerateProductThumbnails extends Command
{
    protected $signature = 'products:generate-thumbnails';
    protected $description = 'Genera thumbnails para imágenes de productos existentes';

    public function handle(ImageService $imageService): int
    {
        $images = ProductImage::whereNull('thumbnail_url')
            ->where('image_url', 'not like', 'http%')
            ->get();

        if ($images->isEmpty()) {
            $this->info('No hay imágenes pendientes de thumbnail.');
            return 0;
        }

        $this->info("Generando thumbnails para {$images->count()} imágenes...");
        $bar = $this->output->createProgressBar($images->count());
        $bar->start();

        $success = 0;
        $failed = 0;

        foreach ($images as $image) {
            $storagePath = str_replace('/storage/', '', $image->image_url);
            $thumbnailPath = $imageService->generateThumbnail($storagePath);

            if ($thumbnailPath) {
                $image->update(['thumbnail_url' => $thumbnailPath]);
                $success++;
            } else {
                $failed++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Completado: {$success} thumbnails generados, {$failed} fallidos.");

        return 0;
    }
}
