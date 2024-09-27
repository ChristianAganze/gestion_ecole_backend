<?php

require_once "headers.php";

require_once '../Models/Database.php';
require_once '../Models/AnneeAcademique.php';

$database = new Database();
$db = $database->getConnection();

$anneeAcademique = new AnneeAcademique($db);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $anneeAcademique->id = $_GET['id'];
        if ($anneeAcademique->lireUneSeule()) {
            $anneeAcademique_arr = array(
                "id" => $anneeAcademique->id,
                "annee" => $anneeAcademique->annee
            );
            echo json_encode($anneeAcademique_arr);
        } else {
            echo json_encode(array("message" => "Année académique non trouvée."));
        }
    } else {
        $stmt = $anneeAcademique->lireToutes();
        $anneeAcademiques_arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($anneeAcademiques_arr);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    $anneeAcademique->annee = $data->annee;

    if ($anneeAcademique->creer()) {
        echo json_encode(array("message" => "Année académique créée."));
    } else {
        echo json_encode(array("message" => "Impossible de créer l'année académique."));
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT' && isset($_GET['entity']) && $_GET['entity'] == 'annee_academique') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->id)) {
        $anneeAcademique->id = $data->id;
        $anneeAcademique->annee = $data->annee;

        if ($anneeAcademique->mettreAJour()) {
            echo json_encode(array("message" => "Année académique mise à jour."));
        } else {
            echo json_encode(array("message" => "Impossible de mettre à jour l'année académique."));
        }
    } else {
        echo json_encode(array("message" => "ID manquant pour la mise à jour."));
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->id)) {
        $anneeAcademique->id = $data->id;

        if ($anneeAcademique->supprimer()) {
            echo json_encode(array("message" => "Année académique supprimée."));
        } else {
            echo json_encode(array("message" => "Impossible de supprimer l'année académique."));
        }
    } else {
        echo json_encode(array("message" => "ID manquant pour la suppression."));
    }
}



?>