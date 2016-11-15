<?php

namespace App\Controllers;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class HomeController extends AbstractController{

    protected $view;

    public function __construct($view) {

        parent::__construct($view);

    }


    public function dispatch(Request $request, Response $response, $args){

        if(isset($_SESSION['user'])){

            return $this->view->render($response, 'homepage.html.twig', array(
                'user' => $_SESSION['user']
            ));

        }else
            return $this->view->render($response, 'homepage.html.twig');

    }
}