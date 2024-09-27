<?php

require_once "headers.php";

require_once '../Models/Database.php';
require_once '../Models/Cours.php';

$database = new Database();
$db = $database->getConnection();

$cours = new Cours($db);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $cours->id = $_GET['id'];
        if ($cours->lireUnSeul()) {
            $cours_arr = array(
                "id" => $cours->id,
                "nom" => $cours->nom,
                "description" => $cours->description
            );
            echo json_encode($cours_arr);
        } else {
            echo json_encode(array("message" => "Cours non trouvé."));
        }
    } else {
        $stmt = $cours->lireTous();
        $cours_arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($cours_arr);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    $cours->nom = $data->nom;
    $cours->description = $data->description;

    if ($cours->creer()) {
        echo json_encode(array("message" => "Cours créé."));
    } else {
        echo json_encode(array("message" => "Impossible de créer le cours."));
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->id)) {
        $cours->id = $data->id;
        $cours->nom = $data->nom;
        $cours->description = $data->description;

        if ($cours->mettreAJour()) {
            echo json_encode(array("message" => "Cours mis à jour."));
        } else {
            echo json_encode(array("message" => "Impossible de mettre à jour le cours."));
        }
    } else {
        echo json_encode(array("message" => "ID manquant pour la mise à jour."));
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->id)) {
        $cours->id = $data->id;

        if ($cours->supprimer()) {
            echo json_encode(array("message" => "Cours supprimé."));
        } else {
            echo json_encode(array("message" => "Impossible de supprimer le cours."));
        }
    } else {
        echo json_encode(array("message" => "ID manquant pour la suppression."));
    }
}



?>