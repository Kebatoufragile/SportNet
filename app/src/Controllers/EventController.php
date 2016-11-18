<?php

namespace App\Controllers;

use App\Models\Event;
use App\Models\Trial;
use App\Models\Result;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class EventController extends AbstractController{

    protected $view;
    protected $logger;

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
                "error" => "You must be logged to perform this action."
            ));
        }

        return $response;
    }

    public function createEvent(Request $request, Response $response, $args)
    {
        if (isset($_POST['name']) && isset($_POST['location']) && isset($_POST['discipline']) && isset($_POST['date']) && isset($_POST['description'])) {
            $e = new Event();
            $e->name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $e->location = filter_var($_POST['location'], FILTER_SANITIZE_STRING);
            $e->discipline = filter_var($_POST['discipline'], FILTER_SANITIZE_STRING);
            $e->dates = filter_var($_POST['date'], FILTER_SANITIZE_STRING);
            $e->state = "created";
            $e->description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
            $e->idOrg = $_SESSION['user']->id;
            if ($e->save() != false) {
                if (isset($_SESSION['user'])) {
                    $this->view['view']->render($response, 'event.html.twig', array(
                        'event' => $e,
                        'user' => $_SESSION['user'],
                        'success' => "Event successfully created."
                    ));
                } else {
                    $this->view["view"]->render($response, 'event.html.twig', array(
                        'event' => $e,
                        'success' => "Event successfully created."
                    ));
                }
            } else {
                $this->view['view']->render($response, 'createEvent.html.twig', array(
                    'error' => 'Error when creating event.'
                ));
            }
        } else {
            $this->view['view']->render($response, 'createEvent.html.twig', array(
                'error' => "You must fill all the forms."
            ));
        }
    }

    public function displayList(Request $request, Response $response, $args){
        $events = Event::where('state', 'not like', 'created')->get()->reverse();

        if(isset($_SESSION['user'])){

            $this->view["view"]->render($response, 'eventlist.html.twig', array(
                "events" => $events,
                "user" => $_SESSION['user']
            ));

        }else{

            $this->view["view"]->render($response, 'eventlist.html.twig', array(
                "events" => $events
            ));

        }
    }

    public function displayEventPage(Request $request, Response $response, $args){
        if(isset($_GET["idEvent"])){
            if(isset($_SESSION['user'])){
                $this->view["view"]->render($response, 'event.html.twig', array(
                    "event" => Event::where("idEvent", "like", $_GET["idEvent"])->first(),
                    "user" => $_SESSION['user'],
                    'trials' => Trial::where("idEvent", "like", $_GET['idEvent'])->get(),
                    'path' => $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'],
                    'dateEvent' => Event::where("idEvent", "like", $_GET['idEvent'])->first()->dates
                ));
            }else{
                $this->view["view"]->render($response, 'event.html.twig', array(
                    "event" => Event::where("idEvent", "like", $_GET["idEvent"])->first(),
                    'trials' => Trial::where("idEvent", "like", $_GET['idEvent'])->get(),
                    'dateEvent' => Event::where("idEvent", "like", $_GET['idEvent'])->first()->dates
                ));
            }

        }else{
            if(isset($_SESSION['user'])){
                $this->view["view"]->render($response, 'homepage.html.twig', array(
                    "error" => "Event doesn't exist.",
                    "user" => $_SESSION['user']
                ));
            }else{
                $this->view["view"]->render($response, 'homepage.html.twig', array(
                    "error" => "Event doesn't exist."
                ));
            }
        }
    }

    public function changeEventState(Request $request, Response $response, $args){

        $e = Event::where("idEvent", "like", $_POST["idEvent"])->first();

        if(isset($_SESSION['user'])){

            if($_SESSION['user']->id == $e->idOrg){

                if(!is_null($e)){

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
                                "error" => "Wrong state."
                            ));
                            break;
                    }

                    $this->view['view']->render($response, 'event.html.twig', array(
                        "success" => "Your event has been updated.",
                        'event' => $e,
                        'user' => $_SESSION['user']
                    ));

                }else{
                    $this->view['view']->render($response, 'homepage.html.twig', array(
                        'error' => 'The event dos not exist',
                        'user' => $_SESSION['user']
                    ));
                }


            }else {
                $this->view["view"]->render($response, 'event.html.twig', array(
                    "error" => "You don't own the event.",
                    'event' => $e,
                    'user' => $_SESSION['user']
                ));
            }

        }else{
            $this->view['view']->render($response, 'event.html.twig', array(
                'error' => 'You must be logged to perform this action.',
                'event' => $e
            ));
        }

    }


    public function addEventTrial(Request $request, Response $response, $args){

        $e = Event::where('idEvent', 'like', filter_var($_POST['idEvent'], FILTER_SANITIZE_NUMBER_INT))->first();

        if (isset($_POST["trialName"]) && isset($_POST['trialDate']) && isset($_POST['trialPrice']) && isset($_POST['trialDescription']) && isset($_POST['idEvent'])) {

            $t = new Trial();
            $t->name = filter_var($_POST['trialName'], FILTER_SANITIZE_STRING);
            $datetmp = explode('/', $_POST['trialDate']);
            $day = $datetmp[1];
            $month = $datetmp[0];
            $year = $datetmp[2];
            $date = $year . '-' . $month . '-' . $day;

            $t->date = $date;
            $t->price = filter_var($_POST['trialPrice'], FILTER_SANITIZE_STRING);
            $t->description = filter_var($_POST['trialDescription'], FILTER_SANITIZE_STRING);
            $t->idEvent = filter_var($_POST['idEvent'], FILTER_SANITIZE_NUMBER_INT);

            $t->save();

            $trials = Trial::where('idEvent', 'like', filter_var($_POST['idEvent'], FILTER_SANITIZE_NUMBER_INT))->get();

            $this->view["view"]->render($response, "event.html.twig", array(
                "success" => "Your event has been updated.",
                "user" => $_SESSION['user'],
                'event' => $e,
                'trials' => $trials
            ));

        } else {

            $trials = Trial::where('idEvent', 'like', filter_var($_POST['idEvent'], FILTER_SANITIZE_NUMBER_INT))->get();

            $this->view["view"]->render($response, "homepage.html.twig", array(
                "error" => "Event doesn't exist.",
                'event' => $e,
                'trials' => $trials,
                "user" => $_SESSION['user']
            ));

        }
    }


    public function simplifyURL(Request $request, Response $response, $args){
      $path = $_POST['path'];
        $r = array();
        foreach(explode('/', $path) as $p){
            if($p == '..'){
                array_pop($r);
            }elseif ($p != "." && strlen($p)) {
                $r[] = $p;
            }
        }
        $r = implode('/', $r);
        if($path[0] == '/'){
            $r = '/$r';
        }
        $this->view["view"]->render($response, 'url.html.twig', array(
          'url' => $r,
          'user' => $_SESSION['user']
        ));
    }

    public function displayResults(Request $request, Response $response, $args){

        $event=Trial::where('idTrial', '=', $_GET['idTrial'])->first();

        $this->view['view']->render($response, 'resultstrials.html.twig', array(
            'results' => Result::where('idTrial', '=', $_GET['idTrial'])->get(),
            'idTrial' => $_GET['idTrial'],
            'event' => $event->idEvent
        ));

        return $response;

    }

    /*
      $u = parse_url($dirtyUrl);
      $u['path'] = simplify($u['path']);
      $clean_url = "{$u['scheme']}://{$u['host']}{$u['path']}";
    */


}
