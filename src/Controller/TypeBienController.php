<?php

namespace App\Controller;

use App\Entity\TypeBien;
use App\Form\TypeBienType;
use App\Repository\TypeBienRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/type/bien")
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class TypeBienController extends AbstractController
{
    /**
     * @Route("/", name="type_bien_index", methods={"GET"})
     */
    public function index(TypeBienRepository $typeBienRepository): Response
    {
        return $this->render('type_bien/index.html.twig', [
            'type_biens' => $typeBienRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="type_bien_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $typeBien = new TypeBien();
        $form = $this->createForm(TypeBienType::class, $typeBien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($typeBien);
            $entityManager->flush();

            return $this->redirectToRoute('type_bien_index');
        }

        return $this->render('type_bien/new.html.twig', [
            'type_bien' => $typeBien,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="type_bien_show", methods={"GET"})
     */
    public function show(TypeBien $typeBien): Response
    {
        return $this->render('type_bien/show.html.twig', [
            'type_bien' => $typeBien,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="type_bien_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TypeBien $typeBien): Response
    {
        $form = $this->createForm(TypeBienType::class, $typeBien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('type_bien_index');
        }

        return $this->render('type_bien/edit.html.twig', [
            'type_bien' => $typeBien,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="type_bien_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TypeBien $typeBien): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeBien->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($typeBien);
            $entityManager->flush();
        }

        return $this->redirectToRoute('type_bien_index');
    }
}
