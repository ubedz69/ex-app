<?php

namespace App\Services\Fedex;

final class FedexTransformer
{
    /**
     * Normalizes FedEx Track API response into the structure expected by
     * resources/views/tracking.blade.php:
     *
     * [
     *   'shipments' => [
     *     [
     *       'id' => string|null,
     *       'destination' => ['address' => ['addressLocality' => string|null]],
     *       'origin' => ['address' => ['addressLocality' => string|null]],
     *       'status' => [
     *         'status' => string|null,
     *         'statusCode' => string|null,
     *         'description' => string|null,
     *         'timestamp' => string|null,
     *       ],
     *       'events' => [
     *         [
     *           'timestamp' => string|null,
     *           'location' => ['address' => ['addressLocality' => string|null]],
     *           'description' => string|null,
     *         ],
     *       ],
     *       'shipmentPeriod' => [
     *         'start' => string|null,
     *         'end' => string|null,
     *       ],
     *     ],
     *   ],
     * ]
     *
     * @param  array<string,mixed>  $fedexJson
     * @return array<string,mixed>
     */
    public function transformTrack(array $fedexJson): array
    {
        $shipments = [];

        $output = $fedexJson['output'] ?? null;
        if (! is_array($output)) {
            return ['shipments' => []];
        }

        // FedEx sample response uses:
        // output.completeTrackResults[] -> trackResults[]
        $completeTrackResults = $output['completeTrackResults'] ?? null;
        if (is_array($completeTrackResults)) {
            foreach ($completeTrackResults as $complete) {
                if (! is_array($complete)) {
                    continue;
                }

                $trackResults = $complete['trackResults'] ?? null;
                if (! is_array($trackResults)) {
                    continue;
                }

                foreach ($trackResults as $trackResult) {
                    if (! is_array($trackResult)) {
                        continue;
                    }

                    $shipments[] = $this->transformTrackResult($trackResult);
                }
            }

            return ['shipments' => $shipments];
        }

        // Tolerant fallback for older/alternate response shapes
        $trackResults = $output['trackResults'] ?? null;
        if (is_array($trackResults)) {
            foreach ($trackResults as $trackResult) {
                if (! is_array($trackResult)) {
                    continue;
                }
                $shipments[] = $this->transformTrackResult($trackResult);
            }
        }

        return ['shipments' => $shipments];
    }

    /**
     * @param  array<string,mixed>  $trackResult
     * @return array<string,mixed>
     */
    private function transformTrackResult(array $trackResult): array
    {
        $trackingNumber = $trackResult['trackingNumberInfo']['trackingNumber'] ?? $trackResult['trackingNumber'] ?? null;

        $latestStatusDetail = $trackResult['latestStatusDetail'] ?? [];
        if (! is_array($latestStatusDetail)) {
            $latestStatusDetail = [];
        }

        $scanEvents = $trackResult['scanEvents'] ?? [];
        if (! is_array($scanEvents)) {
            $scanEvents = [];
        }

        $destination = $this->extractAddressTreeFromFedexLocation(
            $trackResult['lastUpdatedDestinationAddress'] ?? null
        );

        // origin from originLocation/originLocation.locationContactAndAddress.address (if available)
        $origin = $this->extractAddressTreeFromFedexLocation(
            $trackResult['originLocation']['locationContactAndAddress']['address'] ?? null
        );

        // If origin still null, try a couple alternatives used by FedEx
        if (($origin['address']['addressLocality'] ?? null) === null) {
            $origin = $this->extractAddressTreeFromFedexLocation(
                $trackResult['originLocation']['locationContactAndAddress']['address'] ?? null
            );
        }

        $status = $this->transformLatestStatusDetail($latestStatusDetail);

        $events = [];
        foreach ($scanEvents as $event) {
            if (! is_array($event)) {
                continue;
            }
            $events[] = $this->transformScanEvent($event);
        }

        $shipmentPeriod = $this->extractShipmentPeriod($trackResult, $events);

        // Ensure timeline order: earliest -> latest
        usort($events, function (array $a, array $b): int {
            $ta = $a['timestamp'] ?? '';
            $tb = $b['timestamp'] ?? '';

            if (! is_string($ta)) {
                $ta = '';
            }
            if (! is_string($tb)) {
                $tb = '';
            }

            return strcmp($ta, $tb);
        });

        if (($status['timestamp'] ?? null) === null) {
            $status['timestamp'] = $shipmentPeriod['end'] ?? $shipmentPeriod['start'];
        }

        return [
            'id' => is_string($trackingNumber) ? $trackingNumber : (isset($trackingNumber) ? (string) $trackingNumber : null),
            'destination' => $destination,
            'origin' => $origin,
            'status' => $status,
            'events' => $events,
            'shipmentPeriod' => $shipmentPeriod,
        ];
    }

    /**
     * FedEx sample:
     * latestStatusDetail:
     * - statusByLocale: "Picked up"
     * - description: "Picked up"
     * - code: "PU"
     * - scanLocation: { city, stateOrProvinceCode, ... }
     * - date/timestamp is not always present; we keep null if missing.
     *
     * @param  array<string,mixed>  $latestStatusDetail
     * @return array<string,mixed>
     */
    private function transformLatestStatusDetail(array $latestStatusDetail): array
    {
        $statusText = $latestStatusDetail['statusByLocale'] ?? $latestStatusDetail['status'] ?? null;
        $description = $latestStatusDetail['description'] ?? $latestStatusDetail['statusDescription'] ?? null;

        $statusCode = $latestStatusDetail['code'] ?? $latestStatusDetail['derivedCode'] ?? $latestStatusDetail['statusCode'] ?? null;

        $timestamp = $latestStatusDetail['timestamp'] ?? $latestStatusDetail['date'] ?? $latestStatusDetail['time'] ?? null;

        return [
            'status' => is_string($statusText) ? $statusText : null,
            'statusCode' => is_string($statusCode) ? $statusCode : null,
            'description' => is_string($description) ? $description : null,
            'timestamp' => is_string($timestamp) ? $timestamp : (isset($timestamp) ? (string) $timestamp : null),
        ];
    }

    /**
     * FedEx sample scanEvents:
     * - date: "2018-02-02T12:01:00-07:00"
     * - eventDescription: "Picked Up"
     * - scanLocation:
     *    - city: "SEATTLE"
     *
     * @param  array<string,mixed>  $event
     * @return array<string,mixed>
     */
    private function transformScanEvent(array $event): array
    {
        // FedEx sample:
        // date: "2018-02-02T12:01:00-07:00"
        // eventDescription: "Picked Up"
        // scanLocation.city: "SEATTLE"
        $timestamp = $event['date'] ?? $event['timestamp'] ?? null;

        $description = $event['eventDescription']
            ?? $event['description']
            ?? $event['exceptionDescription']
            ?? $event['derivedStatus']
            ?? null;

        $scanLocation = $event['scanLocation'] ?? null;
        $addressLocality = null;

        if (is_array($scanLocation)) {
            if (isset($scanLocation['city']) && is_string($scanLocation['city'])) {
                $addressLocality = $scanLocation['city'];
            } elseif (isset($scanLocation['addressLocality']) && is_string($scanLocation['addressLocality'])) {
                $addressLocality = $scanLocation['addressLocality'];
            } elseif (isset($scanLocation['address']) && is_array($scanLocation['address'])) {
                $addr = $scanLocation['address'];
                $addressLocality = $addr['addressLocality'] ?? $addr['city'] ?? null;
            }
        }

        return [
            'timestamp' => is_string($timestamp) ? $timestamp : (isset($timestamp) ? (string) $timestamp : null),
            'location' => [
                'address' => [
                    'addressLocality' => is_string($addressLocality) ? $addressLocality : null,
                ],
            ],
            'description' => is_string($description) ? $description : null,
        ];
    }

    /**
     * FedEx sample address node:
     * lastUpdatedDestinationAddress.address:
     * - city
     * - stateOrProvinceCode
     * etc
     *
     * @param  array<string,mixed>|null  $fedexAddress
     * @return array<string,mixed>
     */
    private function extractAddressTreeFromFedexLocation(mixed $fedexAddress): array
    {
        if (! is_array($fedexAddress)) {
            return ['address' => ['addressLocality' => null]];
        }

        if (isset($fedexAddress['city']) && is_string($fedexAddress['city'])) {
            return ['address' => ['addressLocality' => $fedexAddress['city']]];
        }

        if (isset($fedexAddress['addressLocality']) && is_string($fedexAddress['addressLocality'])) {
            return ['address' => ['addressLocality' => $fedexAddress['addressLocality']]];
        }

        // Sometimes address could be nested or provide locality-like fields
        return ['address' => ['addressLocality' => null]];
    }

    /**
     * @param  array<string,mixed>  $trackResult
     * @param  array<int,array<string,mixed>>  $events
     * @return array{start: string|null, end: string|null}
     */
    private function extractShipmentPeriod(array $trackResult, array $events): array
    {
        $startCandidates = [];
        $endCandidates = [];

        $dateAndTimes = $trackResult['dateAndTimes'] ?? [];
        if (is_array($dateAndTimes)) {
            foreach ($dateAndTimes as $item) {
                if (! is_array($item)) {
                    continue;
                }

                $dateTime = $this->toNullableString($item['dateTime'] ?? null);
                if ($dateTime === null) {
                    continue;
                }

                $type = strtoupper((string) ($item['type'] ?? ''));

                if (str_contains($type, 'DELIVERY')) {
                    $endCandidates[] = $dateTime;
                } elseif (str_contains($type, 'PICKUP') || str_contains($type, 'SHIP') || str_contains($type, 'TENDER')) {
                    $startCandidates[] = $dateTime;
                } else {
                    $startCandidates[] = $dateTime;
                }
            }
        }

        $estimatedWindow = $trackResult['estimatedDeliveryTimeWindow']['window'] ?? null;
        if (is_array($estimatedWindow)) {
            $estimatedBegins = $this->toNullableString($estimatedWindow['begins'] ?? null);
            $estimatedEnds = $this->toNullableString($estimatedWindow['ends'] ?? null);

            if ($estimatedBegins !== null) {
                $startCandidates[] = $estimatedBegins;
            }
            if ($estimatedEnds !== null) {
                $endCandidates[] = $estimatedEnds;
            }
        }

        $standardWindow = $trackResult['standardTransitTimeWindow']['window'] ?? null;
        if (is_array($standardWindow)) {
            $standardBegins = $this->toNullableString($standardWindow['begins'] ?? null);
            $standardEnds = $this->toNullableString($standardWindow['ends'] ?? null);

            if ($standardBegins !== null) {
                $startCandidates[] = $standardBegins;
            }
            if ($standardEnds !== null) {
                $endCandidates[] = $standardEnds;
            }
        }

        $eventTimestamps = array_values(array_filter(array_map(
            fn (array $event): ?string => $this->toNullableString($event['timestamp'] ?? null),
            $events
        )));

        if (count($eventTimestamps) > 0) {
            sort($eventTimestamps);

            $startCandidates[] = $eventTimestamps[0];
            $endCandidates[] = $eventTimestamps[count($eventTimestamps) - 1];
        }

        return [
            'start' => $this->pickEarliest($startCandidates),
            'end' => $this->pickLatest($endCandidates),
        ];
    }

    /**
     * @param  array<int,mixed>  $values
     */
    private function pickEarliest(array $values): ?string
    {
        $normalized = array_values(array_filter(array_map(
            fn (mixed $value): ?string => $this->toNullableString($value),
            $values
        )));

        if (count($normalized) === 0) {
            return null;
        }

        sort($normalized);

        return $normalized[0];
    }

    /**
     * @param  array<int,mixed>  $values
     */
    private function pickLatest(array $values): ?string
    {
        $normalized = array_values(array_filter(array_map(
            fn (mixed $value): ?string => $this->toNullableString($value),
            $values
        )));

        if (count($normalized) === 0) {
            return null;
        }

        sort($normalized);

        return $normalized[count($normalized) - 1];
    }

    private function toNullableString(mixed $value): ?string
    {
        if (is_string($value) && trim($value) !== '') {
            return $value;
        }

        if (is_numeric($value)) {
            return (string) $value;
        }

        return null;
    }
}
