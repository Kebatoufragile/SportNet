<?php

namespace App\Controllers;

use App\Models\Event;
use App\Models\Inscription;
use App\Models\Participant;
use App\Models\Result;
use App\Models\Trial;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class CsvController extends AbstractController{

    protected $view;
    protected $logger;

    public function __construct($view){

        parent::__construct($view);

    }


    public function downloadParticipants(Request $request, Response $response, $args)
    {
        if (isset($_GET['idTrial'])) {

            $idTrial = filter_var($_GET['idTrial'], FILTER_SANITIZE_NUMBER_INT);

            $trial = Trial::where('idTrial', 'like', $idTrial)->first();

            if (!is_null($trial)) {
                $e = Event::where('idEvent', 'like', $trial->idEvent)->first();

                $name = str_replace('/', '_', $e->name) . $trial->idEvent . '_' . $trial->name . '.csv';
                $file = fopen($name, 'w');

                $inscriptions = Inscription::where('idTrial', 'like', $idTrial)->get();

                $firstline = 'idTrial;idParticipant;lastname;firstname;bib'."\n";
                fwrite($file, $firstline);

                foreach ($inscriptions as $inscription) {
                    $participant = Participant::where('idParticipant', 'like', $inscription->idParticipant)->first();
                    $line = $idTrial . ';' . $participant->idParticipant . ';' . $participant->lastname . ';' . $participant->firstname . ';' . $participant->bib."\n";
                    fwrite($file, $line);
                }

                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . basename($name) . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($name));
                readfile($name);

                fclose($file);

                exit();

            } else {

                if (isset($_SESSION['user'])) {
                    $this->view['view']->render($response, 'homepage.html.twig', array(
                        'user' => $_SESSION['user']
                    ));
                } else {
                    $this->view['view']->render($response, 'homepage.html.twig');
                }

            }
        }
    }

    public function uploadResults(Request $request, Response $response, $args){

        $e = Event::where('idEvent', 'like', $_POST['idEvent'])->first();
        $idTrial = $_POST['idTrial'];
        $trials = Trial::where('idEvent', 'like', $e->idEvent)->get();

        // if the user is logged
        if(isset($_SESSION['user'])){

            // if the user is the organizer
            if($_SESSION['user']->id == $e->idOrg) {

                // if a file is uploaded
                if (isset($_FILES['results']['tmp_name'])) {

                    $file = explode("\n", file_get_contents($_FILES['results']['tmp_name']));

                    // if file not empty
                    if (count($file) > 1) {

                        array_shift($file);

                        foreach ($file as $line) {

                            $line = explode(";", $line);

                            if(Participant::where('idParticipant', 'like', $line[0])->get()->count() > 0){

                                $r = new Result();
                                $r->rank = $line[1];
                                $r->idTrial = $idTrial;
                                $r->idParticipant = $line[0];

                                $r->save();
                            }


                        }

                        $this->view['view']->render($response, 'event.html.twig', array(
                            'event' => $e,
                            'success' => 'Results have been upload.',
                            'user' => $_SESSION['user'],
                            'trials' =>$trials
                        ));

                    } else {
                        $this->view['view']->render($response, 'event.html.twig', array(
                            'event' => $e,
                            'error' => 'File is empty',
                            'user' => $_SESSION['user'],
                            'trials' =>$trials
                        ));
                    }

                } else {

                    $this->view['view']->render($response, 'event.html.twig', array(
                        'event' => Event::where('idEvent'),
                        'user' => $_SESSION['user'],
                        'error' => 'You must choose a file.',
                        'trials' =>$trials
                    ));
                }

            }else{

                $this->view['view']->render($response, 'event.html.twig', array(
                    'event' => Event::where('idEvent'),
                    'user' => $_SESSION['user'],
                    'error' => 'You do not own the event.',
                    'trials' =>$trials
                ));
            }

        }else{
            $this->view['view']->render($response, 'event.html.twig', array(
                'event' => Event::where('idEvent'),
                'error' => 'You must be logged to perform this action.',
                'trials' =>$trials
            ));
        }

    }
}