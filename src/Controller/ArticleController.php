<?php

namespace App\Controller;

use App\Service\PlaceholderImageService;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    #[Route('/add', name: 'add')]
    public function add(PlaceholderImageService $placeholderImageService) :Response
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
}