<?php

namespace App\Service\Asset;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class DeleteService implements DeleteServiceInterface
{
    private ShowServiceInterface $showService;
    private EntityManagerInterface $em;

    public function __construct(ShowServiceInterface $showService, EntityManagerInterface $em)
    {
        $this->showService = $showService;
        $this->em = $em;
    }

    public function deleteAsset(int $id, User $user): void
    {
        $asset = $this->showService->getAssetByIdAndUser($id, $user);

        $this->em->remove($asset);
        $this->em->flush();
    }
}
