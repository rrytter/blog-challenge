<?php

namespace App\Controller;

use App\Repository\PostRepository;

class PostController extends Controller
{
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