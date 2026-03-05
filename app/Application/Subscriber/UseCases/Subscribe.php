<?php

namespace App\Application\Subscriber\UseCases;

use App\Domain\Subscriber\Repositories\SubscriberRepositoryInterface;
use App\Mail\NewsletterSubscribedMail;
use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Subscribe
{
    public function __construct(
        private SubscriberRepositoryInterface $subscriberRepository,
    ) {}

    public function execute(string $email): array
    {
        $subscriber = $this->subscriberRepository->findByEmail($email);

        if ($subscriber) {
            if ($subscriber->is_active) {
                return [
                    'alreadySubscribed' => true,
                    'message' => 'Ya estás suscrito/a a nuestro newsletter.',
                ];
            }
            $this->subscriberRepository->update($subscriber, ['is_active' => true]);
        } else {
            $this->subscriberRepository->create(['email' => $email]);
        }

        // Update newsletter flag if user exists
        User::where('email', $email)->update(['newsletter' => true]);

        // Send confirmation email
        try {
            Mail::to($email)->send(new NewsletterSubscribedMail($email));
        } catch (\Exception $e) {
            Log::error('Error enviando email newsletter: ' . $e->getMessage());
        }

        return [
            'alreadySubscribed' => false,
            'message' => '¡Te has suscrito exitosamente! Revisa tu correo.',
        ];
    }
}
