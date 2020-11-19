<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\VideoRepository;

/**
 * @ORM\Entity(repositoryClass=VideoRepository::class)
 */
class Video
{
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string")
     */
    private $urlCustomName;

    /**
     * @ORM\Column(type="string")
     */
    private $urlVideo;
    
    /**
     * @ORM\Column(type="string")
     */
    private $urlImagePreview;

    
    /**
     * @ORM\Column(type="integer")
     */ 
    private $status;

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of urlVideo
     */ 
    public function getUrlVideo()
    {
        return $this->urlVideo;
    }

    /**
     * Set the value of urlVideo
     *
     * @return  self
     */ 
    public function setUrlVideo($urlVideo)
    {
        $this->urlVideo = $urlVideo;

        return $this;
    }

    /**
     * Get the value of urlImagePreview
     */ 
    public function getUrlImagePreview()
    {
        return $this->urlImagePreview;
    }

    /**
     * Set the value of urlImagePreview
     *
     * @return  self
     */ 
    public function setUrlImagePreview($urlImagePreview)
    {
        $this->urlImagePreview = $urlImagePreview;

        return $this;
    }

    /**
     * Get the value of status
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */ 
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of urlCustomName
     */ 
    public function getUrlCustomName()
    {
        return $this->urlCustomName;
    }

    /**
     * Set the value of urlCustomName
     *
     * @return  self
     */ 
    public function setUrlCustomName($urlCustomName)
    {
        $this->urlCustomName = $urlCustomName;

        return $this;
    }
}