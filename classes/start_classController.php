<?php

use Psr\Container\ContainerInterface;

class start_classController
{
    protected $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    function render_class($request, $response, $args)
    {
        // 初始介面
        // session_destroy();
        $response = $this->container->view->render($response, "start_class.html");
        return $response;
    }
}
