<?php

namespace App\Repository\User;

use App\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    private $pageCount;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
        $this->pageCount = $_ENV['PAGE_COUNT'];
    }

    /**
     * Get user
     * @param $id
     * @return User
     */
    public function get($id): User
    {
        $user = $this->find($id);
        if (!$user)
            throw new NotFoundHttpException('User doesn\'t exist.');
        return $user;
    }

    public function getAll($email, $status, $role, $page = null)
    {
        $qb = $this->createQueryBuilder('u');
        if ($email) {
            $qb->andWhere('u.email LIKE :email')->setParameter('email', "%".$email."%");
        }
        if ($status) {
            $qb->andwhere('u.status = :status')->setParameter('status', $status);
        }
        if ($role) {
            $qb->andwhere('u.roles = :role')->setParameter('role', $role);
        }

        if ($page) {
            $offset = ($page - 1)  * $this->pageCount;
            $qb->setMaxResults($this->pageCount)->setFirstResult($offset);
        }

        return $qb->getQuery()
            ->getResult();
    }

    public function getCountUsers($email, $status, $role)
    {
        $qb = $this->createQueryBuilder('u')->select('COUNT(u)');
        if ($email) {
            $qb->andWhere('u.email LIKE :email')->setParameter('email', "%".$email."%");
        }
        if ($status) {
            $qb->andwhere('u.status = :status')->setParameter('status', $status);
        }
        if ($role) {
            $qb->andwhere('u.roles = :role')->setParameter('role', $role);
        }

        return $qb->getQuery()
            ->getSingleScalarResult();
    }

}