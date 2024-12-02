<?php

namespace App\Service\Asset;

use App\Entity\Asset;
use App\Entity\User;

interface UpdateServiceInterface
{
    public function updateAsset(int $id, string $jsonData, User $user): Asset;
}
