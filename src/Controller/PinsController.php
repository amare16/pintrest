<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Form\PinType;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="app_home", methods={"GET"})
     */
    public function index(PinRepository $pinRepository): Response
    {
        // find all result from repository
        $pins = $pinRepository->findBy([], ['createdAt' => 'DESC']);

        // return the result on the browser by using twig
        return $this->render('pins/index.html.twig', compact('pins'));
    }

    /**
     * @Route("/pins/create", name="app_pins_create", methods={"GET", "POST"})
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $pin = new Pin;
//        $form = $this->createFormBuilder($pin)
//            ->add('title', TextType::class)
//            ->add('description', TextareaType::class)
//            ->getForm()
//        ;
        // Instead of above $form, we use form type PinType
        $form = $this->createForm(PinType::class, $pin);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($pin);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }


        return $this->render('pins/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/pins/{id<\d+>}", name="app_pins_show", methods={"GET"})
     */

    public function show(Pin $pin) : Response
    {
        return $this->render('pins/show.html.twig', compact('pin'));
    }

    /**
     * @Route("/pins/{id<\d+>}/edit", name="app_pins_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Pin $pin, EntityManagerInterface $em) : Response
    {

        $form = $this->createForm(PinType::class, $pin);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('pins/edit.html.twig', [
            'pin' => $pin,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/pins/{id<\d+>}/delete", name="app_pins_delete", methods={"GET", "POST"})
     */
    public function delete(Request $request, Pin $pin, EntityManagerInterface $em) : Response
    {

        if ($this->isCsrfTokenValid('pin_deletion_' . $pin->getId(), $request->request->get('crsf_token') )) {
            $em->remove($pin);
            $em->flush();
        }


        return $this->redirectToRoute('app_home');
    }


}
