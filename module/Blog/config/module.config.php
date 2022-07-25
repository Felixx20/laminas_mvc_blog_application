<?php

namespace Blog;

use Laminas\Router\Http\Segment;




return [



    'router' => [
        'routes' => [
            'blog' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/blog[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\PostController::class,
                        'action'     => 'index',
                    ],
                ],

            ],

            'comment' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/blog/comment[/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\CommentController::class,
                        'action'     => 'index',
                    ],
                ],

            ],


            'createcomment' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/blog/createcomment[/:id]',
                    'defaults' => [
                        'controller' => Controller\CommentController::class,
                        'action'     => 'add',
                    ],
                ],

            ],


        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'blog' => __DIR__ . '/../view',
        ],
    ],
];