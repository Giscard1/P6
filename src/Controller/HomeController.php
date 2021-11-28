<?php


namespace App\Controller;


use App\Entity\Tricks;
use App\Repository\TricksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route ("/homepage", name="homepage")
     * @param Request $request
     * @param TricksRepository $trickrepository
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function homepage(Request $request, TricksRepository $trickrepository)
    {
        $allTricks = $trickrepository->findAll();

        return $this->render('Homepage/homepage.html.twig', ['allTrick' => $allTricks]);
    }

}
