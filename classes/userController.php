<?php

use Psr\Container\ContainerInterface;

class userController
{
    protected $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    function render_login($request, $response, $args)
    {
        // 登入介面
        session_destroy();
        $response = $this->container->view->render($response, "login.html");
        return $response;
    }
    function login($request, $response, $args)
    {
        // login 取得登入者 id 
        $data = $request->getParsedBody();
        $user = new user($this->container->db);
        $result = $user->login($data);
        $response = $response->withHeader("Content-type", "application/json");
        $response = $response->withJson($result);
        return $response;
    }
    function render_register($request, $response, $args)
    {
        // render 註冊介面
        session_destroy();
        $response = $this->container->view->render($response, "register.html");
        return $response;
    }
    function register($request, $response, $args)
    {
        // 註冊
        $data = $request->getParsedBody();
        $user = new user($this->container->db);
        $usermanagement = new usermanagement($this->container->db);
        $result = $user->login($data);
        if ($result != NUll) {
            $result = ["status" => "failed"];
        } else {
            $result = $user->register($data);
            if (isset($result["user_id"])) {
                $data += ["user_id" => $result["user_id"]];
                $result = $usermanagement->addUserRole($data);
            } else {
                $result = ["status" => "failed", "message" => "新增使用者權限失敗"];
                $response = $response->withStatus(500);
            }
            if ($data["role_id"] == '2') {
                $result = $usermanagement->addTeachRole($data);
            } else if ($data["role_id"] == '3') {
                $result = $usermanagement->addStuRole($data);
            }
        }
        $response = $response->withHeader("Content-type", "application/json");
        $response = $response->withJson($result);
        return $response;
    }
    function render_forgetpass($request, $response, $args)
    {
        // render 忘記密碼介面
        session_destroy();
        $response = $this->container->view->render($response, "forgetpassword.html");
        return $response;
    }
    function sendMail($request, $response, $args)
    {
        // 寄信
        $data = $request->getParsedBody();
        $user = new user($this->container->db);
        $result = $user->getPassword($data);
        if ($result == NULL) {
            $result = ["status" => "failed"];
            $response = $response->withStatus(500);
        } else {
            $pass = $result[0]["password"];
            $data += ['password' => $pass];
            $result = $user->sendMail($data);
        }
        $response = $response->withHeader("Content-type", "application/json");
        $response = $response->withJson($result);
        return $response;
    }
    function getRolePermission($request, $response, $args)
    {
        // 取得使用者權限
        $data = $request->getQueryParams();
        $user = new user($this->container->db);
        $result = $user->getRolePermission($data);
        $response = $response->withHeader("Content-type", "application/json");
        $response = $response->withJson($result);
        return $response;
    }
}
