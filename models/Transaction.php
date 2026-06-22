<?php
class Transaction {
    private $id;
    private $code;
    private $montant;
    private $dateHeure;
    private $type;
    private $walletId;

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
    public function getCode() { return $this->code; }
    public function getMontant() { return $this->montant; }
    public function getDateHeure() { return $this->dateHeure; }
    public function getType() { return $this->type; }
    public function getWalletId() { return $this->walletId; }

    public function setId($id) { $this->id = $id; }
    public function setCode($code) { $this->code = $code; }
    public function setMontant($montant) { $this->montant = $montant; }
    public function setDateHeure($dateHeure) { $this->dateHeure = $dateHeure; }
    public function setType($type) { $this->type = $type; }
    public function setWalletId($walletId) { $this->walletId = $walletId; }
}