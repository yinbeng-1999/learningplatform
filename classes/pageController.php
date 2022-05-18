<?php

use Psr\Container\ContainerInterface;

class pageController
{
    protected $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    function render_index($request, $response, $args)
    {
        // 初始介面
        // session_destroy();
        $response = $this->container->view->render($response, "index.html");
        return $response;
    }
    public function getUserData($request, $response, $args)
    {
        // 取得使用者名稱
        $page = new page($this->container->db);
        $result = $page->getUserData(['user_id' => $_SESSION['id']]);
        $response = $response->withHeader("Content-type", "application/json");
        $response = $response->withJson($result);
        return $response;
    }
    public function getCourse($request, $response, $args)
    {
        // 取得各科科目
        $page = new page($this->container->db);
        $data = $request->getQueryParams();
        $result = $page->getCourse($data);
        $response = $response->withHeader("Content-type", "application/json");
        $response = $response->withJson($result);
        return $response;
    }

    public function getChat($request, $response, $args)
    {
        // 取得各科科目
        $page = new page($this->container->db);
        $result = $page->getChat();
        $response = $response->withHeader("Content-type", "application/json");
        $response = $response->withJson($result);
        return $response;
    }

    public function addChat($request, $response, $args)
    {
        //新增聊天
        $page = new page($this->container->db);
        $data = $request->getParsedbody();
        $result = $page->addChat($data);
        $response = $response->withHeader("Content-type", "application/json");
        $response = $response->withJson($result);
        return $response;
    }

    public function getAnnouncement($request, $response, $args)
    {
        //取得各科公告
        $page = new page($this->container->db);
        $data = $request->getQueryParams();
        $result = $page->getAnnouncement($data);
        $response = $response->withHeader("Content-type", "application/json");
        $response = $response->withJson($result);
        return $response;
    }

    public function addAnnouncement($request, $response, $args)
    {
        //新增公告
        $page = new page($this->container->db);
        $data = $request->getParsedbody();
        $result = $page->addAnnouncement($data);
        $response = $response->withHeader("Content-type", "application/json");
        $response = $response->withJson($result);
        return $response;
    }

    public function updateAnnouncement($request, $response, $args)
    {
        // 編輯公告
        $page = new page($this->container->db);
        $data = $request->getParsedBody();
        $result = $page->updateAnnouncement($data);
        $response = $response->withHeader("Content-type", "application/json");
        $response = $response->withJson($result);
        return $response;
    }

    public function deleteAnnouncement($request, $response, $args)
    {
        // 刪除教材區內容
        $page = new page($this->container->db);
        $data = $request->getParsedBody();
        $result = $page->deleteAnnouncement($data);
        $response = $response->withHeader("Content-type", "application/json");
        $response = $response->withJson($result);
        return $response;
    }

    public function getMaterial($request, $response, $args)
    {
        // 取得教材區內容
        $page = new page($this->container->db);
        $data = $request->getQueryParams();
        $result = $page->getMaterial($data);
        $response = $response->withHeader("Content-type", "application/json");
        $response = $response->withJson($result);
        return $response;
    }
    public function addMaterial($request, $response, $args)
    {
        // 上傳檔案
        $uploadedFiles = $request->getUploadedFiles();
        $newfile = $uploadedFiles['newfile'];
        $datas = array();
        if ($newfile->getError() === UPLOAD_ERR_OK) {
            $name = $newfile->getClientFilename();
            $whitelist = array('127.0.0.1', '::1');
            if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
                $result = $newfile->moveTo("uploads/$name");
                $datas["newfile"] = $name;
            } else {
                $result = $newfile->moveTo("uploads/$name");
                $datas["newfile"] = $name;
            }
        }
        // 新增教材區內容
        $page = new page($this->container->db);
        $data = $request->getParsedBody();
        foreach ($data as $key => $value) {
            $datas[$key] = $value;
        }
        $result = $page->addMaterial($datas);
        $response = $response->withHeader("Content-type", "application/json");
        $response = $response->withJson($result);
        return $response;
    }
    public function updateMaterial($request, $response, $args)
    {
        // 修改檔案
        $uploadedFiles = $request->getUploadedFiles();
        $newfile = $uploadedFiles['newfile'];
        $datas = array();
        if ($newfile->getError() === UPLOAD_ERR_OK) {
            $name = $newfile->getClientFilename();
            $whitelist = array('127.0.0.1', '::1');
            if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
                $result = $newfile->moveTo("uploads/$name");
                $datas["newfile"] = $name;
            } else {
                $result = $newfile->moveTo("uploads/$name");
                $datas["newfile"] = $name;
            }
            $response = $response->withHeader("Content-type", "application/json");
            $response = $response->withJson($result);
            return $response;
        }
        // 修改教材區內容
        $page = new page($this->container->db);
        $data = $request->getParsedBody();
        foreach ($data as $key => $value) {
            $datas[$key] = $value;
        }
        $result = $page->updateMaterial($datas);
        $response = $response->withHeader("Content-type", "application/json");
        $response = $response->withJson($result);
        return $response;
    }
    public function deleteMaterial($request, $response, $args)
    {
        // 刪除教材區內容
        $page = new page($this->container->db);
        $data = $request->getParsedBody();
        $result = $page->deleteMaterial($data);
        $response = $response->withHeader("Content-type", "application/json");
        $response = $response->withJson($result);
        return $response;
    }
    public function getAssessment($request, $response, $args)
    {
        // 取得評量區內容
        $page = new page($this->container->db);
        $data = $request->getQueryParams();
        $result = $page->getAssessment($data);
        $response = $response->withHeader("Content-type", "application/json");
        $response = $response->withJson($result);
        return $response;
    }
    public function deleteAssessment($request, $response, $args)
    {
        // 刪除評量區內容
        $page = new page($this->container->db);
        $data = $request->getParsedBody();
        $result = $page->deleteAssessment($data);
        $response = $response->withHeader("Content-type", "application/json");
        $response = $response->withJson($result);
        return $response;
    }
    public function addAssessment($request, $response, $args)
    {
        //新增功課
        $page = new page($this->container->db);
        $data = $request->getParsedbody();
        $result = $page->addAssessment($data);
        $response = $response->withHeader("Content-type", "application/json");
        $response = $response->withJson($result);
        return $response;
    }
    public function updateAssessment($request, $response, $args)
    {
        // 編輯功課
        $page = new page($this->container->db);
        $data = $request->getParsedBody();
        $result = $page->updateAssessment($data);
        $response = $response->withHeader("Content-type", "application/json");
        $response = $response->withJson($result);
        return $response;
    }
}
