<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    public function about()
    {
        return view('about');
    }

    public function services()
    {
        return view('services');
    }

    public function contact()
    {
        return view('contact');
    }

    public function submitContact(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        $entry = $data + ['created_at' => now()->toDateTimeString()];

        $file = 'contacts/contacts.json';
        if (! Storage::disk('local')->exists('contacts')) {
            Storage::disk('local')->makeDirectory('contacts');
        }

        $existing = [];
        if (Storage::disk('local')->exists($file)) {
            $contents = Storage::disk('local')->get($file);
            $existing = json_decode($contents, true) ?: [];
        }

        $existing[] = $entry;
        Storage::disk('local')->put($file, json_encode($existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return redirect()->back()->with('status', 'Pesan Anda telah dikirim. Kami akan menghubungi Anda segera.');
    }
}
