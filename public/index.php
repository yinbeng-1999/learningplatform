<?php
session_start();

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Slim\Http\Response as Response;

use \Slim\Http\UploadedFile;

require '../vendor/autoload.php';
require '../classes/PHPMailer/src/PHPMailer.php';
require '../classes/PHPMailer/src/Exception.php';
require '../classes/PHPMailer/src/SMTP.php';

// Create and configure Slim app
$config['debug'] = true;
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(['settings' => $config]);

$container = $app->getContainer();

$container['upload_directory'] = __DIR__ . "/../uploads/";
$container['view'] = new \Slim\Views\PhpRenderer('../html/');

$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO('pgsql:host=140.127.74.174;port=5432;dbname=postgres;', 'postgres', 'pgsql');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

//登入時的 middleware 
class loginCheck
{
    private $router;
    public function __construct($router)
    {
        $this->router = $router;
    }

    public function __invoke($request, $response, $next)
    {
        if (isset($_SESSION["id"])) {
            $response = $next($request, $response);
        } else {
            $response = $response->withRedirect('/');
        }
        return $response;
    }
}

// Define app routes
$app->group('', function () use ($app) {
    $app->get('/', \userController::class . ':render_login');
    $app->group('/login', function () use ($app) {
        $app->post('', \userController::class . ':login');
    });
    $app->group('/forgetpass', function () use ($app) {
        $app->get('', \userController::class . ':render_forgetpass');
        $app->post('/sendMail', \userController::class . ':sendMail');
    });
    $app->group('/register', function () use ($app) {
        $app->get('/render', \userController::class . ':render_register');
        $app->post('', \userController::class . ':register');
    });
    $app->group('', function () use ($app) {
        $app->group('/userRole', function () use ($app) {
            $app->get('/getRolePermission', \userController::class . ':getRolePermission');
        });
        $app->group('/billboard', function () use ($app) {
            $app->get('/data/{id}', \pageController::class . ':render_index');
            $app->get('/getCourse', \pageController::class . ':getCourse');
            $app->get('/getUserData', \pageController::class . ':getUserData');
            $app->group('/announcement', function () use ($app) {
                $app->get('', \pageController::class . ':getAnnouncement');
                $app->post('', \pageController::class . ':addAnnouncement');
                $app->patch('', \pageController::class . ':updateAnnouncement');
                $app->delete('', \pageController::class . ':deleteAnnouncement');
            });
            $app->group('/material', function () use ($app) {
                $app->get('', \pageController::class . ':getMaterial');
                $app->post('', \pageController::class . ':addMaterial');
                $app->patch('', \pageController::class . ':updateMaterial');
                $app->delete('', \pageController::class . ':deleteMaterial');
            });
            $app->group('/assessment', function () use ($app) {
                $app->get('', \pageController::class . ':getAssessment');
                $app->post('', \pageController::class . ':addAssessment');
                $app->patch('', \pageController::class . ':updateAssessment');
                $app->delete('', \pageController::class . ':deleteAssessment');
            });
            $app->group('/chatbox', function () use ($app) {
                $app->get('', \pageController::class . ':getChat');
                $app->post('', \pageController::class . ':addChat');
            });
        });
        $app->group('/mainpage', function () use ($app) {
            $app->get('/data/{id}', \mainpageController::class . ':render_personpage');
            $app->get('/getYear', \mainpageController::class . ':getYear');
            $app->get('/getSemester', \mainpageController::class . ':getSemester');
            $app->get('/getChart', \mainpageController::class . ':getChart');
            $app->get('/getSchedule', \mainpageController::class . ':getSchedule');
            $app->patch('/patchUserData', \mainpageController::class . ':patchUserData');
            $app->post('/patchUserPhoto', \mainpageController::class . ':patchUserPhoto');
        });
        $app->group('/sem_intro', function () use ($app) {
            $app->get('/data/{id}', \sem_introController::class . ':render_intro');
        });
        $app->group('/start_class', function () use ($app) {
            $app->get('/data/{id}', \start_classController::class . ':render_class');
        });
        $app->group('/usermanagement', function () use ($app) {
            $app->get('/data/{id}', \usermanagementController::class . ':render_usermanagement');
            $app->get('/getRole', \usermanagementController::class . ':getRole');
            $app->get('', \usermanagementController::class . ':getUserManagement');
            $app->post('', \usermanagementController::class . ':addUserManagement');
            $app->patch('', \usermanagementController::class . ':patchUserManagement');
            $app->delete('', \usermanagementController::class . ':deleteUserManagement');
        });
    })->add("loginCheck");

    // $app->get('/page', function (Request $request, Response $response, $args) {
    //     $response = $this->view->render($response, 'page.html');
    //     return $response;
    // });
});

// Run app
$app->run();
