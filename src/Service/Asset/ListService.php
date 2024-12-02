<?php

namespace App\Service\Asset;

use App\Entity\User;
use App\Repository\AssetRepository;

class ListService implements ListServiceInterface
{
    private AssetRepository $assetRepository;

    public function __construct(AssetRepository $assetRepository)
    {
        $this->assetRepository = $assetRepository;
    }

    public function getAssetsByUser(User $user): array
    {
        $assets = $this->assetRepository->findByUser($user);

        return $assets;
    }
}
