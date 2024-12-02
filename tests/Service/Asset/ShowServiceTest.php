<?php

namespace App\Tests\Service\Asset;

use App\Entity\Asset;
use App\Entity\User;
use App\Repository\AssetRepository;
use App\Service\Asset\ShowService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShowServiceTest extends TestCase
{
    private $assetRepositoryMock;
    private $showService;

    protected function setUp(): void
    {
        $this->assetRepositoryMock = $this->createMock(AssetRepository::class);
        $this->showService = new ShowService($this->assetRepositoryMock);
    }

    public function testGetAssetByIdAndUserSuccess(): void
    {
        $user = new User();
        $asset = new Asset();

        $this->assetRepositoryMock
            ->expects($this->once())
            ->method('findOneByIdAndUser')
            ->with(1, $user)
            ->willReturn($asset);

        $result = $this->showService->getAssetByIdAndUser(1, $user);

        $this->assertSame($asset, $result);
    }

    public function testGetAssetByIdAndUserNotFound(): void
    {
        $user = new User();

        $this->assetRepositoryMock
            ->expects($this->once())
            ->method('findOneByIdAndUser')
            ->with(1, $user)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->showService->getAssetByIdAndUser(1, $user);
    }
}
