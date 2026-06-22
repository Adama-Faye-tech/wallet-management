<?php
require_once __DIR__ . '/../services/WalletService.php';

class WalletController {
    private $walletService;

    public function __construct() {
        $this->walletService = new WalletService();
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom' => $_POST['nom'] ?? '',
                'prenom' => $_POST['prenom'] ?? '',
                'telephone' => $_POST['telephone'] ?? '',
                'code' => $_POST['code'] ?? '',
                'solde' => $_POST['solde'] ?? 0
            ];

            $result = $this->walletService->createWallet($data);
            header('Content-Type: application/json');
            echo json_encode($result);
            exit;
        }
    }

    public function balance() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $telephone = $_POST['telephone'] ?? '';
            $wallet = $this->walletService->getWalletByTelephone($telephone);

            if (!$wallet) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Aucun wallet trouvé pour ce numéro'
                ]);
                exit;
            }

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'Solde du wallet récupéré',
                'telephone' => $wallet->getTelephone(),
                'solde' => $wallet->getSolde(),
                'nom' => $wallet->getNom(),
                'prenom' => $wallet->getPrenom()
            ]);
            exit;
        }
    }

    public function getAll() {
        header('Content-Type: application/json');
        echo json_encode($this->walletService->getAllWallets());
        exit;
    }
}