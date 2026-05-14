<?php

namespace App\Services;

use App\Services\Fedex\FedexService as InternalFedexService;

class FedexService
{
    /**
     * Backward-compatible wrapper so older imports (App\Services\FedexService)
     * use the normalized implementation under App\Services\Fedex\FedexService.
     *
     * @param string $trackingNumber
     * @return array<string,mixed>
     */
    public function track($trackingNumber): array
    {
        return (new InternalFedexService())->track($trackingNumber);
    }
}
