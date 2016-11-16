<?php

namespace App\Controllers;

use App\Models\Event;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class EventController extends AbstractController{

  protected $view;
  protected $logger;
  private $sentinel;

  public function __construct($view){

      parent::__construct($view);

  }

  public function dispatch(Request $request, Response $response, $args){
    if(isset($_SESSION['user'])){
      $this->view['view']->render($response, 'createEvent.html.twig', array());
    }else{
      $this->view['view']->render($response, 'register.html.twig', array());
    }

    return $response;
  }

  public function createEvent(Request $request, Response $response, $args){
      if(isset($_POST['name']) && isset($_POST['location']) && isset($_POST['discipline']) && isset($_POST['date']) && isset($_POST['state']) && isset($_POST['description'])){
        $e = new Event();
        $e->name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $e->location = filter_var($_POST['location'], FILTER_SANITIZE_STRING);
        $e->discipline = filter_var($_POST['discipline'], FILTER_SANITIZE_STRING);
        $e->dates = filter_var($_POST['date'], FILTER_SANITIZE_STRING);
        $e->state = "created";
        $e->description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
        $e->idOrg = $_SESSION['user']->id;

        $e->save();
      }
  }

}
