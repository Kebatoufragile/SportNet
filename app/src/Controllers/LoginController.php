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

    public function form(Request $request, Response $response, $args){
      if(isset($_SESSION['user'])){
        $res = $this->authenticateUser();

        if($res == 1){
            $this->view['view']->render($response, 'homepage.html.twig', array(
                'error' => 'Unable to log you, check your email address and your password, then try again.'
            ));
        }elseif($res == 1){
            $this->view['view']->render($response, 'homepage.html.twig', array(
                'error' => 'Unable to log you, fields are missing, please try again.'
            ));
        }else{
            $this->view['view']->render($response, 'homepage.html.twig', array(
                'user' => $_SESSION['user'],
                'success' => 'You have been successfully logged.',
            ));
      }
    }else{
        $res = $this->authenticateUser();

        if($res == 1){
            $this->view['view']->render($response, 'login.html.twig', array(
                'error' => 'Unable to log you, check your email address and your password, then try again.'
            ));
        }elseif($res == 1){
            $this->view['view']->render($response, 'login.html.twig', array(
                'error' => 'Unable to log you, fields are missing, please try again.'
            ));
        }else{
            $this->view['view']->render($response, 'login.html.twig', array(
                'success' => 'You have been successfully logged.',
            ));
      }
    }

      return $response;
    }


    public function authenticateUser(Request $request, Response $response, $args){

      if(isset($_POST['username']) && isset($_POST['password'])){

        $credentials = [
          'email' => filter_var($_POST['username'], FILTER_SANITIZE_STRING),
          'password' => filter_var($_POST['password'], FILTER_SANITIZE_EMAIL)
        ];

        $userInterface = $this->sentinel->authenticate($credentials);

        if($userInterface instanceof userInterface){
          $this->sentinel->login($userInterface, true);

          $u = User::where('id', "like", $userInterface->getUserId())->first();

          $_SESSION["userid"] = $userInterface->getUserId();
          $_SESSION["user"] = $u;
          $this->view['view']->render($response, 'homepage.html.twig', array(
              'error' => 'You have been successfully logged.',
          ));

        }else{
          return 1;
        }
      }else{
        return 2;
      }
    }


}
