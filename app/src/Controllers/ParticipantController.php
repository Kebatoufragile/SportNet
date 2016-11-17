<?php

namespace App\controllers;

use App\Models\Participant;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ParticipantController extends AbstractController{

  protected $view;
  protected $logger;
  protected $sentinel;

  public function __construct($view){
    parent::__construct($view);
  }

  public function dispatch(Request $request, Response $response, $args){

      $this->view['view']->render($response, 'register.html.twig');

      return $response;

  }

}
