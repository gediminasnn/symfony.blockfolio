<?php

namespace App\Tests\Service\Asset;

use App\Entity\Asset;
use App\Entity\User;
use App\Repository\AssetRepository;
use App\Service\Asset\ListService;
use PHPUnit\Framework\TestCase;

class ListServiceTest extends TestCase
{
    private $assetRepositoryMock;
    private $listService;

    protected function setUp(): void
    {
        $this->assetRepositoryMock = $this->createMock(AssetRepository::class);
        $this->listService = new ListService($this->assetRepositoryMock);
    }

    public function testGetAssetsByUserSuccess(): void
    {
        $user = new User();
        $assets = [new Asset(), new Asset()];

        $this->assetRepositoryMock
            ->expects($this->once())
            ->method('findByUser')
            ->with($user)
            ->willReturn($assets);

        $result = $this->listService->getAssetsByUser($user);

        $this->assertSame($assets, $result);
    }

    public function testGetAssetsByUserNotFound(): void
    {
        $user = new User();

        $this->assetRepositoryMock
            ->expects($this->once())
            ->method('findByUser')
            ->with($user)
            ->willReturn([]);

        $result = $this->listService->getAssetsByUser($user);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }
}
