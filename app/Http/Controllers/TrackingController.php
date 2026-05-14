<?php

namespace App\Http\Controllers;

use App\Services\DHLService;
use App\Services\FedexService;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index(Request $request, DHLService $dhl, FedexService $fedex)
    {
        // Allow quick tracking via query params from header (GET /tracking?tracking_number=...)
        if ($request->filled('tracking_number')) {
            $validated = $request->validate([
                'tracking_number' => 'required|string|max:100',
            ]);

            $raw = trim($validated['tracking_number']);
            $digits = preg_replace('/\D+/', '', $raw);

            if (strlen($digits) === 10) {
                $courier = 'dhl';
            } elseif (strlen($digits) === 12) {
                $courier = 'fedex';
            } else {
                $result = ['error' => 'Nomor resi tidak valid. Gunakan 10 digit untuk DHL atau 12 digit untuk FedEx.'];

                return view('tracking', compact('result'));
            }

            try {
                if ($courier === 'dhl') {
                    $result = $dhl->track($digits);
                } else {
                    $result = $fedex->track($digits);
                }
            } catch (\Throwable $e) {
                report($e);
                $result = ['error' => 'Layanan pelacakan sedang tidak tersedia. Silakan coba lagi nanti.'];
            }

            return view('tracking', compact('result'));
        }

        return view('tracking');
    }

    public function track(
        Request $request,
        DHLService $dhl,
        FedexService $fedex
    ) {

        $validated = $request->validate([
            'tracking_number' => 'required|string|max:100',
        ]);

        $raw = trim($validated['tracking_number']);

        // Enforce only digits in input (user requested "format hanya number")
        if (preg_match('/\D/', $raw)) {
            return view('tracking', ['result' => ['error' => 'Nomor resi harus berupa angka saja (tanpa spasi atau tanda baca).']]);
        }

        $digits = $raw; // already digits-only

        if (strlen($digits) === 10) {
            $courier = 'dhl';
        } elseif (strlen($digits) === 12) {
            $courier = 'fedex';
        } else {
            return view('tracking', ['result' => ['error' => 'Nomor resi tidak valid. Gunakan 10 digit untuk DHL atau 12 digit untuk FedEx.']]);
        }

        try {
            if ($courier === 'dhl') {
                $result = $dhl->track($digits);
            } else {
                $result = $fedex->track($digits);
            }
        } catch (\Throwable $e) {
            report($e);
            $result = ['error' => 'Layanan pelacakan sedang tidak tersedia. Silakan coba lagi nanti.'];
        }

        return view('tracking', compact('result'));
    }
}
