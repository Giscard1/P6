<?php


namespace App\Controller;


use App\Entity\Tricks;
use App\Repository\TricksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{

    /**
     * @Route ("/homepage", name="homepage")
     * @param Request $request
     * @param TricksRepository $tricksRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function homepage(Request $request, TricksRepository $tricksRepository)
    {
        //$allTricks = $trickrepository->findAll();
        $paginator = $tricksRepository->getTrickByLimit(1, Tricks::LIMIT_PER_PAGE);

        return $this->render(
            'Homepage/homepage.html.twig',
            [
            'allTrick' => $paginator,
            'page' => 1,
            'pageTotal' => ceil(count($paginator) / Tricks::LIMIT_PER_PAGE)
        ]);
    }

    /**
     * @param Request $request
     * @Route("/ajax/tricks", name="get_tricks_ajax", methods={"GET"})
     * @param TricksRepository $tricksRepository
     * @return JsonResponse
     */
    public function getTricksWithAjaxRequest(Request $request, TricksRepository $tricksRepository)
    {
        $pageTargeted = $request->query->getInt('page');
        $tricks = $tricksRepository->getTrickByLimit($pageTargeted, Tricks::LIMIT_PER_PAGE);

        return new JsonResponse(
            [
                "html" => $this->renderView('_parts/_tric_card.html.twig', ['allTrick' => $tricks])
            ]
        );
    }

}
