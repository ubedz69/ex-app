@extends('layouts.app')

@section('title', 'Jasa Export Import Indonesia | Rai Raka Express')

@section('meta_description', 'Rai Raka Express melayani jasa export import, pengiriman internasional, dokumentasi export, dan cargo terpercaya dari Indonesia.')
@section('meta_keywords', 'jasa ekspedisi internasional, jasa kirim barang luar negeri, cargo internasional murah, pengiriman barang ke Jepang, ekspedisi Indonesia Jepang, jasa import export terpercaya, pengiriman door to door internasional, jasa kirim paket cepat luar negeri, cargo udara internasional, jasa pengiriman barang UMKM export, Rai Raka Express, Rai Raka Express cargo, Rai Raka Express Jepang, Rai Raka Express tracking, Rai Raka Express ekspedisi internasional, Rai Raka Express pengiriman luar negeri')

@section('content')
    <div class="container">
        <section class="hero card" aria-label="Tracking paket" style="background:#fff; padding:22px; display:flex; flex-direction:column;">
            <div class="left" style="max-width:100%; order:1; width:100%; display:block;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:14px;flex-wrap:wrap;">
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div style="width:52px;height:52px;border:4px solid var(--brand-blue);border-radius:16px;display:flex;align-items:center;justify-content:center;background:#fff;">
                            <img src="{{ asset('images/logo-compact.png') }}" alt="{{ config('app.name') }} logo" style="height:28px;width:auto;">
                        </div>
                        <div>
                            <h1 class="hero-title" style="margin:0; font-size:28px;">Pelacakan Paket</h1>
                        </div>
                    </div>

                    <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
                        <a href="{{ url('/') }}" class="btn-outline" style="padding:10px 14px;border:2px solid rgba(2,6,23,0.08);border-radius:12px;font-weight:900;">
                            ← Beranda
                        </a>

                        <a href="{{ url('/contact') }}" class="btn-outline" style="border:2px solid rgba(2,6,23,0.08); padding:10px 14px;border-radius:12px;font-weight:900;">
                            Butuh Bantuan?
                        </a>
                    </div>
                </div>

                <div style="margin-top:16px; border:3px solid rgba(2,6,23,0.04); border-radius:16px; padding:14px; background:#fff;">
                    <h2 style="margin:0 0 8px 0; color:var(--brand-dark); font-size:18px; font-weight:900; letter-spacing:-.01em;">
                        Masukkan Nomor Resi
                    </h2>

                    <form method="POST" action="{{ url('/tracking') }}" class="tracking-form" aria-label="Form tracking">
                        @csrf

                        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
                            <div style="flex:1;min-width:260px;">
                                <label for="tracking_number" class="sr-only">Nomor AWB</label>
                                <input
                                    id="tracking_number"
                                    type="text"
                                    name="tracking_number"
                                    placeholder="Contoh: 1234567890"
                                    required
                                    maxlength="12"
                                    pattern="[0-9]{0,12}"
                                    inputmode="numeric"
                                    title="Nomor resi angka saja"
                                    class="input"
                                    style="padding:14px 16px;border-radius:12px;border:2px solid rgba(11,93,167,0.18);font-weight:800;"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                                    onpaste="event.preventDefault(); const t=(event.clipboardData||window.clipboardData).getData('text'); const d=(t||'').replace(/[^0-9]/g,'').slice(0,12); document.getElementById('tracking_number').value=d;"
                                >
                                <div class="site-footer" style="border-top:none;padding:8px 0;margin-top:6px;color:#54617a;">
                                    Tips: hanya angka, tanpa spasi/tanda baca.
                                </div>
                            </div>

                            <button type="submit" class="btn" style="padding:14px 18px;border-radius:12px;border:none;box-shadow:0 10px 30px rgba(11,93,167,0.12);">
                                Cek Status
                            </button>
                        </div>
                    </form>
                </div>

                <div style="margin-top:14px; display:flex; gap:10px; flex-wrap:wrap;"></div>
            </div>

            <div class="right" style="flex-basis:auto; order:2; width:100%; display:block; margin-top:14px;">
                <div class="card" style="width:100%; background:#fff; box-shadow:0 10px 30px rgba(2,6,23,0.06); border:3px solid rgba(11,93,167,0.12);">
                    <div style="padding:16px;">
                        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px; margin-bottom:10px; flex-wrap:wrap;">
                            <h2 style="margin:0;color:var(--brand-dark);font-size:18px;font-weight:900;">Hasil Pelacakan</h2>
                            <div style="display:inline-flex;align-items:center;gap:8px;">
                                <span style="width:10px;height:10px;background:var(--brand-orange);border:2px solid #fff;box-shadow:0 0 0 3px rgba(255,154,28,0.25);border-radius:999px;"></span>
                                <span style="font-weight:900;color:#1b1b18;font-size:14px;">LIVE</span>
                            </div>
                        </div>

                        <div id="tracking-result" aria-live="polite" aria-atomic="true" style="padding-right:12px; box-sizing:border-box;">
                            @php
                                /** @var array<string,mixed> $result */
                                $result = $result ?? [];

                                // DHLService returns either:
                                // 1) success: $response->json() (e.g. ['shipments' => [...]] )
                                // 2) failure: ['raw' => ['json' => ...], 'error' => ...]
                                $dhlJsonRaw = $result['raw']['json'] ?? null;

                                // If raw json not present, try using the whole result as json-like payload
                                if ($dhlJsonRaw === null) {
                                    $dhlJsonRaw = $result;
                                }

                                // Be tolerant: DHL may be decoded as array/object, or come as JSON string
                                if (is_string($dhlJsonRaw)) {
                                    $decoded = json_decode($dhlJsonRaw, true);
                                    $dhlJson = is_array($decoded) ? $decoded : null;
                                } else {
                                    $dhlJson = is_array($dhlJsonRaw)
                                        ? $dhlJsonRaw
                                        : (is_object($dhlJsonRaw) ? (array) $dhlJsonRaw : null);
                                }

                                $dhlShipments = [];

                                if (is_array($dhlJson) && array_key_exists('shipments', $dhlJson)) {
                                    $ship = $dhlJson['shipments'];

                                    if (is_array($ship)) {
                                        $dhlShipments = $ship;
                                    } elseif (is_object($ship)) {
                                        $dhlShipments = (array) $ship;
                                    }
                                }

                                // Extra safety: if $result itself contains shipments (success case)
                                if (empty($dhlShipments) && is_array($result) && array_key_exists('shipments', $result)) {
                                    $ship = $result['shipments'];
                                    if (is_array($ship)) {
                                        $dhlShipments = $ship;
                                    } elseif (is_object($ship)) {
                                        $dhlShipments = (array) $ship;
                                    }
                                }
                            @endphp

                            @if(count($dhlShipments) > 0)
                                @if(isset($result['error']) && $result['error'])
                                    <div role="status" style="background:#fef3c7;border:1px solid rgba(180,35,24,0.22);color:#b42318;padding:12px;border-radius:14px;font-weight:900;margin-bottom:12px;">
                                        <div style="font-size:14px;margin-bottom:8px;">
                                            {{ $result['error'] }}
                                        </div>
                                    </div>
                                @endif

                                <div style="display:flex;flex-direction:column;gap:10px;margin-top:4px;">
                                    @foreach($dhlShipments as $shipment)
                                                @php
                                                    $status = (isset($shipment['status']) && is_array($shipment['status'])) ? $shipment['status'] : [];
                                                    $events = (isset($shipment['events']) && is_array($shipment['events'])) ? $shipment['events'] : [];
                                                    $destination = (isset($shipment['destination']) && is_array($shipment['destination'])) ? $shipment['destination'] : [];
                                                    $origin = (isset($shipment['origin']) && is_array($shipment['origin'])) ? $shipment['origin'] : [];
                                                @endphp

                                                <div style="border:2px solid rgba(180,35,24,0.22);border-radius:14px;padding:12px;background:#fff;">
                                                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap;">
                                                        <div>
                                                            <div style="font-weight:1000;font-size:14px;">
                                                                Air Way Bill: {{ $shipment['id'] ?? '-' }}
                                                            </div>
                                                            <div style="color:#54617a;font-weight:800;font-size:12px;margin-top:4px;">
                                                                {{ $destination['address']['addressLocality'] ?? '-' }} ←
                                                                {{ $origin['address']['addressLocality'] ?? '-' }}
                                                            </div>
                                                        </div>

                                                        <div style="text-align:right;">
                                                            <div style="font-weight:1000;font-size:14px;">
                                                                Status {{ $status['status'] ?? $status['statusCode'] ?? '-' }}
                                                            </div>
                                                            <div style="color:#54617a;font-weight:800;font-size:12px;margin-top:4px;max-width:240px;">
                                                                {{ $status['description'] ?? '-' }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @if(isset($status['timestamp']))
                                                        <div style="margin-top:8px;color:#54617a;font-weight:800;font-size:12px;">
                                                            Updated: {{ $status['timestamp'] }}
                                                        </div>
                                                    @endif

                                                    {{-- Next steps removed per request --}}

                                                    @if(count($events) > 0)
                                                            <div style="margin-top:10px;">
                                                                <div style="font-weight:1000;font-size:13px;margin-bottom:6px;">Last updates</div>

                                                                @php
                                                                    $sortedEvents = is_array($events) ? $events : [];
                                                                    usort($sortedEvents, function ($a, $b) {
                                                                        $ta = $a['timestamp'] ?? '';
                                                                        $tb = $b['timestamp'] ?? '';
                                                                        return strcmp((string)$ta, (string)$tb);
                                                                    });
                                                                    // Tampilkan semua event (earliest -> latest)
                                                                    $eventsToShow = $sortedEvents;
                                                                @endphp

                                                            @php
                                                                // Build groups by date in timezone-less way (ISO date part)
                                                                $groups = [];
                                                                foreach ($eventsToShow as $event) {
                                                                    $ts = $event['timestamp'] ?? '';
                                                                    $dateKey = (is_string($ts) && strlen($ts) >= 10) ? substr($ts, 0, 10) : 'unknown-date';
                                                                    if (!isset($groups[$dateKey])) $groups[$dateKey] = [];
                                                                    $groups[$dateKey][] = $event;
                                                                }

                                                                // Convert dateKey to readable label like "Tuesday, May 12, 2026"
                                                                $formatDateLabel = function (string $dateKey): string {
                                                                    try {
                                                                        $dt = new \DateTime($dateKey.' 00:00:00');
                                                                        return $dt->format('l, F j, Y');
                                                                    } catch (\Throwable $e) {
                                                                        return $dateKey;
                                                                    }
                                                                };

                                                                // Urutkan tanggal dari bawah ke atas: terbaru di atas.
                                                                // $groups terbentuk dari $eventsToShow (earliest -> latest), jadi reverse untuk terbaru -> teratas.
                                                                $groupKeys = array_keys($groups);
                                                                $groupKeys = array_reverse($groupKeys);
                                                            @endphp

                                                            <div style="margin-top:6px;">
                                                                @foreach($groupKeys as $dateKey)
                                                                    <div style="margin:10px 0 6px 0; font-weight:1000; font-size:12px; color:#1b1b18; line-height:1.1;">
                                                                        {{ $formatDateLabel($dateKey) }}
                                                                    </div>

                                                                    <table style="width:100%; max-width:100%; table-layout:fixed; border-collapse:collapse; border:1px solid rgba(2,6,23,0.12); border-radius:12px; overflow:hidden; background:#fff;">
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="text-align:center; padding:6px 4px; font-size:11px; background:rgba(2,6,23,0.03); border-bottom:1px solid rgba(2,6,23,0.12); font-weight:1000; vertical-align:middle; width:7%;">#</th>
                                                                                <th style="text-align:left; padding:6px 8px; font-size:11px; background:rgba(2,6,23,0.03); border-bottom:1px solid rgba(2,6,23,0.12); font-weight:1000; vertical-align:middle; width:55%;">Status</th>
                                                                                <th style="text-align:left; padding:6px 8px; font-size:11px; background:rgba(2,6,23,0.03); border-bottom:1px solid rgba(2,6,23,0.12); font-weight:1000; vertical-align:middle; width:23%;">Lokasi</th>
                                                                                <th style="text-align:left; padding:6px 8px; font-size:11px; background:rgba(2,6,23,0.03); border-bottom:1px solid rgba(2,6,23,0.12); font-weight:1000; vertical-align:middle; white-space:nowrap; width:15%;">Waktu</th>
                                                                            </tr>
                                                                        </thead>

                                                                        <tbody>
                                                                            @php $idx = 1; @endphp
                                                                            @foreach(array_reverse($groups[$dateKey]) as $event)
                                                                                @php
                                                                                    $ts = $event['timestamp'] ?? '';
                                                                                    $loc = $event['location']['address']['addressLocality'] ?? '-';
                                                                                    $desc = $event['description'] ?? '-';

                                                                                    // Extract HH:mm from ISO timestamp
                                                                                    $timeText = $ts;
                                                                                    if (is_string($ts) && strpos($ts, 'T') !== false) {
                                                                                        $timeText = substr($ts, 11, 5);
                                                                                    }
                                                                                @endphp
                                                                                <tr>
                                                                                    <td style="text-align:center; padding:6px 4px; border-bottom:1px solid rgba(2,6,23,0.08); font-weight:1000; color:#1b1b18; font-size:11px; vertical-align:top;">{{ $idx }}</td>
                                                                                    <td style="text-align:left; padding:6px 8px; border-bottom:1px solid rgba(2,6,23,0.08); color:#1b1b18; font-weight:800; font-size:11px; vertical-align:top; word-break:break-word; overflow-wrap:anywhere;">{{ $desc }}</td>
                                                                                    <td style="text-align:left; padding:6px 8px; border-bottom:1px solid rgba(2,6,23,0.08); color:#54617a; font-weight:800; font-size:11px; vertical-align:top; word-break:break-word; overflow-wrap:anywhere;">{{ $loc }}</td>
                                                                                    <td style="text-align:left; padding:6px 8px; border-bottom:1px solid rgba(2,6,23,0.08); color:#54617a; font-weight:800; font-size:11px; vertical-align:top; white-space:nowrap;">{{ $timeText }}</td>
                                                                                </tr>
                                                                                @php $idx++; @endphp
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                @endforeach
                                                            </div>
                                                            </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                </div>
                            @else
                                <div style="background:#fff;border:1px dashed rgba(2,6,23,0.12);padding:14px;border-radius:14px;color:#54617a;font-weight:700;">
                                    Belum ada hasil tracking untuk nomor AWB ini.
                                </div>
                            @endif
                        </div>

                        <div style="margin-top:12px;color:#54617a;font-weight:700; font-size:13px;">
                            Data yang ditampilkan berasal langsung dari sistem kurir.
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
