<?php

namespace App\Repository;

use App\Entity\Asset;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Asset>
 */
class AssetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Asset::class);
    }

    /**
     * Add a new Asset to the database.
     *
     * @param bool $flush Whether to flush the changes immediately
     */
    public function add(Asset $asset, bool $flush = false): void
    {
        $this->_em->persist($asset);

        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * Remove an Asset from the database.
     *
     * @param bool $flush Whether to flush the changes immediately
     */
    public function remove(Asset $asset, bool $flush = false): void
    {
        $this->_em->remove($asset);

        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * Find all Assets belonging to a specific User.
     *
     * @return Asset[] An array of Asset entities
     */
    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find an Asset by its ID and User.
     *
     * @return Asset|null The Asset entity or null if not found
     */
    public function findOneByIdAndUser(int $id, User $user): ?Asset
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.id = :id')
            ->andWhere('a.user = :user')
            ->setParameter('id', $id)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
