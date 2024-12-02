<?php

namespace App\Controller\Asset;

use App\Service\Asset\CreateServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CreateController extends AbstractController
{
    private CreateServiceInterface $createService;

    public function __construct(CreateServiceInterface $createService)
    {
        $this->createService = $createService;
    }

    #[Route('/api/assets', name: 'asset_create', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        try {
            $user = $this->getUser();
            $jsonData = $request->getContent();
            $asset = $this->createService->createAsset($jsonData, $user);

            return $this->json($asset, Response::HTTP_CREATED);
        } catch (BadRequestHttpException $e) {
            return $this->json(['message' => 'One or more input fields are invalid.'], $e->getStatusCode());
        }
    }
}
