<?php


namespace App\Service;


use App\Entity\Pictures;

class PictureService extends BaseService
{
    public function findPictureById($id){
        $picture = $this->pictureRepo->findBy(['id' => $id]);
        return $picture;
    }

    public function newPictureObject($formData,$newFilename){
        $picture = new Pictures();
        $picture->setName($formData->getName());
        $picture->setDescription($formData->getDescription());
        $picture->setImageFile($newFilename);
        $picture->setTricks($formData->getId());
        $picture->setCreationDate(new \DateTime());
    }
}
