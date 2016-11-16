<?php

namespace App\Controllers;

use App\Models\User;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Cartalyst\Sentinel\Users\UserInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


class LoginController extends AbstractController{

    protected $view;
    protected $logger;
    private $sentinel;

    public function __construct($view){

        parent::__construct($view);
        $this->sentinel = new Sentinel();
        $this->sentinel = $this->sentinel->getSentinel();

    }

    public function renderForm(Request $request, Response $response, $args){
        // if user already logged
        if(isset($_SESSION['user']))
            return $this->view['view']->render($response, 'homepage.html.twig');
        else
            return $this->view['view']->render($response, 'login.html.twig');

    }


    public function authenticateUser(Request $request, Response $response, $args){

        // if informations are filled
        if(isset($_POST['username']) && isset($_POST['password'])){

            $credentials = [
                'email' => filter_var($_POST['username'], FILTER_SANITIZE_EMAIL),
                'password' => filter_var($_POST['password'], FILTER_SANITIZE_STRING)
            ];

            $userInterface = $this->sentinel->authenticate($credentials);

            // if login worked
            if($userInterface instanceof userInterface){
                $this->sentinel->login($userInterface, true);

                $u = User::where('id', "like", $userInterface->getUserId())->first();

                $_SESSION["userid"] = $userInterface->getUserId();
                $_SESSION["user"] = $u;

                return $this->view['view']->render($response, 'homepage.html.twig', array(
                    'success' => 'You have been successfully logged.',
                    'user' => $_SESSION['user']
                ));

            }else{

                return $this->view['view']->render($response, 'login.html.twig', array(
                    'error' => 'Unable to log you, check your email address and your password, then try again.'
                ));

            }

        }else{

            return $this->view['view']->render($response, 'login.html.twig', array(
                'error' => 'Unable to log you, fields are missing, please try again.'
            ));

        }

    }


}
