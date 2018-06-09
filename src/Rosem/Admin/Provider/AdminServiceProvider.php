<?php

namespace Rosem\Admin\Provider;

use Psr\Container\ContainerInterface;
use Psrnext\Http\Factory\ResponseFactoryInterface;
use Psrnext\ViewRenderer\ViewRendererInterface;
use Rosem\Admin\Http\Server\AdminRequestHandler;
use Rosem\Admin\Http\Server\LoginRequestHandler;
use Psrnext\Config\ConfigInterface;
use Psrnext\Container\ServiceProviderInterface;
use Psrnext\Route\RouteCollectorInterface;
use Rosem\Authentication\Http\Server\AuthenticationMiddleware;

class AdminServiceProvider implements ServiceProviderInterface
{
    /**
     * Returns a list of all container entries registered by this service provider.
     *
     * @return callable[]
     */
    public function getFactories(): array
    {
        return [
            AdminRequestHandler::class => function (ContainerInterface $container) {
                return new AdminRequestHandler(
                    $container->get(ResponseFactoryInterface::class),
                    $container->get(ViewRendererInterface::class),
                    $container->get(ConfigInterface::class)
                );
            },
            LoginRequestHandler::class => function (ContainerInterface $container) {
                return new LoginRequestHandler(
                    $container->get(ResponseFactoryInterface::class),
                    $container->get(ViewRendererInterface::class),
                    $container->get(ConfigInterface::class)
                );
            },
        ];
    }

    /**
     * Returns a list of all container entries extended by this service provider.
     *
     * @return callable[]
     */
    public function getExtensions(): array
    {
        return [
            RouteCollectorInterface::class => function (
                ContainerInterface $container,
                RouteCollectorInterface $routeCollector
            ) {
                $adminUri = '/' . $container->get(ConfigInterface::class)->get('admin.uri', 'admin');
                $routeCollector->get($adminUri . '/login', LoginRequestHandler::class);
                $routeCollector->post($adminUri . '/login', LoginRequestHandler::class)
                    ->setMiddleware(AuthenticationMiddleware::class);
                $routeCollector->get($adminUri . '{path:.*}', AdminRequestHandler::class)
                    ->setMiddleware(AuthenticationMiddleware::class);
            },
        ];
    }
}
