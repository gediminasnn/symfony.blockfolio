<?php

namespace App\Controller\Asset;

use App\Service\Asset\UpdateServiceInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class UpdateController extends AbstractController
{
    private UpdateServiceInterface $updateService;
    private LoggerInterface $logger;

    public function __construct(UpdateServiceInterface $updateService, LoggerInterface $logger)
    {
        $this->updateService = $updateService;
        $this->logger = $logger;
    }

    #[Route('/api/assets/{id}', name: 'asset_update', methods: ['PUT'])]
    public function __invoke(Request $request, int $id): Response
    {
        try {
            $user = $this->getUser();
            $jsonData = $request->getContent();
            $asset = $this->updateService->updateAsset($id, $jsonData, $user);

            return $this->json($asset);
        } catch (BadRequestHttpException $e) {
            return $this->json(['message' => 'One or more input fields are invalid.'], $e->getStatusCode());
        } catch (NotFoundHttpException $e) {
            return $this->json(['message' => 'Asset cannot be found.'], $e->getStatusCode());
        }
    }
}
