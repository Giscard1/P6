<?php


namespace App\Service;


use App\Repository\PicturesRepository;
use App\Repository\TricksRepository;
use App\Repository\UserRepository;
use App\Repository\VideosRepository;
use Psr\Container\ContainerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class BaseService
{
    public $container;
    private $userRepo;
    protected $trickRepo;
    protected $slugger;
    private $picRepo;
    protected $pictureRepo;
    protected $videoRepo;


    /**
     * @var UserPasswordHasherInterface
     */
    public $passwordHacher;

    public function __construct(
                                ContainerInterface $container,
                                UserPasswordHasherInterface $passwordHacher,
                                UserRepository $userRepo,
                                TricksRepository $trickRepo,
                                SluggerInterface $slugger,
                                PicturesRepository $picRepo,
                                PicturesRepository $pictureRepo,
                                VideosRepository $videoRepo


    )
    {
        $this->container = $container;
        $this->passwordHacher = $passwordHacher;
        $this->userRepo = $userRepo;
        $this->trickRepo = $trickRepo;
        $this->slugger = $slugger;
        $this->picRepo = $picRepo;
        $this->pictureRepo = $pictureRepo;
        $this->videoRepo = $videoRepo;

    }

    function getDoctrine()
    {
        return $this->container->get('doctrine');
    }

    function persist($entity)
    {
        $this->getEm()->persist($entity);
    }

    function getEm()
    {
        return $this->getDoctrine()->getManager();
    }

    function flush()
    {
        $this->getEm()->flush();
    }
}
