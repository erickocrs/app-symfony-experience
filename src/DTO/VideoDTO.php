<?php
namespace App\DTO;
use App\Entity\Video;

class VideoDTO implements \JsonSerializable{

    private $user;
    public function __construct(Video $video){
        $this->video = $video;
    }
    
    public function jsonSerialize() {
        return array(
            'id' => $this->video->getId(),                      
            'urlVideo' => $this->video->getUrlVideo(),  
            'urlCustomName' => $this->video->getUrlCustomName(),            
            'urlImagePreview' => $this->video->getUrlImagePreview(),               
            'status' => $this->video->getStatus(),           
        );
    }
}