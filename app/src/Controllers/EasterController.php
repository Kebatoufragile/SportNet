<?php

namespace App\Controllers;


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class EasterController extends AbstractController{

    protected $view;
    protected $logger;

    public function __construct($view){

        parent::__construct($view);

    }

    public function dispatch(Request $request, Response $response, $args){
      if(isset($_SESSION['user'])){
        $this->view['view']->render($response, 'easter.html.twig', array(
            'user' => $_SESSION['user']
        ));
      }else{
        $this->view['view']->render($response, 'easter.html.twig');
      }
    }
  }
