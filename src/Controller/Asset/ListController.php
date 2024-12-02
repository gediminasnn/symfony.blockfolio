<?php

namespace App\Controller\Asset;

use App\Service\Asset\ListServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends AbstractController
{
    private ListServiceInterface $listService;

    public function __construct(ListServiceInterface $listService)
    {
        $this->listService = $listService;
    }

    #[Route('/api/assets', name: 'asset_list', methods: ['GET'])]
    public function __invoke(): Response
    {
        $user = $this->getUser();
        $assets = $this->listService->getAssetsByUser($user);

        return $this->json($assets);
    }
}
