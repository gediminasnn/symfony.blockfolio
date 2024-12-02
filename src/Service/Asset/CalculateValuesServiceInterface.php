<?php

namespace App\Service\Asset;

use App\Entity\User;

interface CalculateValuesServiceInterface
{
    public function calculateAssetValues(User $user): array;
}
