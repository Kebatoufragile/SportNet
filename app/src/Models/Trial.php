<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trial extends Model{

    protected $table = "trial";
    protected $primaryKey = "idTrial";
    public $timestamps = false;


    public static function downloadParticipants($idTrial){
        $trial = Trial::where('idTrial', 'like', $idTrial)->first();

        if(!is_null($trial)){
            $e = Event::where('idEvent', 'like', $trial->idEvent)->first();

            $name = str_replace('/', '_', $e->name).$trial->idEvent.'_'.$trial->name.'.csv';
            $file = fopen($name, 'w');

            $inscriptions = Inscription::where('idTrial', 'like', $idTrial)->get();

            $firstline = 'idTrial;idParticipant;lastname;firstname;bib';
            fwrite($file, $firstline);

            foreach($inscriptions as $inscription){
                $participant = Participant::where('idParticipant', 'like', $inscription->idParticipant)->first();
                $line = $idTrial.';'.$participant->idParticipant.';'.$participant->lastname.';'.$participant->firstname.';'.$participant->bib;
                fwrite($file, $line);
            }

            //readfile($name)
            fclose($file);
        }
    }

}
