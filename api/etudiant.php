<?php
require_once "headers.php";

require_once '../Models/Database.php';
require_once '../Models/Etudiant.php';

$database = new Database();
$db = $database->getConnection();

$etudiant = new Etudiant($db);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $etudiant->id = $_GET['id'];

        // Récupérer un étudiant
        if ($etudiant->lireUnSeul()) {
            $etudiant_arr = array(
                "id" => $etudiant->id,
                "nom" => $etudiant->nom,
                "prenom" => $etudiant->prenom,
                "id_annee_academique" => $etudiant->id_annee_academique,
                "id_cours" => $etudiant->id_cours
            );

            echo json_encode($etudiant_arr);
        } else {
            echo json_encode(array("message" => "Étudiant non trouvé."));
        }
    } else {
        $stmt = $etudiant->lireTous();
        $etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($etudiants);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $etudiant->nom = $data->nom;
    $etudiant->prenom = $data->prenom;
    $etudiant->id_annee_academique = $data->id_annee_academique;
    $etudiant->id_cours = $data->id_cours;
    
    if($etudiant->creer()){
        echo json_encode(array("message" => "Etudiant créé."));
    } else {
        echo json_encode(array("message" => "Erreur lors de la création de l'étudiant."));
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $data = json_decode(file_get_contents("php://input"));
    
    // S'assurer que l'ID est fourni
    if (!empty($data->id)) {
        $etudiant->id = $data->id;
        $etudiant->nom = $data->nom;
        $etudiant->prenom = $data->prenom;
        $etudiant->id_annee_academique = $data->id_annee_academique;
        $etudiant->id_cours = $data->id_cours;


        // Mettre à jour l'étudiant
        if($etudiant->mettreAJour()){
            echo json_encode(array("message" => "Étudiant mis à jour."));
        } else {
            echo json_encode(array("message" => "Impossible de mettre à jour l'étudiant."));
        }
    } else {
        echo json_encode(array("message" => "ID manquant pour la mise à jour."));
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));
    echo json_encode($data);die;
    
    // S'assurer que l'ID est fourni
    if (!empty($data->id)) {
        $etudiant->id = $data->id;

        // Supprimer l'étudiant
        if($etudiant->supprimer()){
            echo json_encode(array("message" => "Étudiant supprimé."));
        } else {
            echo json_encode(array("message" => "Impossible de supprimer l'étudiant."));
        }
    } else {
        echo json_encode(array("message" => "ID manquant pour la suppression."));
    }
}

?>
