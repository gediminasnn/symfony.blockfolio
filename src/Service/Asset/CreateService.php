<?php

namespace App\Service\Asset;

use App\Entity\Asset;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateService implements CreateServiceInterface
{
    private EntityManagerInterface $em;
    private ValidatorInterface $validator;
    private SerializerInterface $serializer;

    public function __construct(
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
    ) {
        $this->em = $em;
        $this->validator = $validator;
        $this->serializer = $serializer;
    }

    public function createAsset(string $jsonData, User $user): Asset
    {
        $asset = $this->serializer->deserialize($jsonData, Asset::class, 'json');
        $asset->setUser($user);

        $errors = $this->validator->validate($asset);
        if (count($errors) > 0) {
            throw new BadRequestHttpException((string) $errors);
        }

        $this->em->persist($asset);
        $this->em->flush();

        return $asset;
    }
}
