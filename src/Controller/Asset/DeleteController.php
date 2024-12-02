<?php

namespace App\Controller\Asset;

use App\Service\Asset\DeleteServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class DeleteController extends AbstractController
{
    private DeleteServiceInterface $deleteService;

    public function __construct(DeleteServiceInterface $deleteService)
    {
        $this->deleteService = $deleteService;
    }

    #[Route('/api/assets/{id}', name: 'asset_delete', methods: ['DELETE'])]
    public function __invoke(int $id): Response
    {
        try {
            $user = $this->getUser();
            $this->deleteService->deleteAsset($id, $user);

            return $this->json(null, Response::HTTP_NO_CONTENT);
        } catch (NotFoundHttpException $e) {
            return $this->json(['message' => 'Asset cannot be found.'], $e->getStatusCode());
        }
    }
}
