<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Location;
use App\Form\ClientType;
use App\Form\LocationType;
use App\Repository\ClientRepository;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/client")
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class ClientController extends AbstractController
{
    /**
     * @Route("/", name="client_index", methods={"GET"})
     */
    public function index(ClientRepository $clientRepository): Response
    {
        return $this->render('client/index.html.twig', [
            'clients' => $clientRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="client_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client->getUser()->setPassword($passwordEncoder->encodePassword($client->getUser(), "passer"));
            $client->getUser()->setRoles(["ROLE_CLIENT"]);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('client_index');
        }

        return $this->render('client/new.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="client_show", methods={"GET"})
     */
    public function show(Client $client): Response
    {
        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="client_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Client $client): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('client_index');
        }

        return $this->render('client/edit.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="client_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Client $client): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($client);
            $entityManager->flush();
        }

        return $this->redirectToRoute('client_index');
    }

    /**
     * @Route("/{id}/location", name="client_location", methods={"GET", "POST"})
     */
    public function listLocation(LocationRepository $locationRepository, Client $client): Response
    {
        $locations = $locationRepository->findBy(["client" => $client, "etat" => "en_cours"]);

        return $this->render('client/locations.html.twig', [
            'client' => $client,
            'locations' => $locations,
        ]);
        
    }

    /**
     * @Route("/{id}/location/new", name="client_location_new", methods={"GET", "POST"})
     */
    public function addLocation(Request $request,  Client $client): Response
    {
        $location = new Location();
        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach($location->getArticle() as $article) {
                $article->setEtat('en_location');
            }
            $location->setEtat('en_cours');
            $location->setClient($client);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($location);
            $entityManager->flush();

            return $this->redirectToRoute('client_location', [
                'id' => $client->getId(),
            ]);
        }

        return $this->render('client/new_location.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
        
    }
}
