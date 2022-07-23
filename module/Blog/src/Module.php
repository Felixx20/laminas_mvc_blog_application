<?php

namespace Blog;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }


    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\PostTable::class => function ($container) {
                    $tableGateway = $container->get(Model\PostTableGateway::class);
                    return new Model\PostTable($tableGateway);
                },
                Model\PostTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Post());
                    return new TableGateway('post', $dbAdapter, null, $resultSetPrototype);
                },
                Model\CommentTable::class => function ($container) {
                    $tableGateway = $container->get(Model\CommentTableGateway::class);
                    return new Model\CommentTable($tableGateway);
                },
                Model\CommentTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Comment());
                    return new TableGateway('comment', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\PostController::class => function ($container) {
                    return new Controller\PostController(
                        $container->get(Model\PostTable::class)
                    );
                },
                Controller\CommentController::class => function ($container) {
                    return new Controller\CommentController(
                        $container->get(Model\CommentTable::class),
                        $container->get(Model\PostTable::class)
                    );
                },
            ],
        ];
    }
}