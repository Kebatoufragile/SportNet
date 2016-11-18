<?php

namespace App\Controllers;

use App\Models\Event;
use App\Models\Participant;
use App\Models\Inscription;
use App\Models\Result;
use App\Models\Trial;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ParticipantController extends AbstractController{

    protected $view;
    protected $logger;

    public function __construct($view){
        parent::__construct($view);
    }

    public function dispatch(Request $request, Response $response, $args){
      if (isset($_SESSION['user'])){
        $this->view['view']->render($response, 'participant.html.twig', array(
            'idTrial' => $_GET['idTrial'],
            'user' => $_SESSION['user']
        ));
      } else {
        $this->view['view']->render($response, 'participant.html.twig', array(
            'idTrial' => $_GET['idTrial']
        ));
      }

        return $response;

    }

    public function registerParticipant(){

        if (isset($_POST['lastNameParticipant']) && isset($_POST['firstNameParticipant']) && isset($_POST['emailParticipant']) && isset($_POST['ageParticipant'])){

            $p = new Participant();
            $p->lastname = filter_var($_POST['lastNameParticipant'], FILTER_SANITIZE_STRING);
            $p->firstname = filter_var($_POST['firstNameParticipant'], FILTER_SANITIZE_STRING);
            $p->mail = filter_var($_POST['emailParticipant'], FILTER_SANITIZE_EMAIL);
            $p->age = filter_var($_POST['ageParticipant'], FILTER_SANITIZE_NUMBER_INT);
            $dossardcount = Inscription::where('idTrial', '=', $_GET['idTrial'])->count();
            $p->bib = $dossardcount+1;

            $_SESSION['participant']=$p;
            //$p->save();

            $i = new Inscription();
            $i->idTrial = $_GET['idTrial'];
            $_SESSION['inscription']=$i;
            //$i->save();

            $_SESSION['id'] = $p->idParticipant;
            return 4;

        } else {
            return 2;//information manquante
        }

    }

    public function dispatchSubmit(Request $request, Response $response, $args){

        $res=$this->registerParticipant();
        $e=Trial::where('idTrial', '=', $_SESSION['inscription']->idTrial)->first();

        if(isset($_SESSION['user'])){
          switch($res) {
              case 2:
                  $this->view['view']->render($response, 'participant.html.twig', array(
                      'error' => 'Unable to register you, informations are missing, please try again.',
                      'user' => $_SESSION['user']
                  ));
                  break;
              case 4:
                  $this->view['view']->render($response, 'submit.html.twig', array(
                      'success' => 'You have been successfully registered.',
                      'dossard' => $_SESSION['participant']->bib,
                      'numP' => $_SESSION['participant']->idParticipant,
                      'event' => $e->idEvent,
                      'user' => $_SESSION['user']
                  ));
                  break;
              default:
                  $this->view['view']->render($response, 'participant.html.twig', array(
                      'error' => 'Unable to register you, informations are wrong, please try again.',
                      'user' => $_SESSION['user']
                  ));
                  break;
          }
        } else {
          switch($res) {
              case 2:
                  $this->view['view']->render($response, 'participant.html.twig', array(
                      'error' => 'Unable to register you, informations are missing, please try again.'
                  ));
                  break;
              case 4:
                  $this->view['view']->render($response, 'submit.html.twig', array(
                      'success' => 'You have been successfully registered.',
                      'dossard' => $_SESSION['participant']->bib,
                      'numP' => $_SESSION['participant']->idParticipant,
                      'event' => $e->idEvent
                  ));
                  break;
              default:
                  $this->view['view']->render($response, 'participant.html.twig', array(
                      'error' => 'Unable to register you, informations are wrong, please try again.'
                  ));
                  break;
          }
        }
        return $response;
    }

    public function dispatchPayment(Request $request, Response $response, $args){

      if (isset($_SESSION['participant']) && isset($_SESSION['inscription'])){
        $_SESSION['participant']->save();
        $_SESSION['inscription']->idParticipant = $_SESSION['participant']->idParticipant;
        $_SESSION['inscription']->save();
        $p=$_SESSION['participant'];
      }

      if(isset($_GET["idEvent"])){
          if(isset($_SESSION['user'])){
              $this->view["view"]->render($response, 'event.html.twig', array(
                  "event" => Event::where("idEvent", "like", $_GET["idEvent"])->first(),
                  "user" => $_SESSION['user'],
                  'trials' => Trial::where("idEvent", "like", $_GET['idEvent'])->get(),
                  'path' => $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'],
                  'idparticipant' => $p->idParticipant,
                  'dossard' => $p->bib,
                  'dateEvent' => Event::where("idEvent", "like", $_GET['idEvent'])->first()->dates
              ));
          }else{
              $this->view["view"]->render($response, 'event.html.twig', array(
                  "event" => Event::where("idEvent", "like", $_GET["idEvent"])->first(),
                  'trials' => Trial::where("idEvent", "like", $_GET['idEvent'])->get(),
                  'idparticipant' => $p->idParticipant,
                  'dossard' => $p->bib,
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
      return $response;
    }


    public function findResults(Request $request, Response $response, $args){

        if(isset($_GET['idParticipant'])){

            $idParticipant = filter_var($_GET['idParticipant'], FILTER_SANITIZE_NUMBER_INT);

            // if results
            if(Result::where('idParticipant', 'like', $idParticipant)->get()->count() > 0){

                $r = Result::where('idParticipant', 'like', $idParticipant)->get();

                $results = array();

                foreach($r as $result){

                    $trial = Trial::where('idTrial', 'like', $result->idTrial)->first();
                    $result->trialname = $trial->name;
                    $event = Event::where('idEvent', 'like', $trial->idEvent)->first();
                    $result->eventname = $event->name;
                    $participant = Participant::where('idParticipant', 'like', $idParticipant)->first();
                    $result->lastname = $participant->lastname;
                    $result->firstname = $participant->firstname;

                    $results[] = $result;

                }

                if(isset($_SESSION['user'])){
                    $this->view['view']->render($response, 'seeresults.html.twig', array(
                        'user' => $_SESSION['user'],
                        'results' => $results
                    ));
                }else{
                    $this->view['view']->render($response, 'seeresults.html.twig', array(
                        'results' => $results
                    ));
                }

            }else{

                if(isset($_SESSION['user'])){
                    $this->view['view']->render($response, 'seeresults.html.twig', array(
                        'user' => $_SESSION['user'],
                        'error' => 'No results to display.'
                    ));
                }else{
                    $this->view['view']->render($response, 'seeresults.html.twig', array(
                        'error' => 'No results to display.'
                    ));
                }

            }

        }else{

            if(isset($_SESSION['user'])){
                $this->view['view']->render($response, 'seeresults.html.twig', array(
                    'user' => $_SESSION['user'],
                    'error' => 'You must enter a participant number.'
                ));
            }else{
                $this->view['view']->render($response, 'seeresults.html.twig', array(
                    'error' => 'You must enter a participant number.'
                ));
            }

        }
    }

}
