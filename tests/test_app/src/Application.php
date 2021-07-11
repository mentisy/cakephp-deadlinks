<?php
declare(strict_types=1);

namespace TestApp;

use Avolle\Deadlinks\Plugin as DeadlinksPlugin;
use Cake\Http\BaseApplication;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;

class Application extends BaseApplication
{
    public function bootstrap(): void
    {
        parent::bootstrap();

        $this->addPlugin(DeadlinksPlugin::class);
    }

    public function routes(RouteBuilder $routes): void
    {
        Router::connect('/meh', ['controller' => 'Pages', 'action' => 'home']);
    }

    /**
     * Middleware
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue Middleware Queue
     * @return \Cake\Http\MiddlewareQueue
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        return $middlewareQueue;
    }
}
