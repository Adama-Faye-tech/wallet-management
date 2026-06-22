<?php
require_once __DIR__ . '/../repositories/WalletRepository.php';

class WalletService {
    private $walletRepository;

    public function __construct() {
        $this->walletRepository = new WalletRepository();
    }

    public function createWallet($data) {
        if (empty($data['nom']) || empty($data['prenom']) || empty($data['telephone']) || empty($data['code'])) {
            return ['success' => false, 'message' => 'Tous les champs sont obligatoires'];
        }

        if ($this->walletRepository->existsByTelephone($data['telephone'])) {
            return ['success' => false, 'message' => 'Ce numéro de téléphone existe déjà'];
        }

        if ($this->walletRepository->existsByCode($data['code'])) {
            return ['success' => false, 'message' => 'Ce code existe déjà'];
        }

        if (isset($data['solde']) && $data['solde'] < 0) {
            return ['success' => false, 'message' => 'Le solde ne peut pas être négatif'];
        }

        $data['solde'] = $data['solde'] ?? 0;
        $id = $this->walletRepository->create($data);
        return ['success' => true, 'message' => 'Wallet créé avec succès', 'id' => $id];
    }

    public function getWalletByTelephone($telephone) {
        return $this->walletRepository->findByTelephone($telephone);
    }

    public function getAllWallets() {
        return $this->walletRepository->getAll();
    }

    public function updateSolde($walletId, $newSolde) {
        return $this->walletRepository->updateSolde($walletId, $newSolde);
    }
}