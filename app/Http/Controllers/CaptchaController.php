<?php

namespace App\Http\Controllers;

use App\Support\MathCaptcha;
use Illuminate\Http\JsonResponse;

class CaptchaController extends Controller
{
    public function __invoke(string $key): JsonResponse
    {
        return response()->json([
            'question' => MathCaptcha::generate($key),
        ]);
    }
}
