<?php

namespace App\Repository;

use App\Entity\User;
use App\DTO\UserDTO;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{

    private $passwordEncoder;
    public function __construct(ManagerRegistry $registry, UserPasswordEncoderInterface $encoder)
    {
        parent::__construct($registry, User::class);
        $this->passwordEncoder = $encoder;
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function encodePassword($user){
        $oldPassword =  $user->getPassword();
        $encodedPassword = $this->passwordEncoder->encodePassword($user, $oldPassword);
        $user->setPassword($encodedPassword);
    }
    
    public function passwordIsValid($user, $password){
        return $this->passwordEncoder->isPasswordValid($user, $password);
    }
    
    public function findOneByUsername($pUsername): ?User
    {
        $userFounded = $this->findOneBy([
            'username' => $pUsername,
        ]);

        return $userFounded;
    }

    public function auth($user)
    {
        $userFounded = $this->findOneBy([
            'username' => $user->getUsername()
        ]);

        if( $userFounded
            && $this->passwordIsValid($userFounded, $user->getPassword())) {

            return $userFounded;
        }
        else {
            
            return false;
        }
    }
    
    public function save(User $user)
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function usersListDTO($users) {
        $usersDTO = [];
        foreach($users as $user){
            array_push($usersDTO, new UserDTO($user));
        }
        return $usersDTO;
    }
    

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
