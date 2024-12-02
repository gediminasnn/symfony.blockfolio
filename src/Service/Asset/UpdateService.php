<?php

namespace App\Service\Asset;

use App\Entity\Asset;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UpdateService implements UpdateServiceInterface
{
    private ShowServiceInterface $showService;
    private EntityManagerInterface $em;
    private ValidatorInterface $validator;
    private SerializerInterface $serializer;

    public function __construct(
        ShowServiceInterface $showService,
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
    ) {
        $this->showService = $showService;
        $this->em = $em;
        $this->validator = $validator;
        $this->serializer = $serializer;
    }

    public function updateAsset(int $id, string $jsonData, User $user): Asset
    {
        $asset = $this->showService->getAssetByIdAndUser($id, $user);

        $this->serializer->deserialize($jsonData, Asset::class, 'json', [
            'object_to_populate' => $asset,
        ]);

        $errors = $this->validator->validate($asset);
        if (count($errors) > 0) {
            throw new BadRequestHttpException((string) $errors);
        }

        $this->em->flush();

        return $asset;
    }
}
