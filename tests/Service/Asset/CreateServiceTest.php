<?php

namespace App\Tests\Service\Asset;

use App\Entity\Asset;
use App\Entity\User;
use App\Service\Asset\CreateService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateServiceTest extends TestCase
{
    private $entityManagerMock;
    private $validatorMock;
    private $serializerMock;
    private $createService;

    protected function setUp(): void
    {
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $this->validatorMock = $this->createMock(ValidatorInterface::class);
        $this->serializerMock = $this->createMock(SerializerInterface::class);

        $this->createService = new CreateService(
            $this->entityManagerMock,
            $this->validatorMock,
            $this->serializerMock
        );
    }

    public function testCreateAssetSuccess(): void
    {
        $user = new User();
        $asset = new Asset();

        $this->serializerMock
            ->expects($this->once())
            ->method('deserialize')
            ->willReturn($asset);

        $violationListMock = $this->createMock(ConstraintViolationListInterface::class);
        $violationListMock->method('count')->willReturn(0);

        $this->validatorMock
            ->expects($this->once())
            ->method('validate')
            ->willReturn($violationListMock);

        $this->entityManagerMock
            ->expects($this->once())
            ->method('persist')
            ->with($asset);

        $this->entityManagerMock
            ->expects($this->once())
            ->method('flush');

        $result = $this->createService->createAsset('{"label":"binance","value":0.123,"currency":"bitcoin"}', $user);

        $this->assertSame($asset, $result);
        $this->assertSame($user, $asset->getUser());
    }
}
