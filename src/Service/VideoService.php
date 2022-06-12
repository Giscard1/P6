<?php


namespace App\Service;

use App\Entity\Category;
use App\Entity\Tricks;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class VideoService extends BaseService
{

    public function findVideoById($id){
        $video = $this->videoRepo->find($id);
        return $video;
    }

    public function videoUrlVerification($urlUploaded)
    {
        $err = 0;

        //1er vérification -> vérifier si le mot youtube y ai compris
        $youtube = "youtube";
        // Teste si la chaîne contient le mot
        if(strpos($urlUploaded, $youtube) !== false){
            //2eme verification est ce que la chaine contien le mot Embed
            $embed = "embed";
            // Teste si la chaîne contient le mot
            if(strpos($urlUploaded, $embed) !== false){
                return $urlUploaded;
            } else{
                $urlId = explode('=',$urlUploaded)[1];
                $urlFirstPart = explode('.com/', $urlUploaded)[0];
                $urlEmbeded = $urlFirstPart.'.com/'.'embed'.'/'.$urlId;
                return $urlEmbeded;
            }
        } else{
            return $err;
        }
    }

}
