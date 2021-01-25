<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Repository\LocationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class LocationController extends AbstractController
{
    /**
     * @Route("/locations", name="location_progess")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function index(LocationRepository $locationRepository): Response
    {
        $locations = $locationRepository->findBy(["etat" => "en_cours"]);

        return $this->render('location/index.html.twig', [
            'locations' => $locations,
        ]);
    }
}
