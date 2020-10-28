<?php

namespace App\DataFixtures;

use App\Component\Database\DatabaseManager;
use App\Entity\Post;

class PostFixtures
{
    private DatabaseManager $databaseManager;

    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    public function load()
    {
        $this->databaseManager->getConnection()->exec(sprintf("CREATE TABLE `%s` (
            'id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            'title' TEXT,
            'content' TEXT,
            'status' TEXT DEFAULT 'draft',
            'created' DATETIME DEFAULT CURRENT_DATE,
            'updated' DATETIME DEFAULT CURRENT_DATE
        );", Post::getTable()));

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
            $timestamp = time() - $i * 3600 * 24; // -1 day in each cycle

            (new Post($this->databaseManager, [
                'title'     => $lines[0],
                'content'   => implode(' ', $lines),
                'status'    => 'publish',
                'created'   => date('Y-m-d', $timestamp),
                'updated'   => date('Y-m-d', $timestamp)
            ]))->persist();
        }
    }
}