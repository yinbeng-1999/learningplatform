<?php

use Psr\Container\ContainerInterface;

class mainpageController
{
    protected $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    function render_personpage($request, $response, $args)
    {
        // 個人主頁介面
        // session_destroy();
        $response = $this->container->view->render($response, "mainpage.html");
        return $response;
    }
    public function getYear($request, $response, $args)
    {
        // 取得年份
        $mainpage = new mainpage($this->container->db);
        $result = $mainpage->getYear();
        $response = $response->withHeader("Content-type", "application/json");
        $response = $response->withJson($result);
        return $response;
    }
    public function getSemester($request, $response, $args)
    {
        // 取得學期
        $mainpage = new mainpage($this->container->db);
        $result = $mainpage->getSemester();
        $response = $response->withHeader("Content-type", "application/json");
        $response = $response->withJson($result);
        return $response;
    }
    public function getChart($request, $response, $args)
    {
        // 取得圖表
        $data = $request->getQueryParams();
        $mainpage = new mainpage($this->container->db);
        $result = $mainpage->getChart($data);
        $response = $response->withHeader("Content-type", "application/json");
        $response = $response->withJson($result);
        return $response;
    }
    public function getSchedule($request, $response, $args)
    {
        // 取得圖表
        $data = $request->getQueryParams();
        $mainpage = new mainpage($this->container->db);
        $result = $mainpage->getSchedule($data);
        $response = $response->withHeader("Content-type", "application/json");
        $response = $response->withJson($result);
        return $response;
    }
    public function patchUserData($request, $response, $args)
    {
        // 取得圖表
        $data = $request->getParsedBody();
        $mainpage = new mainpage($this->container->db);
        if (isset($_SESSION["id"])) {
            $data += ["user_id" => $_SESSION["id"]];
            $result = $mainpage->patchUserData($data);
        } else {
            $result = ["status" => "failed", "message" => "使用者編輯錯誤"];
            $response = $response->withStatus(500);
        }
        $response = $response->withHeader("Content-type", "application/json");
        $response = $response->withJson($result);
        return $response;
    }
    public function patchUserPhoto($request, $response, $args)
    {
        $uploadedFiles = $request->getUploadedFiles();
        $newfile = $uploadedFiles['picture'];
        $datas = array();
        if ($newfile->getError() === UPLOAD_ERR_OK) {
            $name = $newfile->getClientFilename();
            $whitelist = array('127.0.0.1', '::1');
            if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
                $result = $newfile->moveTo("uploads/$name");
                $datas["picture"] = $name;
            } else {
                $result = $newfile->moveTo("uploads/$name");
                $datas["picture"] = $name;
            }
        }
    }
}
