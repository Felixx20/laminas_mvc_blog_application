<?php

namespace User;

use Laminas\Authentication\AuthenticationService;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\Session\Container as SessionContainer;

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
                Model\UserTable::class => function ($container) {
                    $tableGateway = $container->get(Model\UserTableGateway::class);
                    $auth = new AuthenticationService();
                    return new Model\UserTable($tableGateway, $auth);
                },

                Model\UserTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\User());
                    return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
                },




            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\RegisterController::class => function ($container) {
                    return new Controller\RegisterController(
                        $container->get(Model\UserTable::class)
                    );
                },
                Controller\LoginController::class => function ($container) {
                    return new Controller\LoginController(
                        $container->get(Model\UserTable::class),
                        $container->get(SessionContainer::class),


                    );
                },
            ],
        ];
    }
}