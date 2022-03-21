<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Component\Routing\Attribute\Route;

class HomeController extends Controller
{
    #[Route('/')]
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