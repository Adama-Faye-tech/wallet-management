<?php
require_once __DIR__ . '/../repositories/TransactionRepository.php';
require_once __DIR__ . '/../repositories/WalletRepository.php';

class TransactionService {
    private $transactionRepository;
    private $walletRepository;

    public function __construct() {
        $this->transactionRepository = new TransactionRepository();
        $this->walletRepository = new WalletRepository();
    }

    public function depot($telephone, $montant) {
        if (empty($telephone)) {
            return ['success' => false, 'message' => 'Le numéro de téléphone est obligatoire'];
        }

        $wallet = $this->walletRepository->findByTelephone($telephone);
        if (!$wallet) {
            return ['success' => false, 'message' => 'Wallet non trouvé'];
        }

        if ($montant <= 0) {
            return ['success' => false, 'message' => 'Le montant doit être positif'];
        }

        $newSolde = $wallet->getSolde() + $montant;
        $this->walletRepository->updateSolde($wallet->getId(), $newSolde);

        $code = 'DEP' . time() . rand(100, 999);
        $transactionData = [
            'code' => $code,
            'montant' => $montant,
            'type' => 'Dépôt',
            'wallet_id' => $wallet->getId()
        ];
        $this->transactionRepository->create($transactionData);

        return ['success' => true, 'message' => 'Dépôt effectué avec succès', 'newSolde' => $newSolde];
    }

    public function retrait($telephone, $montant) {
        if (empty($telephone)) {
            return ['success' => false, 'message' => 'Le numéro de téléphone est obligatoire'];
        }

        $wallet = $this->walletRepository->findByTelephone($telephone);
        if (!$wallet) {
            return ['success' => false, 'message' => 'Wallet non trouvé'];
        }

        if ($montant <= 0) {
            return ['success' => false, 'message' => 'Le montant doit être positif'];
        }

        $frais = min($montant * 0.01, 5000);
        $totalRetrait = $montant + $frais;

        if ($wallet->getSolde() < $totalRetrait) {
            return ['success' => false, 'message' => 'Solde insuffisant'];
        }

        $newSolde = $wallet->getSolde() - $totalRetrait;
        $this->walletRepository->updateSolde($wallet->getId(), $newSolde);

        $code = 'RET' . time() . rand(100, 999);
        $transactionData = [
            'code' => $code,
            'montant' => $montant,
            'type' => 'Retrait',
            'wallet_id' => $wallet->getId()
        ];
        $this->transactionRepository->create($transactionData);

        return [
            'success' => true, 
            'message' => 'Retrait effectué avec succès', 
            'newSolde' => $newSolde,
            'frais' => $frais
        ];
    }

    public function getAllTransactions() {
        return $this->transactionRepository->getAll();
    }

    public function getTransactionsByWallet($walletId) {
        return $this->transactionRepository->findByWalletId($walletId);
    }
}