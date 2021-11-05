<?php


namespace App\Controller;


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
        return $this->render('Homepage/homepage.html.twig');
    }
}
