<?php

namespace App\Services;

use App\Services\Fedex\FedexService as InternalFedexService;
use App\Services\Fedex\FedexTransformer;

class FedexService
{
    /**
     * Backward-compatible wrapper so older imports (App\Services\FedexService)
     * keep returning the UI-friendly normalized payload used by tracking.blade.php.
     *
     * @return array<string,mixed>
     */
    public function track(string $trackingNumber): array
    {
        $rawResponse = $this->trackRaw($trackingNumber);

        if (isset($rawResponse['error'])) {
            return $rawResponse;
        }

        return (new FedexTransformer)->transformTrack($rawResponse);
    }

    /**
     * Returns the FedEx-like raw response shape:
     * transactionId, customerTransactionId, output.completeTrackResults.
     *
     * @return array<string,mixed>
     */
    public function trackRaw(string $trackingNumber): array
    {
        return (new InternalFedexService)->track($trackingNumber);
    }
}
