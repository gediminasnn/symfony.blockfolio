<?php

namespace App\Service\Asset;

use App\Entity\Asset;
use App\Entity\User;

interface ShowServiceInterface
{
    public function getAssetByIdAndUser(int $id, User $user): Asset;
}
