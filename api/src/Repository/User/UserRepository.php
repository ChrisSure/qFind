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
    /**
     * @var mixed
     */
    private $pageCount;

    /**
     * UserRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
        $this->pageCount = $_ENV['PAGE_COUNT'];
    }

    /**
     * Get user
     *
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

    /**
     * Get user by email
     *
     * @param $email
     * @return User
     */
    public function getByEmail($email): User
    {
        $user = $this->findOneBy([
            'email' => $email
        ]);
        if (!$user)
            throw new NotFoundHttpException('User doesn\'t exist.');
        return $user;
    }

    /**
     * Get all users
     *
     * @param $email
     * @param $status
     * @param $role
     * @param null $page
     * @return array
     */
    public function getAll($email, $status, $role, $page = null): array
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

        return $qb->getQuery()->getResult();
    }

    /**
     * Get count users
     *
     * @param $email
     * @param $status
     * @param $role
     * @return string
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getCountUsers($email, $status, $role): string
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

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Save user
     *
     * @param User $user
     * @return void
     */
    public function save(User $user): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($user);
        $entityManager->flush();
    }

    /**
     * Delete user
     *
     * @param User $user
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(User $user): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($user);
        $entityManager->flush();
    }


}