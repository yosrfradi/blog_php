<?php
class Utilisateur {
    private $nomUtilisateur;
    private $preUtilisateur;
    private $email;
    private $mdp;

    public function __construct(array $donnees){
        $this->hydrate($donnees);
    }
public function hydrate(array $donnees){
    foreach ($donnees as $key => $value){
        $method = 'set' . ucfirst($key);
        if (method_exists($this, $method)){
            $this->$method($value);
        }
    }
}

public function setNomUtilisateur($value) {
    $this->nomUtilisateur = $value;
}

public function setPreUtilisateur($value) {
    $this->preUtilisateur = $value;
}

public function setEmail($value) {
    $this->email = $value;
}

    public function setMdp($value) {
        $this->mdp =$value ;
    }
    public function getNomUtilisateur() {
        return $this->nomUtilisateur;
    }

    public function getPreUtilisateur() {
        return $this->preUtilisateur;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getMdp() {
        return $this->mdp;
    }
}

   
class Manager {
    private $conn ;
    public function __construct(PDO $conn){
        $this->conn = $conn;
    }
    public function ajout(Utilisateur $donnees){
   $sql = 'INSERT INTO  utilisateurs(nom, prenom, email, mdp) VALUES (:nom, :prenom, :email, :mdp)';
   $stmt =$this->conn->prepare($sql);
   
   $stmt->bindValue(':nom',$donnees->getNomUtilisateur() );
   $stmt->bindValue(':prenom',$donnees->getPreUtilisateur());
   $stmt->bindValue(':email',$donnees->getEmail());
   $stmt->bindValue(':mdp',$donnees->getMdp());
 
   $stmt->execute();
}
}