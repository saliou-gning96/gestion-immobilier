<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Entity\Bien;
use App\Entity\Location;
use App\Entity\Proprietaire;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="dashboard")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();

        $repoBiens = $em->getRepository(Bien::class);

        $totalBiens = $repoBiens
            ->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $repoProprietaires = $em->getRepository(Proprietaire::class);

        $totalProprietaires = $repoProprietaires
            ->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $repoLocations = $em->getRepository(Location::class);

        $totalLocations = $repoLocations
            ->createQueryBuilder('a')
            ->where('a.etat = :etat')
            ->setParameter('etat', 'en_cours')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return $this->render('dashboard/index.html.twig', [
            'totalBiens' => $totalBiens,
            'totalProprietaires' => $totalProprietaires,
            'totalLocations' => $totalLocations,
            'controller_name' => 'DashboardController',
        ]);
    }
}
