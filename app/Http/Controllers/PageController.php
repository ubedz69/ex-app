<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    public function about(): View
    {
        return view('about');
    }

    public function services(): View
    {
        return view('services');
    }

    public function contact(): View
    {
        return view('contact');
    }

    public function submitContact(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'message' => 'required|string',
        ]);

        $entry = $data + ['created_at' => now()->toDateTimeString()];

        $file = 'contacts/contacts.json';
        if (! Storage::disk('local')->exists('contacts')) {
            Storage::disk('local')->makeDirectory('contacts');
        }

        try {
            Cache::lock('contacts-write', 10)->block(5, function () use ($file, $entry): void {
                $existing = [];

                if (Storage::disk('local')->exists($file)) {
                    $contents = Storage::disk('local')->get($file);
                    $existing = json_decode($contents, true) ?: [];
                }

                $existing[] = $entry;
                Storage::disk('local')->put($file, json_encode($existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            });
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['contact' => 'Pesan belum tersimpan. Silakan coba lagi.']);
        }

        return redirect()->back()->with('status', 'Pesan Anda telah dikirim. Kami akan menghubungi Anda segera.');
    }
}
