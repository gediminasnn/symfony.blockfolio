<?php

namespace App\Service\Asset;

use App\Entity\User;

interface ListServiceInterface
{
    public function getAssetsByUser(User $user): array;
}
