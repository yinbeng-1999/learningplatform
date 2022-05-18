<?php

use Psr\Container\ContainerInterface;

class usermanagementController
{
    protected $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    function render_usermanagement($request, $response, $args)
    {
        // 用戶介面
        // session_destroy();
        $response = $this->container->view->render($response, "usermanagement.html");
        return $response;
    }
    public function getUserManagement($request, $response, $args)
    {
        // 取得用戶
        $usermanagement = new usermanagement($this->container->db);
        $data = $request->getQueryParams();
        $result = $usermanagement->getUserManagement($data);
        $response = $response->withHeader("Content-type", "application/json");
        $response = $response->withJson($result);
        return $response;
    }
    public function getRole($request, $response, $args)
    {
        // 取得權限
        $usermanagement = new usermanagement($this->container->db);
        $data = $request->getQueryParams();
        $result = $usermanagement->getRole($data);
        $response = $response->withHeader("Content-type", "application/json");
        $response = $response->withJson($result);
        return $response;
    }
    public function addUserManagement($request, $response, $args)
    {
        //新增用戶
        $usermanagement = new usermanagement($this->container->db);
        $data = $request->getParsedBody();
        $result = $usermanagement->addUserManagement($data);
        $data += ["user_id" => $result["user_id"]];
        $result = $usermanagement->addUserRole($data);
        $response = $response->withHeader("Content-type", "application/json");
        $response = $response->withJson($result);
        return $response;
    }
    public function patchUserManagement($request, $response, $args)
    {
        // 編輯用戶
        $usermanagement = new usermanagement($this->container->db);
        $data = $request->getParsedBody();
        $result = $usermanagement->patchUserManagement($data);
        $result = $usermanagement->patchUserRole($data);
        $response = $response->withHeader("Content-type", "application/json");
        $response = $response->withJson($result);
        return $response;
    }
    public function deleteUserManagement($request, $response, $args)
    {
        // 刪除用戶
        $usermanagement = new usermanagement($this->container->db);
        $data = $request->getParsedBody();
        $result = $usermanagement->deleteUserManagement($data);
        $response = $response->withHeader("Content-type", "application/json");
        $response = $response->withJson($result);
        return $response;
    }
}
