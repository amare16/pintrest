<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Repository\PinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(PinRepository $pinRepository): Response
    {
        // find all result from repository
        $pins = $pinRepository->findBy([], ['createdAt' => 'DESC']);

        // return the result on the browser by using twig
        return $this->render('pins/index.html.twig', compact('pins'));
    }

    /**
     * @Route("/pins/{id<[0-9]>}", name="app_pins_show")
     */

    public function show(Pin $pin) : Response
    {
        return $this->render('pins/show.html.twig', compact('pin'));
    }
}
