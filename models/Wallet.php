<?php
class Wallet {
    private $id;
    private $nom;
    private $prenom;
    private $telephone;
    private $code;
    private $solde;
    private $dateCreation;

    public function __construct($data = []) {
        $this->hydrate($data);
    }

    public function hydrate($data) {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getPrenom() { return $this->prenom; }
    public function getTelephone() { return $this->telephone; }
    public function getCode() { return $this->code; }
    public function getSolde() { return $this->solde; }
    public function getDateCreation() { return $this->dateCreation; }

    public function setId($id) { $this->id = $id; }
    public function setNom($nom) { $this->nom = $nom; }
    public function setPrenom($prenom) { $this->prenom = $prenom; }
    public function setTelephone($telephone) { $this->telephone = $telephone; }
    public function setCode($code) { $this->code = $code; }
    public function setSolde($solde) { $this->solde = $solde; }
    public function setDateCreation($dateCreation) { $this->dateCreation = $dateCreation; }
}