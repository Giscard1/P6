<?php


namespace App\Controller;


use App\Entity\Tricks;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route ("/homepage", name="homepage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function homepage(Request $request)
    {
        $Trickrepository = $this->getDoctrine()->getRepository(Tricks::class);
        $allTricks = $Trickrepository->findAll();

        return $this->render('Homepage/homepage.html.twig', ['allTrick' => $allTricks]);
    }
}
