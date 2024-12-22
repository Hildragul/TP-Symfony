<?php

namespace App\Controller;
use DateTimeImmutable;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/article/creer', name: 'app_article_create')]
    public function create(EntityManagerInterface $entityManager, FileUploader $fileuploader): Response
    {

        $article = new Article();
        $article->setTitre('Article créé ')
        ->setTexte('Contenu')
        ->setPublie(true)
        ->setDate(new DateTimeImmutable());

        $entityManager->persist($article);
        $entityManager->flush();

        $this->addFlash('success', 'L\'article a été créé avec succès.');

        return $this->render('article/creer/create.html.twig', [
            'controller_name' => 'ArticleController',
            'titre' => 'Article',
            'article' => $article
        ]);
    }


    #[Route('/article/modifier/{id}', name: 'app_article_modifier')]
    public function update(int $id, Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->addFlash('error', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('app_login');
        }

        $article = $entityManager->getRepository(Article::class)->find($id);
        
        if (!$article) {
            $this->addFlash('error', 'Article non trouvé!');
            return $this->redirectToRoute('app_article_afficher');
        }
  

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'image
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFilename = $fileUploader->upload($imageFile);
                $article->setImageFilename($imageFilename);
            }
            $entityManager->flush();
            $this->addFlash('success', 'Article modifié avec succès!');
            return $this->redirectToRoute('app_article_afficher');
        }

        return $this->render('article/modifier/modifier.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
        ]);
    }



    #[Route('/article/afficher', name: 'app_article_afficher')]
    public function show(EntityManagerInterface $entityManager): Response
    {

        $articles = $entityManager->getRepository(Article::class)->findAll();

        return $this->render('article/afficher/afficher.html.twig', [
            'articles' => $articles,
        ]);
    }


    #[Route('/article/supprimer/{id}', name: 'app_article_supprimer')]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {

        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->addFlash('error', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('app_login');
        }
        $article = $entityManager->getRepository(Article::class)->find($id);
        
        if (!$article) {
            throw $this->createNotFoundException('Article not found.');
        }


        $entityManager->remove($article);
        $entityManager->flush();

        $this->addFlash('success', 'L\'article a été supprimé avec succès.');

        return $this->redirectToRoute('app_article_afficher');
    }

    #[Route('/article/new', name: 'app_article_form')]
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->addFlash('error', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('app_login');
        }
    
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'image
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFilename = $fileUploader->upload($imageFile);
                $article->setImageFilename($imageFilename);
            }

            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'Article créé avec succès !');

            return $this->redirectToRoute('app_article_form');
        }
    
        return $this->render('article/creer/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
    


}
