<?php

namespace App\controllers;

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

      $this->view['view']->render($response, 'register.html.twig');

      return $response;

  }

  public function registerParticipant(){

    if (isset($_POST['lastNameParticipant']) && isset($_POST['firstNameParticipant']) && isset($_POST['emailParticipant']) && isset($_POST['birthdayParticipant'])){

      if (is_null(Participant::where('email', 'like', $_POST['emailParticipant']))){

        $p = new Particpant();
        $p->lastname = filter_var($_POST['lastNameParticipant'], FILTER_SANITIZE_STRING);
        $p->firstname = filter_var($_POST['firstNameParticipant'], FILTER_SANITIZE_STRING);
        $p->mail = filter_var($_POST['emailParticipant'], FILTER_SANITIZE_EMAIL);
        $p->birthdate = filter_var($_POST['birthdayParticipant'], FILTER_SANITIZE_STRING);

        $p->save();
        return 4;

      } else {
        return 3; //email manquant
      }

    } else {
      return 2;//information manquante
    }

  }

  public function dispatchSubmit(Request $request, Response $response, $args){

    $res=$this->register();

    switch($res) {
        case 2:
            $this->view['view']->render($response, 'participant.html.twig', array(
                'error' => 'Unable to register you, informations are missing, please try again.'
            ));
            break;
        case 3:
            $this->view['view']->render($response, 'login.html.twig', array(
                'success' => "You have been successfully registered."
            ));
            break;
        case 4:
            $this->view['view']->render($response, 'participant.html.twig', array(
                'error' => 'Mail address already used.'
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
