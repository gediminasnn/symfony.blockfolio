<?php

namespace App\Service\Asset;

use App\Entity\Asset;
use App\Entity\User;
use App\Repository\AssetRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShowService implements ShowServiceInterface
{
    private AssetRepository $assetRepository;

    public function __construct(AssetRepository $assetRepository)
    {
        $this->assetRepository = $assetRepository;
    }

    public function getAssetByIdAndUser(int $id, User $user): Asset
    {
        $asset = $this->assetRepository->findOneByIdAndUser($id, $user);

        if (!$asset) {
            throw new NotFoundHttpException("Asset with ID {$id} not found.");
        }

        return $asset;
    }
}
