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

        $this->view['view']->render($response, 'participant.html.twig', array(
            'results' => Result::where('idTrial', '=', $_GET['idTrial'])->get();
        ));

        return $response;

    }

}
