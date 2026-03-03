<?php

namespace App\Application\User\UseCases;

use App\Application\User\DTOs\SocialUserDTO;
use App\Application\User\Ports\AuthServiceInterface;
use App\Application\User\Ports\MailServiceInterface;
use App\Domain\User\Exceptions\UserInactiveException;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\Services\UserDomainService;
use App\Models\User;
use Illuminate\Support\Str;

class SocialLogin
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private AuthServiceInterface $authService,
        private MailServiceInterface $mailService,
        private UserDomainService $domainService,
    ) {}

    public function execute(SocialUserDTO $dto): User
    {
        $providerIdField = "{$dto->provider}_id";

        $user = $dto->provider === 'google'
            ? $this->userRepository->findByGoogleId($dto->providerId)
            : $this->userRepository->findByFacebookId($dto->providerId);

        if (!$user && $dto->email) {
            $user = $this->userRepository->findByEmail($dto->email);

            if ($user) {
                $this->userRepository->update($user, [$providerIdField => $dto->providerId]);
            }
        }

        if (!$user) {
            $nameParts = explode(' ', $dto->name, 2);

            $user = $this->userRepository->create([
                'first_name' => $nameParts[0],
                'last_name' => $nameParts[1] ?? '',
                'email' => $dto->email,
                $providerIdField => $dto->providerId,
                'password' => bcrypt(Str::random(24)),
                'role' => 'cliente',
                'is_active' => true,
                'auth_provider' => $dto->provider,
            ]);

            $this->mailService->sendWelcomeEmail($user);
        }

        $this->domainService->ensureUserIsActive($user);

        $this->authService->login($user, true);
        $this->authService->regenerateSession();

        return $user;
    }
}
