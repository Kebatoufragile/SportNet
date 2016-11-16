<?php

namespace App\Controllers;

use App\Models\Event;
use App\Models\User;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


final class ResearchController extends AbstractController
{

    protected $view;
    protected $logger;

    public function __construct($view){

        parent::__construct($view);

    }

    public function dispatch(Request $request, Response $response, $args){

      if(isset($_POST['search'])){
        $search = '%'.filter_var($_POST['search'], FILTER_SANITIZE_STRING).'%';
        $events = Event::where('name', 'like', $search)
                        ->where('state', '!=', 'created')
                        ->get();

        if(isset($_SESSION['user'])){
            $this->view['view']->render($response, 'eventlist.html.twig', array(
                'user' => $_SESSION['user'],
                'events' => $events
            ));
        }else{
            $this->view['view']->render($response, 'eventlist.html.twig', array(
                'events' => $events
            ));
        }
    }else
        header('Location: index.php');

    return $response;

    }
}
