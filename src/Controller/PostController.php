<?php

namespace App\Controller;

use App\Component\Routing\Attribute\Route;
use App\Repository\PostRepository;

class PostController extends Controller
{
    #[Route('/article/{id}', name: 'blog')]
    public function index(int $id): void
    {
        $postRepository = new PostRepository(
            $this->getKernel()->getDatabaseManager()
        );
 
        echo $this->render('article.php', [
            'post' => $postRepository->findOneByIdAndPublished($id)
        ]);
    }
}