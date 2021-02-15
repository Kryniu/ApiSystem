<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByEmail(string $email, int $notCheckId = null): ?User
    {
        $query = $this->createQueryBuilder('u')
            ->setMaxResults(1)
            ->andWhere('u.email = :email')
            ->setParameter('email', $email);
        if (null !== $notCheckId) {
            $query->andWhere('u.id != :id')
                ->setParameter('id', $notCheckId);
        }

        return $query->getQuery()
            ->getOneOrNullResult();
    }
}
