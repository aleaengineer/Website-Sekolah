<?php

namespace App\Support;

class MathCaptcha
{
    /**
     * Create a random addition/subtraction question and remember the answer in session.
     */
    public static function generate(string $key): string
    {
        $left = random_int(2, 9);
        $right = random_int(2, 9);

        if (random_int(0, 1) === 1) {
            session([self::sessionKey($key) => $left + $right]);

            return "{$left} + {$right}";
        }

        if ($right > $left) {
            [$left, $right] = [$right, $left];
        }

        session([self::sessionKey($key) => $left - $right]);

        return "{$left} - {$right}";
    }

    public static function check(string $key, mixed $answer): bool
    {
        return (int) $answer === (int) session(self::sessionKey($key));
    }

    public static function forget(string $key): void
    {
        session()->forget(self::sessionKey($key));
    }

    public static function sessionKey(string $key): string
    {
        return "captcha_{$key}";
    }
}
