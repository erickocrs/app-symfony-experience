<?php

namespace App\Repository;

use App\Entity\Video;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Video::class);
    }

    public function validateWithDB($video){
        if($this->findOneByUrlName($video)){
            return "url custom name alredy exist's";
        }

        return false;
    }

    public function findOneByUrlName($video): ?Video
    {
        $userFounded = $this->findOneBy([
            'urlCustomName' => $video->getUrlCustomName(),
        ]);

        return $userFounded;
    }
    
    public function save(Video $video)
    {
        $this->_em->persist($video);
        $this->_em->flush();
    }
}