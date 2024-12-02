<?php

namespace App\Controller\Asset;

use App\Service\Asset\ShowServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ShowController extends AbstractController
{
    private ShowServiceInterface $showService;

    public function __construct(ShowServiceInterface $showService)
    {
        $this->showService = $showService;
    }

    #[Route('/api/assets/{id}', name: 'asset_show', methods: ['GET'])]
    public function __invoke(int $id): Response
    {
        try {
            $user = $this->getUser();
            $asset = $this->showService->getAssetByIdAndUser($id, $user);

            return $this->json($asset);
        } catch (NotFoundHttpException $e) {
            return $this->json(['message' => 'Asset cannot be found.'], $e->getStatusCode());
        }
    }
}
