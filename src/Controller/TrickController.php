<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\Tricks;
use App\Form\CommentType;
use App\Form\PictureType;
use App\Form\TrickType;
use App\Form\UpdateTrickPictureType;
use App\Form\UpdateTrickType;
use App\Form\VideoType;
use App\Repository\CommentRepository;
use App\Repository\PicturesRepository;
use App\Repository\TricksRepository;
use App\Repository\UserRepository;
use App\Repository\VideosRepository;
use App\Service\CommentService;
use App\Service\PictureService;
use App\Service\TrickService;
use App\Service\VideoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickController extends AbstractController
{
    private $commentService;
    private $trickRepo;
    private $Tricks;
    private $commentRepo;
    private $trickServ;
    private $userRepo;
    private $pictureService;
    private $videoService;
    private $videoRepo;
    private $pictureRepo;

    public function __construct(Security $security,
                                CommentService $commentService,
                                TricksRepository $trickRepo,
                                VideosRepository $videoRepo,
                                CommentRepository $commentRepo,
                                TrickService $trickServ,
                                UserRepository $userRepo,
                                PictureService $pictureService,
                                VideoService $videoService,
                                PicturesRepository $pictureRepo
                                )
    {
        // service de Symfony permettant l'authentification
        $this->Security = $security;
        $this->commentService = $commentService;
        $this->trickRepo = $trickRepo;
        $this->commentRepo = $commentRepo;
        $this->trickServ = $trickServ;
        $this->userRepo = $userRepo;
        $this->pictureService = $pictureService;
        $this->videoService = $videoService;
        $this->videoRepo = $videoRepo;
        $this->pictureRepo = $pictureRepo;

    }

    /**
     * @Route ("/newTrick", name="newTrick")
     * @param Request $request
     * @param TrickService $trickService
     * @param Security $security
     * @param SluggerInterface $slugger
     * @return Response
     */

    public function newTrick(Request $request, TrickService $trickService, Security $security, SluggerInterface $slugger)
    {
        //redirige l'utilisateur vers la homepage s'il n'est pas connecté
        if (!$this->Security->isGranted('IS_AUTHENTICATED_FULLY')){
            return $this->redirectToRoute('homepage');
        }

        $user = $this->getUser();
        $form = $this->createForm(TrickType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $formData = $form->getData();
            //dd($formData);

                //image principale
                $uploadFile = $formData->getImageFile();
                $originalFilename = pathinfo($uploadFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilenameProfilePicture = $safeFilename.'-'.uniqid().'.'.$uploadFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $uploadFile->move(
                        $this->getParameter('trick_picture_directory'),
                        $newFilenameProfilePicture
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $formData->setMainPicture($newFilenameProfilePicture);

            foreach ($formData->getPicture() as $picture){
                $uploadFile = $picture->getImageFile();
                $originalFilename = pathinfo($uploadFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
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
                $picture->setName($newFilename);
                $picture->setCreationDate(new \DateTime());
            }
            foreach ($formData->getVideos() as $video){
                $urlUploaded = $video->getName();
                $urlToSave = $this->videoService->videoUrlVerification($urlUploaded);
                if ($urlToSave == 0){
                    echo "La video n'a pas le bon format";
                }else{
                    $video->setName($urlToSave);
                }
            }

            $formData->setUser($user);
            $slug = $slugger->slug($formData->getName());
            $formData->setSlug($slug);
            $trickService->createNewTrick($formData);

            $this->addFlash('success', 'new trick creat');
            return $this->redirectToRoute('trick',['slug' => $slug]);

        }

        return $this->render('Trick/trick.html.twig',
            ['form' => $form->createView(),
                'mode_ouverture' => 'create']
            );
    }


    /**
     * @Route ("/trick/{slug}", name="trick")
     * @param Request $request
     * @param $slug
     * @return Response
     */
    public function showTrick(Request $request, $slug)
    {
        $trick = $this->getDoctrine()->getRepository(Tricks::class)->findOneBy(['slug' => $slug]);
        $picture = $trick->getPicture();
        $idTrick = $trick->getId();
        $allComments = $trick->getComment();
        //$comments = $this->trickRepo->getCommentsByLimit(0, Tricks::LIMIT_COMMENT_PER_PAGE);
        //$comments = $allComments * $this->Tricks::LIMIT_COMMENT_PER_PAGE;
        //dd($objectTricks);
        $user = $this->getUser();
        $commentForm = $this->createForm(CommentType::class)->handleRequest($request);
        $medias = $this->trickServ->getAllMediaOfTrick($trick->getId());
        $mainImage = $trick->getMainPicture();

            if ($user && $commentForm->isSubmitted() && $commentForm->isValid())
            {
                //$commentForm->getData();
                $commentContent = $commentForm->get('content')->getData();
                $this->commentService->createNewComment($commentContent,$idTrick,$user);
                $this->addFlash('success', 'new comment creat');
                return $this->redirectToRoute('trick',['slug' => $slug]);
            }
        return $this->render('Trick/showTrick.html.twig',
            ['trick' => $trick,
                'picturee' => $picture ,
                'allComments' => $allComments,
                'medias' => $medias,
                'mode_ouverture' => 'show',
                'mainImage' => $mainImage,
                'commentForm' => $commentForm->createView()
            ]
        );
    }

    /**
     * @Route ("/test/{id}", name="test")
     * @param int $id
     * @param Request $request
     * @param UserInterface $user
     * @return Response
     * @return Response
     */
    public function test(int $id, Request $request)
    {
        $trick = $this->getDoctrine()->getRepository(Tricks::class)->find($id);
        $picture = $trick->getPicture();
        $idTrick = $trick->getId();
        $allComments = $trick->getComment();
        //$comments = $this->trickRepo->getCommentsByLimit(0, Tricks::LIMIT_COMMENT_PER_PAGE);
        //$comments = $allComments * $this->Tricks::LIMIT_COMMENT_PER_PAGE;
        //dd($objectTricks);
        $user = $this->getUser();
        $commentForm = $this->createForm(CommentType::class)->handleRequest($request);

        if ($user){
            if ($commentForm->isSubmitted() && $commentForm->isValid())
            {
                $commentContent = $commentForm->get('content')->getData();
                $this->commentService->createNewComment($commentContent,$idTrick,$user);
                $this->addFlash('success', 'new comment creat');
            }
            return $this->render('Trick/test/test.html.twig', ['trick' => $trick, 'picture' => $picture , 'allComments' => $allComments,'commentForm' => $commentForm->createView()]);
        }
        return $this->render('Trick/test/test.html.twig', ['trick' => $trick, 'picture' => $picture , 'allComments' => $allComments,'commentForm' => $commentForm->createView()]);
    }

    /**
     * @Route("/tricks/{id}/comments", name="load-more-comment", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function showNextComment(Request $request, string $id): JsonResponse
    {
        $page = $request->query->has('page') ? $request->query->getInt('page') : 2;
        $comments = $this->commentRepo->getCommentsPagination($id,$page);

        return new JsonResponse(
            [
                'code' => 200,
                'html' => $this->renderView('_parts/_show_more_comments.html.twig', ['comments' => $comments])
            ]
        );
    }

    /**
     * @Route ("/trick/update/{slug}", name="trick_update")
     * @param string $slug
     * @param Request $request
     * @param Tricks $trick
     * @param Security $security
     * @param SluggerInterface $slugger
     * @return Response
     */
    public function updateTrick(string $slug, Request $request, Security $security, SluggerInterface $slugger)
    {
        //redirige l'utilisateur vers la homepage s'il n'est pas connecté
        if (!$this->Security->isGranted('IS_AUTHENTICATED_FULLY')){
            return $this->redirectToRoute('homepage');
        }
        $trick = $this->trickRepo->findOneBy(['slug' => $slug]);
        $form = $this->createForm(UpdateTrickType::class, $trick);
        $form->handleRequest($request);
        $medias = $this->trickServ->getAllMediaOfTrick($trick->getId());
        $mainImage = $trick->getMainPicture();

        if ($form->isSubmitted() && $form->isValid())
        {
            $formData = $form->getData();
            foreach ($formData->getPicture() as $picture){
                $uploadFile = $picture->getImageFile();
                //$trick = $this->trickServ->UploadPicture($trick, $uploadFile);
                //dd($uploadFile);
                //dd($form->getData());

                $originalFilename = pathinfo($uploadFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
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
                //$trick->setName($newFilename);
                //$trick->setDescription($newFilename);
            }
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'updated');
        }
        return $this->render('Trick/updateTrick.html.twig', ['form' => $form->createView(),
            'mode_ouverture' => 'update',
            'medias' => $medias,
            'mainImage' => $mainImage,
            'trick' => $trick]);
    }

    /**
     * @Route ("/trick/update/picture/{id<\d+>}", name="trick_update_picture")
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function updateTrickPicture(int $id, Request $request, Security $security, SluggerInterface $slugger)
    {
        //redirige l'utilisateur vers la homepage s'il n'est pas connecté
        if (!$this->Security->isGranted('IS_AUTHENTICATED_FULLY')){
            return $this->redirectToRoute('homepage');
        }
        $mediaId = $request->get('idMedia');
        $pictureToReplace = $this->pictureRepo->find($mediaId);

        $trickId = $request->get('id');
        $trick = $this->trickServ->findTrickById($trickId);

        $form = $this->createForm(PictureType::class, $pictureToReplace);
        $form->handleRequest($request);

        //$trick->removePicture($pictureToReplace);
        $newPicture = $form->getData();

        if ($form->isSubmitted() && $form->isValid()) {

                $uploadFile = $newPicture->getImageFile();

                $originalFilename = pathinfo($uploadFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $uploadFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $uploadFile->move(
                        $this->getParameter('trick_picture_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            $pictureToReplace->setName($originalFilename);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'updated');
            return $this->redirectToRoute('trick_update',['id' => $trick->getId()]);

        }
        return $this->render('_parts/_update_trick_picture_modal.html.twig', ['form' => $form->createView(),
            'mode_ouverture' => 'update',
            'medias' => $pictureToReplace,
            'trick' => $trick]);
    }

    /**
     * @Route ("/trick/update/video", name="trick_update_video")
     * @param Request $request
     * @param Security $security
     * @param SluggerInterface $slugger
     * @return Response
     */
    public function updateTrickVideo(Request $request, Security $security, SluggerInterface $slugger)
    {
        //redirige l'utilisateur vers la homepage s'il n'est pas connecté
        if (!$this->Security->isGranted('IS_AUTHENTICATED_FULLY')){
            return $this->redirectToRoute('homepage');
        }

        $mediaId = $request->get('idMedia');
        $trickId = $request->get('id');
        $trick = $this->trickServ->findTrickById($trickId);
        $videoToReplace = $this->trickServ->getTheVideo($mediaId);

        $form = $this->createForm(VideoType::class, $videoToReplace);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

                $formData = $form->getData();

                $trick->removeVideo($videoToReplace);

                $urlUploaded = $formData->getName();
                $urlToSave = $this->videoService->videoUrlVerification($urlUploaded);
                if ($urlToSave == 0){
                    echo "La video n'a pas le bon format";
                }else{
                    $formData->setName($urlToSave);
                }
                $trick->addVideo($formData);

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'updated');
            return $this->redirectToRoute('trick_update',['id' => $trick->getId()]);

        }
        return $this->render('_parts/_update_trick_video_modal_update.html.twig', ['form' => $form->createView(),
            'mode_ouverture' => 'update',
            'medias' => $videoToReplace,
            'trick' => $trick]);
    }

    /**
     * @Route("/trick/delete/{id<\d+>}",name="delete_trick")
     */
    public function delete(Request $request, Security $security)
    {
        //redirige l'utilisateur vers la homepage s'il n'est pas connecté
        if (!$this->Security->isGranted('IS_AUTHENTICATED_FULLY')){
            return $this->redirectToRoute('homepage');
        }
        $trickId = $request->get('id');
        $trick = $this->trickServ->findTrickById($trickId);
        $em = $this->getDoctrine()->getManager();
        $em->remove($trick);
        $em->flush();
        // redirige la page
        return $this->redirectToRoute('homepage');
    }

    //* @Route("/picture/delete/{id<\d+>}",name="picture_delete")
    /**
     * @Route("/tricks/picture/delete",name="picture_delete")
     */
    public function pictureDelete(Request $request, Security $security)
    {
        //redirige l'utilisateur vers la homepage s'il n'est pas connecté
        if (!$this->Security->isGranted('IS_AUTHENTICATED_FULLY')){
            return $this->redirectToRoute('homepage');
        }
        //dd($request->get('idPicture'));
        $idPicture = $request->get('idPicture');
      //  dd($request->get('idPicture'));
        //dd($idPicture);
        $picture = $this->pictureRepo->find($idPicture);
        //dd($picture->getId());
        $idTrick = $request->get('idTrick');
        $trick = $this->trickServ->findTrickById($idTrick);

        $this->trickServ->deletePictureFromTrick($trick, $picture);

        //TODO trouver comment supprimer un fichier d'un repertoire
        //$em = $this->getDoctrine()->getManager();
        //$em->remove($trick);
        //$em->flush();
        // redirige la page
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/tricks/{idTrick}/videos/{videoId}/delete",name="video_delete")
     * @param Request $request
     * @param $trickId
     * @param Security $security
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function videoDelete(Request $request, Security $security)
    {
        //redirige l'utilisateur vers la homepage s'il n'est pas connecté
        if (!$this->Security->isGranted('IS_AUTHENTICATED_FULLY')){
            return $this->redirectToRoute('homepage');
        }
        $idVideo = $request->get('videoId');
        $trickId = $request->get('idTrick');
        $trick = $this->trickServ->findTrickById($trickId);
        $video = $this->videoService->findVideoById($idVideo);
        $this->trickServ->deleteVideoFromTrick($trick, $video);
        //$trick->removeVideo($video);
        //$em = $this->getDoctrine()->getManager();
        //$em->remove($trick);
        //$em->flush();
        // redirige la page
        //return $this->redirectToRoute('trick_update',['id' => $trick->getId()]);
        return $this->redirectToRoute('homepage');
    }


    /**
     * @Route("/trickVideoDelete",name="delete_trick_video")
     * @param Request $request
     * @param Security $security
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteTrickVideo(Request $request, Security $security)
    {
        //redirige l'utilisateur vers la homepage s'il n'est pas connecté
        if (!$this->Security->isGranted('IS_AUTHENTICATED_FULLY')){
            return $this->redirectToRoute('/');
        }
        if ($request->isXmlHttpRequest()){
            $idVideo = $request->get('idVideo');
            print_r($idVideo);
        }

        /*
         * $video = $request->request->get('video');
        dd($video);
        //dd($video);
        $trickId = $this->videoRepo->
        //$video = $request->query->has('video');
        $a = $this->videoRepo->find($video);
        dd($a);
        $trick->removeVideo($video);
         */
        //return $this->redirectToRoute('trick_update',['id' => $trick->getId()]);

    }
}
