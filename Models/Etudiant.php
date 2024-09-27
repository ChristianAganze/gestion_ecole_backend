<?php
class Etudiant {
    private $conn;
    private $table_name = "anbc_etudiant";

    public $id;
    public $nom;
    public $prenom;
    public $id_annee_academique;
    public $id_cours;

    public function __construct($db){
        $this->conn = $db;
    }

    public function lireTous(){
        $query = "SELECT 
                e.id AS id, e.nom AS nom, e.prenom AS prenom, 
                a.annee AS annee_academique, 
                c.nom AS nom_cours 
            FROM {$this->table_name} e
            LEFT JOIN anbc_annee_academique a ON e.id_annee_academique = a.id
            LEFT JOIN anbc_cours c ON e.id_cours = c.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function creer(){
        $query = "INSERT INTO " . $this->table_name . "
                  SET nom=:nom, prenom=:prenom, id_annee_academique=:id_annee_academique, id_cours=:id_cours";
        
        $stmt = $this->conn->prepare($query);

        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->prenom = htmlspecialchars(strip_tags($this->prenom));
        $this->id_annee_academique = htmlspecialchars(strip_tags($this->id_annee_academique));
        $this->id_cours = htmlspecialchars(strip_tags($this->id_cours));

        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":prenom", $this->prenom);
        $stmt->bindParam(":id_annee_academique", $this->id_annee_academique);
        $stmt->bindParam(":id_cours", $this->id_cours);

        if($stmt->execute()){
            return true;
        }
        return false;
    }

    public function mettreAJour(){
        $query = "UPDATE " . $this->table_name . "
                  SET nom = :nom, prenom = :prenom, id_annee_academique = :id_annee_academique, id_cours = :id_cours
                  WHERE id = :id";
    
        $stmt = $this->conn->prepare($query);
    
        // Sécuriser les données
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->prenom = htmlspecialchars(strip_tags($this->prenom));
        $this->id_annee_academique = htmlspecialchars(strip_tags($this->id_annee_academique));
        $this->id_cours = htmlspecialchars(strip_tags($this->id_cours));
        $this->id = htmlspecialchars(strip_tags($this->id));
    
        // Bind des paramètres
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':prenom', $this->prenom);
        $stmt->bindParam(':id_annee_academique', $this->id_annee_academique);
        $stmt->bindParam(':id_cours', $this->id_cours);
        $stmt->bindParam(':id', $this->id);
    
        // Exécution de la requête
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    public function supprimer(){
    $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    // Sécuriser l'ID
    $this->id = htmlspecialchars(strip_tags($this->id));

    // Bind de l'ID
    $stmt->bindParam(':id', $this->id);

    // Exécution de la requête
    if($stmt->execute()){
        return true;
    }
    return false;
}

public function lireUnSeul(){
    $query = "SELECT 
        e.id AS id, e.nom AS nom, e.prenom AS prenom, 
        a.annee AS annee_academique, 
        c.nom AS nom_cours 
    FROM {$this->table_name} e
    LEFT JOIN amom_annee_academique a ON e.id_annee_academique = a.id
    LEFT JOIN amom_cours c ON e.id_cours = c.id
    WHERE e.id = ? LIMIT 0,1";

    $stmt = $this->conn->prepare($query);

    // Sécuriser l'ID
    $this->id = htmlspecialchars(strip_tags($this->id));

    // Bind de l'ID
    $stmt->bindParam(1, $this->id);

    // Exécution de la requête
    $stmt->execute();

    // Si un étudiant est trouvé
    if($stmt->rowCount() > 0){
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Remplir les propriétés de l'étudiant
        $this->nom = $row['nom'];
        $this->prenom = $row['prenom'];
        $this->id_annee_academique = $row['id_annee_academique'];
        $this->id_cours = $row['id_cours'];

        return true;
    }

    return false;
}

    
}
?>
