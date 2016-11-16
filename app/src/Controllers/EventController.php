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
      $this->view['view']->render($response, 'createEvent.html.twig', array(
          "user" => $_SESSION['user']
      ));
    }else{
      $this->view['view']->render($response, 'login.html.twig', array(
          "error" => "You must be log to perform this action"
      ));
    }

    return $response;
  }

  public function createEvent(Request $request, Response $response, $args){
    if(isset($_POST['name']) && isset($_POST['location']) && isset($_POST['discipline']) && isset($_POST['date']) && isset($_POST['description'])){
      $e = new Event();
      $e->name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
      $e->location = filter_var($_POST['location'], FILTER_SANITIZE_STRING);
      $e->discipline = filter_var($_POST['discipline'], FILTER_SANITIZE_STRING);
      $e->dates = filter_var($_POST['date'], FILTER_SANITIZE_STRING);
      $e->state = "created";
      $e->description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
      $e->idOrg = $_SESSION['user']->id;

      $e->save();
    }else{
      echo "nop";
    }
  }

  public function displayList(Request $request, Response $response, $args){
    $events = Event::where('state', 'not like', 'created')->get()->reverse();

    if(isset($_SESSION['user'])){
      $this->view['view']->render($response, 'eventlist.html.twig', array(
          'events' => $events,
          'user' => $_SESSION['user']
      ));
    }else{
      $this->view['view']->render($response, 'eventlist.html.twig', array(
          'events' => $events
      ));
    }

  }

  public function displayEventPage(Request $request, Response $response, $args){
    if(isset($_GET["idEvent"])){
      if(isset($_SESSION['user'])){
        $this->view["view"]->render($response, 'event.html.twig', array(
          "event" => Event::where("idEvent", "like", $_GET["idEvent"])->first(),
          "user" => $_SESSION['user']
        ));
      }else{
        $this->view["view"]->render($response, 'event.html.twig', array(
          "event" => Event::where("idEvent", "like", $_GET["idEvent"])->first(),
        ));
      }

    }else{
      if(isset($_SESSION['user'])){
        $this->view["view"]->render($response, 'homepage.html.twig', array(
            "error" => "Event doesn't exist",
            "user" => $_SESSION['user']
        ));
      }else{
        $this->view["view"]->render($response, 'homepage.html.twig', array(
            "error" => "Event doesn't exist"
        ));
      }
    }
  }

  public function changeEventState(Request $request, Response $response, $args){
    if(isset($_POST["state"]) && isset($_POST["idEvent"])){
      $e = Event::where("idEvent", "like", $_POST["idEvent"])->first();
      switch ($_POST["state"]) {
        case 'open':
          $e->state = "open";
          $e->save();
          break;
        case 'closed':
          $e->state = "closed";
          $e->save();
          break;
        case 'finish':
          $e->state = "finish";
          $e->save();
          break;
        default:
          $this->view["view"]->render($response, "event.html.twig", array(
            "error" => "Wrong state"
          ));
          break;
      }
    }else{
      $this->view["view"]->render($response, 'homepage.html.twig', array(
        "error" => "Event doesn't exist"
      ));
    }
  }

}
