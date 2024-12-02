<?php

namespace App\Service\Asset;

use App\Entity\Asset;
use App\Entity\User;

interface CreateServiceInterface
{
    public function createAsset(string $jsonData, User $user): Asset;
}
