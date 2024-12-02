<?php

namespace App\Tests\Service\Asset;

use App\Entity\Asset;
use App\Entity\User;
use App\Service\Asset\DeleteService;
use App\Service\Asset\ShowServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class DeleteServiceTest extends TestCase
{
    private $showServiceMock;
    private $entityManagerMock;
    private $deleteService;

    protected function setUp(): void
    {
        $this->showServiceMock = $this->createMock(ShowServiceInterface::class);
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);

        $this->deleteService = new DeleteService($this->showServiceMock, $this->entityManagerMock);
    }

    public function testDeleteAssetSuccess(): void
    {
        $user = new User();
        $asset = new Asset();

        $this->showServiceMock
            ->expects($this->once())
            ->method('getAssetByIdAndUser')
            ->with(1, $user)
            ->willReturn($asset);

        $this->entityManagerMock
            ->expects($this->once())
            ->method('remove')
            ->with($asset);

        $this->entityManagerMock
            ->expects($this->once())
            ->method('flush');

        $this->deleteService->deleteAsset(1, $user);
    }
}
