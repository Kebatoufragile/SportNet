<?php

define("PATH_ROOT", __DIR__ . '/');

require PATH_ROOT . '../app/server.php';

$trial = \App\Models\Trial::where('idTrial', 'like', $_POST['idTrial'])->first();

if(!is_null($trial)) {
    $e = \App\Models\Event::where('idEvent', 'like', $trial->idEvent)->first();

    $name = str_replace('/', '_', $e->name) . $trial->idEvent . '_' . $trial->name . '.csv';
    $file = fopen($name, 'w');

    $inscriptions = \App\Models\Inscription::where('idTrial', 'like', $_POST['idTrial'])->get();

    $firstline = 'idTrial;idParticipant;lastname;firstname;bib';
    fwrite($file, $firstline);

    foreach ($inscriptions as $inscription) {
        $participant = \App\Models\Participant::where('idParticipant', 'like', $inscription->idParticipant)->first();
        $line = $_POST['idTrial'] . ';' . $participant->idParticipant . ';' . $participant->lastname . ';' . $participant->firstname . ';' . $participant->bib;
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
}else
    die('The trial does not exist.');

