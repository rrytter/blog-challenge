<?php

namespace App\Controller;

use App\Repository\PostRepository;

class HomeController extends Controller
{
    public function index(): void
    {
        $postRepository = new PostRepository(
            $this->getKernel()->getDatabaseManager()
        );
 
        echo $this->render('home.php', [
            'posts' => $postRepository->findAllPublished()
        ]);
    }
}