<?php
declare(strict_types=1);

namespace TestApp;

use Avolle\Deadlinks\DeadlinksPlugin;
use Cake\Http\BaseApplication;
use Cake\Http\MiddlewareQueue;

class Application extends BaseApplication
{
    public function bootstrap(): void
    {
        parent::bootstrap();

        $this->addPlugin(DeadlinksPlugin::class);
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
