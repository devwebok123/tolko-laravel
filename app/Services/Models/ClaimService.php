<?php


namespace App\Services\Models;

use App\Helpers\PhoneHelper;
use App\Models\Claim;

class ClaimService
{

    /**
     * @param string $phone
     * @param null|string $referer
     * @return Claim
     */
    public function store(string $phone, ?string $referer): Claim
    {
        return Claim::create([
            'phone' => PhoneHelper::preparePhone($phone),
            'referer' => $referer,
        ]);
    }
}
