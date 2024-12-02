<?php

namespace App\Controller\Asset;

use App\Service\Asset\CalculateValuesServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalculateValuesController extends AbstractController
{
    private CalculateValuesServiceInterface $calculateValuesService;

    public function __construct(CalculateValuesServiceInterface $calculateValuesService)
    {
        $this->calculateValuesService = $calculateValuesService;
    }

    #[Route('/api/assets/values', name: 'asset_values', methods: ['GET'])]
    public function __invoke(): Response
    {
        $user = $this->getUser();
        $values = $this->calculateValuesService->calculateAssetValues($user);

        return $this->json($values);
    }
}
