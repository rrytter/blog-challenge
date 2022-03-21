<?php

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', true);

// Check for PHP SQLite extension
if (!extension_loaded('sqlite3')) {
    trigger_error('SQLite extension is required', E_USER_ERROR);
}

// App constants
define('APP_PATH', [
    'VAR'       => __DIR__ . '/var',
    'TEMPLATES' => __DIR__ . '/templates'
]);
define('APP_DATABASE', APP_PATH['VAR'] . '/database.sqlite');

try {

    // Create PDO instance (Database)
    $pdo = new PDO('sqlite:' . APP_DATABASE);

    // Check SQLite database file
    if (!file_exists(APP_DATABASE)) {
        throw new Exception('SQLite database not found');
    }

    // Initialize database?
    if (filesize(APP_DATABASE) === 0) {

        // Structure
        $pdo->exec("CREATE TABLE `posts` (
            'id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            'title' TEXT,
            'content' TEXT,
            'status' TEXT DEFAULT 'draft',
            'created' DATETIME DEFAULT CURRENT_DATE,
            'updated' DATETIME DEFAULT CURRENT_DATE
        );");

        // Fixtures
        $lines = [
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'Morbi est turpis, venenatis vitae pellentesque vitae, venenatis a nisi.',
            'Cras gravida ipsum vel lacus varius vehicula. Curabitur arcu urna, faucibus a commodo eu, ultrices vitae sem.',
            'Nullam sollicitudin purus sed libero porta pretium.',
            'Nam pulvinar tristique sapien facilisis ornare. Suspendisse pretium turpis ac enim convallis, id feugiat sapien pharetra.',
            'Duis ornare ornare molestie.',
            'Duis vehicula felis in urna viverra, eget ornare enim sodales.'
        ];

        for ($i = 0; $i < 12; ++$i) {
            
            shuffle($lines); // Random content
            $date = date('Y-m-d', time() - $i * 3600 * 24); // -1 day in each cycle
            
            $pdo->prepare("INSERT INTO `posts` ('id', 'title', 'content', 'status', 'created', 'updated') VALUES (NULL, :title, :content, :status, :created, :updated)")
                ->execute([
                    'title'     => $lines[0],
                    'content'   => implode(' ', $lines),
                    'status'    => 'publish',
                    'created'   => $date,
                    'updated'   => $date
                ])
            ;

        }    

    }

    // Routes: [pattern, callback][]
    $routes = [

        // Home: /
        [
            '#^/$#',
            function(string $request) use ($pdo) {

                $sth = $pdo->prepare("SELECT * FROM `posts` WHERE `status` = 'publish' ORDER BY `created` DESC, `id` DESC");
                $sth->execute();
                
                if ($posts = $sth->fetchAll(PDO::FETCH_ASSOC)) {
                    $posts = array_map(function($post) {
                        $excerpt = strlen($post['content']) > 200
                            ? substr($post['content'], 0, 200) . '...'
                            : $post['content']
                        ;
                        return $post + ['excerpt' => $excerpt];
                    }, $posts);
                }
                
                include APP_PATH['TEMPLATES'] . '/home.php';
            }
        ],

        // Article: /article/<id>
        [
            '#^/article/(\d+)$#',
            function(string $request, int $id) use ($pdo) {
                
                $sth = $pdo->prepare("SELECT * FROM `posts` WHERE `id` = :id AND `status` = 'publish'");
                $sth->execute(['id' => $id]);
                
                $post = $sth->fetch(PDO::FETCH_ASSOC);
                
                include APP_PATH['TEMPLATES'] . '/article.php';
            }
        ],

        // Admin: /admin/<action>/<id>
        [
            '#^/admin(?:/(create|update|delete))?(?:/(\d+))?$#',
            function(string $request, ?string $action = null, ?int $id = null) use ($pdo) {

                // Create/Update validation, then persist in database
                if (
                    in_array($action, ['create', 'update'])
                    && !empty($_POST['title'])
                    && !empty($_POST['content'])
                    && !empty($_POST['status'])
                    && in_array($_POST['status'], ['draft', 'publish'])
                ) {

                    // Create
                    if ($action === 'create') {
                        $sth = $pdo->prepare("INSERT INTO `posts` ('id', 'title', 'content', 'status') VALUES (NULL, :title, :content, :status)");
                        $sth->execute([
                            'title'     => (string) $_POST['title'],
                            'content'   => (string) $_POST['content'],
                            'status'    => (string) $_POST['status']
                        ]);
                    }

                    // Update
                    if ($action === 'update' && $id) {
                        $sth = $pdo->prepare("UPDATE `posts` SET `title` = :title, `content` = :content, `status` = :status, `updated` = :updated WHERE `id` = :id");
                        $sth->execute([
                            'title'     => (string) $_POST['title'],
                            'content'   => (string) $_POST['content'],
                            'status'    => (string) $_POST['status'],
                            'updated'   => date('Y-m-d'),
                            'id'        => $id
                        ]);
                    }

                    header('Location: /admin');
                    exit;

                }

                // Create defaults
                if ($action === 'create') {
                    $post = $_POST + [
                        'title'     => '',
                        'content'   => '',
                        'status'    => 'draft'
                    ];
                }

                // Update defaults
                if ($action === 'update' && $id) {
                    $sth = $pdo->prepare("SELECT * FROM `posts` WHERE `id` = :id");
                    $sth->execute(['id' => $id]);
                    $post = $_POST + $sth->fetch(PDO::FETCH_ASSOC);
                }

                // Delete
                if ($action === 'delete' && $id) {
                    $sth = $pdo->prepare("DELETE FROM `posts` WHERE `id` = :id");
                    $sth->execute(['id' => $id]);
                    header('Location: /admin');
                    exit;
                }

                // Listing
                if ($action === null) {
                    $sth = $pdo->prepare("SELECT * FROM `posts`");
                    $sth->execute();
                    $posts = $sth->fetchAll(PDO::FETCH_ASSOC);
                }

                include APP_PATH['TEMPLATES'] . '/admin.php';
            }
        ]

    ];
    
    // Get master request uri
    $request = '/' . trim($_SERVER['REQUEST_URI'], '/');

    // Find matching route
    foreach ($routes as [$pattern, $callback]) {
        if (preg_match($pattern, $request, $args)) {
            $callback(...$args);
            exit;
        }
    }

    // No matching route, throw 404
    header('HTTP/1.0 404 Not Found');
    include APP_PATH['TEMPLATES'] . '/404.php';

} catch (Throwable $e) {

    // Uncaught exception
    header('HTTP/1.0 500 Internal Server Error');
    include APP_PATH['TEMPLATES'] . '/500.php';

}