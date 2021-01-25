<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Entity\Article;
use App\Form\BienType;
use App\Form\ArticleType;
use App\Repository\BienRepository;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/bien")
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class BienController extends AbstractController
{
    /**
     * @Route("/", name="bien_index", methods={"GET"})
     */
    public function index(BienRepository $bienRepository): Response
    {
        return $this->render('bien/index.html.twig', [
            'biens' => $bienRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="bien_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $bien = new Bien();
        $form = $this->createForm(BienType::class, $bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bien);
            $entityManager->flush();

            return $this->redirectToRoute('bien_index');
        }

        return $this->render('bien/new.html.twig', [
            'bien' => $bien,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="bien_show", methods={"GET"})
     */
    public function show(Bien $bien): Response
    {
        return $this->render('bien/show.html.twig', [
            'bien' => $bien,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="bien_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Bien $bien): Response
    {
        $form = $this->createForm(BienType::class, $bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()
                ->getManager()
                ->flush();

            return $this->redirectToRoute('bien_index');
        }

        return $this->render('bien/edit.html.twig', [
            'bien' => $bien,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="bien_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Bien $bien): Response
    {
        if (
            $this->isCsrfTokenValid(
                'delete' . $bien->getId(),
                $request->request->get('_token')
            )
        ) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($bien);
            $entityManager->flush();
        }

        return $this->redirectToRoute('bien_index');
    }

    /**
     * @Route("/{id}/article", name="bien_article", methods={"GET", "POST"})
     */
    public function listArticle(ArticleRepository $articleRepository, Bien $bien): Response
    {
        $articles = $articleRepository->findBy(["bien" => $bien]);

        return $this->render('bien/articles.html.twig', [
            'bien' => $bien,
            'articles' => $articles,
        ]);
        
    }

    /**
     * @Route("/{id}/article/new", name="bien_new_article", methods={"GET", "POST"})
     */
    public function addArticle(Request $request, Bien $bien): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setBien($bien);
            $article->setEtat('en_agence');
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('bien_article', [
                'id' => $bien->getId(),
            ]);
        }

        return $this->render('bien/new_article.html.twig', [
            'bien' => $bien,
            'form' => $form->createView(),
        ]);
    }
}
