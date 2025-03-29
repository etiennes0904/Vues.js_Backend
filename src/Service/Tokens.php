<?php declare(strict_types=1);

namespace App\Service;

use DateTime;
use RuntimeException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Throwable;
use const FILTER_VALIDATE_EMAIL;

final readonly class Tokens
{
    public function __construct(
        #[Autowire(param: 'kernel.secret')]
        private string $secret,
    ) {
    }

    public function generateTokenForUser(string $email, DateTime $expire = new DateTime('+4 hours')): string
    {
        $encoded = json_encode([
            'email' => $email,
            'expire' => $expire->getTimestamp(),
        ]);

        if (false === $encoded) {
            throw new RuntimeException('Failed to encode JSON.');
        }

        $signedData = json_encode([
            $encoded,
            $this->sign($encoded),
        ]);
        if (false === $signedData) {
            throw new RuntimeException('Failed to encode JSON.');
        }

        return base64_encode($signedData);
    }

    public function decodeUserToken(?string $token): ?string
    {
        try {
            [$info, $sign] = json_decode(base64_decode($token), true);

            if ($sign !== $this->sign($info)) {
                return null;
            }

            $info = json_decode($info, true);

            if ($info['expire'] < time()) {
                return null;
            }

            if (isset($info['email']) && filter_var($info['email'], FILTER_VALIDATE_EMAIL)) {
                return $info['email'];
            }

            return null;
        } catch (Throwable) {
            return null;
        }
    }

    private function sign(string $encoded): string
    {
        return hash('sha256', $encoded . '/' . $this->secret);
    }
}
