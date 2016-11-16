<?php

namespace App\Controllers;

//require '../vendor/autoload.php';
use App\Models\Pictures;
use App\Models\User;
use Illuminate\Database\Capsule\Manager;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Cartalyst\Sentinel\Users\UserInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

define('TARGET', "../public/assets/img/avatars/");
define('MAX_SIZE', 2000000);
define('WIDTH_MAX', 1500);
define('HEIGHT_MAX', 1500);

final class RegisterController extends AbstractController
{

    protected $view;
    protected $logger;
    private $sentinel;

    public function __construct($view){

        parent::__construct($view);
        $this->sentinel = new Sentinel();
        $this->sentinel = $this->sentinel->getSentinel();

    }

    public function dispatch(Request $request, Response $response, $args){

        $this->view['view']->render($response, 'register.html.twig');

        return $response;

    }

    public function register(){

        if (isset($_POST['username']) && isset($_POST['mdp']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email'])) {

            $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
            $mdp = filter_var($_POST['mdp'], FILTER_SANITIZE_STRING);
            $mdp_confirmation = filter_var($_POST['mdp_confirmation'], FILTER_SANITIZE_STRING);
            $ln = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
            $fn = filter_var($_POST['prenom'], FILTER_SANITIZE_STRING);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

            if(is_null(User::where('email', 'like', $email)->first())){

                if(is_null(User::where('username', 'like', $username)->first())){
                  if(!strcmp($mdp, $mdp_confirmation)){
                    if($username && $mdp && $ln && $fn && $email){
                        $credentials = [
                            'password' => $mdp,
                            'last_name' => $ln,
                            'first_name' => $fn,
                            'email' => $email
                            //date de naissance ?
                        ];

                        $this->sentinel->registerAndActivate($credentials);

                        // profile pic
                        if(!empty($_FILES['profilepic']['name']))
                            $profilepic = $this->upload($username);
                        else
                            $profilepic = 'default';

                        $u=User::where('email', 'like', $email)->first();
                        $u->username=$username;

                        if(strcmp($profilepic, "default") !== 0)
                            $u->profilePic = $profilepic;
                        $u->save();
                        return 3;

                    } else {
                        return 1;
                        //echo '<script>alert("Les données ne sont pas bonnes")';
                    }
                  } else {
                    return 6;
                  }
                }else
                    return 5;

            }else
                return 4;


        } else {
            return 2;
            //echo '<script>alert("il manque des données chef")';
        }

    }

    public function dispatchSubmit(Request $request, Response $response, $args){

        $res = $this->register();

        switch($res) {
            case 2:
                $this->view['view']->render($response, 'register.html.twig', array(
                    'error' => 'Unable to register you, informations are missing, please try again.'
                ));
                break;
            case 3:
                $this->view['view']->render($response, 'login.html.twig', array(
                    'success' => "You have been successfully registered. You can now try log in."
                ));
                break;
            case 4:
                $this->view['view']->render($response, 'register.html.twig', array(
                    'error' => 'Mail address already used.'
                ));
                break;
            case 5:
                $this->view['view']->render($response, 'register.html.twig', array(
                    'error' => 'Username already used.'
                ));
                break;
            case 6:
                $this->view['view']->render($response, 'register.html.twig', array(
                    'error' => 'Passwords are differents.'
                ));
                break;
            default:
                $this->view['view']->render($response, 'register.html.twig', array(
                    'error' => 'Unable to register you, informations are wrong, please try again.'
                ));
                break;
        }

        return $response;

    }

    private function upload($username){
        $tabExt = array('jpg','png','jpeg', 'gif'); //Extensions autorisées

        if(!is_dir(TARGET))
            mkdir(TARGET, 777);

        if(!empty($_POST)){

            if( !empty($_FILES['profilepic']['name'])){

                $extension = pathinfo($_FILES['profilepic']['name'], PATHINFO_EXTENSION);

                if(in_array(strtolower($extension), $tabExt)){

                    $infosImg = getimagesize($_FILES['profilepic']['tmp_name']);

                    if($infosImg[2] >= 1 && $infosImg[2] <= 14){

                        if(($infosImg[0]<=WIDTH_MAX) && ($infosImg[1]<=HEIGHT_MAX) && (filesize($_FILES['profilepic']['tmp_name'])<=MAX_SIZE)){

                            if(isset($_FILES['profilepic']['error']) && UPLOAD_ERR_OK === $_FILES['profilepic']['error']){

                                if(move_uploaded_file($_FILES['profilepic']['tmp_name'], TARGET.$username.'tmp'.$extension)){

                                    switch($extension){
                                        case 'png':
                                            $src = imagecreatefrompng(TARGET.$username.'tmp'.$extension);
                                            break;
                                        case 'jpg':
                                            $src = imagecreatefromjpeg(TARGET.$username.'tmp'.$extension);
                                            break;
                                        case 'jpeg':
                                            $src = imagecreatefromjpeg(TARGET.$username.'tmp'.$extension);
                                            break;
                                        case 'gif':
                                            $src = imagecreatefromgif(TARGET.$username.'tmp'.$extension);
                                            break;
                                        default:
                                            return 'default';
                                    }

                                    $dest = imagecreatetruecolor(150, 150);

                                    if(imagecopyresized($dest, $src, 0, 0, 0, 0, 150, 150, $infosImg[0], $infosImg[1])){
                                        if(file_exists(TARGET.$username.'tmp'.$extension))
                                            unlink(TARGET.$username.'tmp'.$extension);

                                        switch($extension) {
                                            case 'png':
                                                imagepng($dest, TARGET.$username.'.'.$extension);
                                                break;
                                            case 'jpg':
                                                imagejpeg($dest, TARGET.$username.'.'.$extension);
                                                break;
                                            case 'jpeg':
                                                imagejpeg($dest, TARGET.$username.'.'.$extension);
                                                break;
                                            case 'gif':
                                                imagegif($dest, TARGET.$username.'.'.$extension);
                                                break;
                                        }
                                        return $username.'.'.$extension;
                                    }else
                                        return "default";

                                }else{
                                    $message = 'A problem has occured during the upload.';
                                }
                            }else{
                                $message = 'An internal error prevented the upload.';
                            }
                        }else{
                            $message = 'The picture is too big. (Max : 1000x1000)';
                        }
                    }else{
                        $message = 'The file you want to upload is not a picture.';
                    }
                }else{
                    $message = 'The file extension is uncorrect.';
                }
            }else{
                $message = 'Please fill the form.';
            }
        }

        return "default";
    }

}
