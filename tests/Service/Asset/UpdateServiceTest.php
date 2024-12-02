<?php

namespace App\Tests\Service\Asset;

use App\Entity\Asset;
use App\Entity\User;
use App\Service\Asset\ShowServiceInterface;
use App\Service\Asset\UpdateService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UpdateServiceTest extends TestCase
{
    private $showServiceMock;
    private $entityManagerMock;
    private $validatorMock;
    private $serializerMock;
    private $updateService;

    protected function setUp(): void
    {
        $this->showServiceMock = $this->createMock(ShowServiceInterface::class);
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $this->validatorMock = $this->createMock(ValidatorInterface::class);
        $this->serializerMock = $this->createMock(SerializerInterface::class);

        $this->updateService = new UpdateService(
            $this->showServiceMock,
            $this->entityManagerMock,
            $this->validatorMock,
            $this->serializerMock
        );
    }

    public function testUpdateAssetSuccess(): void
    {
        $user = new User();
        $asset = new Asset();

        $this->showServiceMock
            ->expects($this->once())
            ->method('getAssetByIdAndUser')
            ->with(1, $user)
            ->willReturn($asset);

        $this->serializerMock
            ->expects($this->once())
            ->method('deserialize')
            ->with('{"label":"Updated Asset"}', Asset::class, 'json', ['object_to_populate' => $asset])
            ->willReturn($asset);

        $violationListMock = $this->createMock(ConstraintViolationListInterface::class);
        $violationListMock->method('count')->willReturn(0);

        $this->validatorMock
            ->expects($this->once())
            ->method('validate')
            ->with($asset)
            ->willReturn($violationListMock);

        $this->entityManagerMock
            ->expects($this->once())
            ->method('flush');

        $result = $this->updateService->updateAsset(1, '{"label":"Updated Asset"}', $user);

        $this->assertSame($asset, $result);
    }
}
