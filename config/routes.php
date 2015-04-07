<?php
return [
    'main' => [
                'resource' => 'home', 
                // 'constraints' => ['id' => '([-_a-z0-9]+)'], // TODO: Need to make functionality
                'only' => ['index', 'add'], 
                'path_names' => ['all' => 'index', 'new' => 'add'],
                ],
];