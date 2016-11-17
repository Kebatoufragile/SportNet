<?php

namespace App\Controllers;

use App\Models\Participant;
use App\Models\Inscription;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ParticipantController extends AbstractController{

  protected $view;
  protected $logger;

  public function __construct($view){
    parent::__construct($view);
  }

  public function dispatch(Request $request, Response $response, $args){

      $this->view['view']->render($response, 'participant.html.twig', array(
        'idTrial' => $_GET['idTrial']
      ));

      return $response;

  }

  public function registerParticipant(){

    if (isset($_POST['lastNameParticipant']) && isset($_POST['firstNameParticipant']) && isset($_POST['emailParticipant']) && isset($_POST['ageParticipant'])){

        $p = new Participant();
        $p->lastname = filter_var($_POST['lastNameParticipant'], FILTER_SANITIZE_STRING);
        $p->firstname = filter_var($_POST['firstNameParticipant'], FILTER_SANITIZE_STRING);
        $p->mail = filter_var($_POST['emailParticipant'], FILTER_SANITIZE_EMAIL);
        $p->age = filter_var($_POST['ageParticipant'], FILTER_SANITIZE_STRING);
        $dossardcount = Inscription::where('idTrial', '=', $_GET['idTrial'])->count();
        $p->bib = $dossardcount+1;

        $p->save();

        $i = new Inscription();
        $i->idParticipant = $p->idParticipant;
        $i->idTrial = $_GET['idTrial'];
        $i->save();

        $_SESSION['id'] = $p->idParticipant;
        return 4;

    } else {
      return 2;//information manquante
    }

  }

  public function dispatchSubmit(Request $request, Response $response, $args){

    $res=$this->registerParticipant();

    $p=Participant::where('idParticipant', '=', $_SESSION['id'])->first();

    switch($res) {
        case 2:
            $this->view['view']->render($response, 'participant.html.twig', array(
                'error' => 'Unable to register you, informations are missing, please try again.'
            ));
            break;
        case 4:
            $this->view['view']->render($response, 'submit.html.twig', array(
                'success' => 'You have been successfully registered.',
                'dossard' => $p->bib,
                'numP' => $p->idParticipant
            ));
            break;
        default:
            $this->view['view']->render($response, 'participant.html.twig', array(
                'error' => 'Unable to register you, informations are wrong, please try again.'
            ));
            break;
    }

    return $response;

  }

}
