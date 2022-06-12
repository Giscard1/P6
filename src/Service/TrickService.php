<?php


namespace App\Service;

use App\Entity\Category;
use App\Entity\Tricks;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class TrickService extends BaseService
{
    public function createNewTrick(\App\Entity\Tricks $trick)
    {
        $this->persist($trick);
        $this->flush();
        //envoyer le trick a trick picture service pour créer une entréé dans picture
    }

    public function deletePictureFromTrick(Tricks $trick, $picture){
        $trick->removePicture($picture);
        $this->flush();

    }

    public function deleteVideoFromTrick(Tricks $trick, $video){
        $trick->removeVideo($video);
        $this->flush();
    }
    public function findTrickById($id){

        $trick = $this->trickRepo->find($id);
        return $trick;
    }
    

    public function UploadPicture($trick, $uploadFile){
        $originalFilename = pathinfo($uploadFile->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$uploadFile->guessExtension();

        // Move the file to the directory where brochures are stored
        try {
            $uploadFile->move(
                $this->getParameter('trick_picture_directory'),
                $newFilename
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        // updates the 'brochureFilename' property to store the PDF file name
        // instead of its contents
        $trick->setName($newFilename);
        $trick->setDescription($newFilename);
    }

    public function getAllMediaOfTrick($idTrick){
        $videos = $this->videoRepo->findby(['tricks' => $idTrick]);
        $pictures = $this->pictureRepo->findby(['tricks' => $idTrick]);
        $medias = array_merge((array)$videos, $pictures);
        return $medias;
    }

    public function getThePicture($mediaId){
        $picture = $this->pictureRepo->find($mediaId);
        return $picture;
    }

    public function getTheVideo($mediaId){
        $video = $this->videoRepo->find($mediaId);
        return $video;
    }

}
