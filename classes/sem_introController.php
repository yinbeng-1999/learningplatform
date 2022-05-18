<?php

use Psr\Container\ContainerInterface;

class sem_introController
{
    protected $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    function render_intro($request, $response, $args)
    {
        // 初始介面
        // session_destroy();
        $response = $this->container->view->render($response, "sem_intro.html");
        return $response;
    }
   
}
