<?php

namespace App\Controller;

use App\Component\Database\DatabaseManager;
use App\Component\Kernel\Kernel;
use App\Entity\Post;
use App\Repository\PostRepository;

class AdminController extends Controller
{
    private DatabaseManager $databaseManager;
    private PostRepository $postRepository;
    
    public function __construct(Kernel $kernel)
    {
        parent::__construct($kernel);

        $this->databaseManager = $this->getKernel()->getDatabaseManager();
        $this->postRepository = new PostRepository($this->databaseManager);
    }

    public function index(): void
    {
        echo $this->render('admin/index.php', [
            'posts' => $this->postRepository->findAll()
        ]);
    }

    public function create(): void
    {
        if (!empty($_POST)) {
            
            (new Post($this->databaseManager))
                ->setTitle($_POST['title'])
                ->setContent($_POST['content'])
                ->setStatus($_POST['status'])
                ->setCreated(new \DateTime())
                ->setUpdated(new \DateTime())
                ->persist()
            ;

            header('Location: /admin');
            exit;
        }

        echo $this->render('admin/form.php', [
            'post' => new Post($this->databaseManager)
        ]);
    }

    public function update(int $id): void
    {
        if (
            !empty($_POST)
            && ($post = $this->postRepository->findOneById($id))
        ) {
            
            $post
                ->setTitle($_POST['title'])
                ->setContent($_POST['content'])
                ->setStatus($_POST['status'])
                ->setUpdated(new \DateTime())
                ->persist()
            ;

            header('Location: /admin');
            exit;
        }

        echo $this->render('admin/form.php', [
            'post' => $this->postRepository->findOneById($id)
        ]);
    }

    public function delete(int $id): void
    {
        if ($post = $this->postRepository->findOneById($id)) {
            $post->delete();
        }

        header('Location: /admin');
        exit;
    }
}