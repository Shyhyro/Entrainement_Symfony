<?php

namespace App\Controller;

use App\Service\PlaceholderImageService;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/articles', name: 'articles_')]
class ArticleController extends AbstractController
{
    /**
     * List available articles.
     * @return Response
     */
    #[Route('/', name: 'list')]
    public function list() :Response
    {
        return new Response("<h1>Listes des articles</h1>");
    }

    /**
     * Add a new article
     * @param PlaceholderImageService $placeholderImageService
     * @return Response
     */
    #[Route('/addImage', name: 'add')]
    public function addImage(PlaceholderImageService $placeholderImageService) :Response
    {
        try {
            $success = $placeholderImageService->getNewImageAndSave(350, 250, 'articlexyz-thumb.png');
        }
        catch (Error $e) {
            $success = false;
        }

        if ($success) {
            return new Response("<div>L'article a été créer avec succès</div>");
        }

        return new Response("<div>Erreur en ajoutant l'article</div>");
    }

    #[Route('/articles', name: 'articles_list')]
    public function list() :JsonResponse
    {
        $articles = [
            new Articles(),
            new Articles(),
            new Articles(),
            new Articles(),
        ];

        return $this->json([
            $articles[0]->getTitle(),
            $articles[1]->getTitle(),
            $articles[2]->getTitle(),
            $articles[3]->getTitle(),
        ]);
    }

    #[Route('/article/add', name: 'article_add')]
    public function add() :Response
    {
        if (!in_array('ROLE_AUTHOR', $this->getUser()->getRoles()))
        {
            return $this->redirectToRoute('articles_list');
        }
        return $this->render('article/add.html.twig');
    }
}