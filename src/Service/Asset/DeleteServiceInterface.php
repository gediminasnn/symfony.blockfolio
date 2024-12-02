<?php

namespace App\Service\Asset;

use App\Entity\User;

interface DeleteServiceInterface
{
    public function deleteAsset(int $id, User $user): void;
}
