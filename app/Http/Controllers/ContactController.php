<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Support\MathCaptcha;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('pages.contact', [
            'captchaQuestion' => MathCaptcha::generate('contact'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'contact' => ['required', 'string', 'max:100'],
            'subject' => ['nullable', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:2000'],
            'captcha' => ['required', 'integer', function ($attribute, $value, $fail): void {
                if (! MathCaptcha::check('contact', $value)) {
                    $fail('Jawaban captcha salah. Silakan coba lagi.');
                }
            }],
        ]);

        ContactMessage::create(Arr::except($validated, ['captcha']));

        MathCaptcha::forget('contact');

        return redirect()
            ->route('contact.index')
            ->with('success', 'Pesan Anda berhasil dikirim. Terima kasih, kami akan segera menindaklanjuti.');
    }
}
