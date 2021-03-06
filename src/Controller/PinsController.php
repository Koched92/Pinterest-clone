<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PinsController extends AbstractController
{

    /**
     * @Route("/", name="app_home", methods={"GET"})
     */
    public function index(PinRepository $repo): Response
    {


        return $this->render('pins/index.html.twig', ['pins' => $repo->findAll()]);
    }

    /**
     * @Route("/pins/{id}", priority="1", name="app_pins_show")
     *
     */
    function show(Pin $pin): Response{


        return $this->render('pins/show.html.twig', compact('pin'));
    }

    /**
     * @Route("/pins/create", priority="2" ,name="app_pins_create", methods={"GET", "POST"})
     */
    public function create(Request $request, EntityManagerInterface $em): Response{

        $pin = new Pin();


        $form = $this->createFormBuilder($pin)
            ->add('title', TextType::class, ['attr' => ['autofocus' => true] ])
            ->add('description', TextareaType::class, ['attr' => ['rows'=>10, 'cols'=>50]])
            ->getForm()
        ;



        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $em->persist($pin);
            $em->flush();

            return $this->redirectToRoute('app_pins_show', ['id'=>$pin->getId()]);
        }
       return $this->render('pins/create.html.twig', ['form' => $form->createView()]);

    }

}
