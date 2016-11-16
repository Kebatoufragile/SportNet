<?php

namespace App\Controllers;

use App\Models\Event;
use App\Models\User;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ProfileController extends AbstractController{

    protected $view;
    protected $logger;

    public function __construct($view){

        parent::__construct($view);

    }

    public function displayProfile(Request $request, Response $response, $args){

        if(isset($_SESSION['user'])){

            $events = Event::where('idOrg', 'like', $_SESSION['user']->id)->get();

            $this->view['view']->render($response, 'profile.html.twig', array(
                'user' => $_SESSION['user'],
                'events' => $events
            ));
        }else{
            $this->view['view']->render($response, 'homepage.html.twig', array(
                'error' => 'You must be logged to perform this action.'
            ));
        }

    }

    public function modifyProfile(Request $request, Response $response, $args){

        // if user logged
        if(isset($_SESSION['user'])){
            $events = Event::where('idOrg', 'like', $_SESSION['user']->id)->get();

            // if informations are filled
            if(isset($_POST['emailProfile']) && isset($_POST['firstnameProfile']) && isset($_POST['lastnameProfile'])){

                $u = $_SESSION['user'];

                if(strcmp($_POST['emailProfile'], $u->email) != 0){
                    if(!is_null(User::where('email', 'like', $_POST['emailProfile'])->first())){
                        return $this->view['view']->render($response, 'profile.html.twig', array(
                            'user' => $_SESSION['user'],
                            'error' => 'The mail address already exists.',
                            'events' => $events
                        ));
                    }else
                        $u->email = filter_var($_POST['emailProfile'], FILTER_SANITIZE_EMAIL);
                }
                $u->first_name = filter_var($_POST['firstnameProfile'], FILTER_SANITIZE_STRING);
                $u->last_name = filter_var($_POST['lastnameProfile'], FILTER_SANITIZE_STRING);
                $u->save();

                $_SESSION['user'] = User::where("id", "like", $_SESSION['userid'])->first();

                $this->view['view']->render($response, 'profile.html.twig', array(
                    'user' => $_SESSION['user'],
                    'events' => $events,
                    'success' => 'Your profile has been updated.'
                ));

            }else{

                $this->view['view']->render($response, 'profile.html.twig', array(
                    'user' => $_SESSION['user'],
                    'events' => $events,
                    'error' => 'Informations are missing.'
                ));

            }

        }else
            header('Location: index.php');

    }


    public function modifyPassword(Request $request, Response $response, $args){
        // if user is logged
        if(isset($_SESSION['user'])) {

            $events = Event::where('idOrg', 'like', $_SESSION['user']->id)->get();

            // if no informations missing
            if(isset($_POST['actualPassword']) && isset($_POST['newPassword']) && isset($_POST['newPasswordConf'])){

                // if actual password matching
                if(password_verify(filter_var($_POST['actualPassword'], FILTER_SANITIZE_STRING), $_SESSION['user']->password)){

                    // if new password and its confirmation are matching
                    if(filter_var($_POST['newPassword'], FILTER_SANITIZE_STRING) === filter_var($_POST['newPasswordConf'], FILTER_SANITIZE_STRING)){

                        $new = password_hash(filter_var($_POST['newPassword'], FILTER_SANITIZE_STRING), PASSWORD_BCRYPT);
                        $u = $_SESSION['user'];
                        $u->password = $new;
                        $u->save();

                        $_SESSION['user'] = User::where("id", "like", $_SESSION['userid'])->first();

                        $this->view['view']->render($response, 'profile.html.twig', array(
                            'user' => $_SESSION['user'],
                            'events' => $events,
                            'success' => 'Your password has been updated.'
                        ));

                    }else{

                        $this->view['view']->render($response, 'profile.html.twig', array(
                            'user' => $_SESSION['user'],
                            'events' => $events,
                            'error' => 'Your new password and its confirmation are not matching.'
                        ));

                    }

                }else{

                    // password not matching
                    $this->view['view']->render($response, 'profile.html.twig', array(
                        'events' => $events,
                        'user' => $_SESSION['user'],
                        'error' => 'The password you entered does not match with your actual password.'
                    ));
                }

            }

        }else
            header('Location: index.php');

    }
}