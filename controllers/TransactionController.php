<?php
require_once __DIR__ . '/../services/TransactionService.php';

class TransactionController {
    private $transactionService;

    public function __construct() {
        $this->transactionService = new TransactionService();
    }

    public function depot() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $telephone = $_POST['telephone'] ?? '';
            $montant = $_POST['montant'] ?? 0;

            $result = $this->transactionService->depot($telephone, floatval($montant));
            header('Content-Type: application/json');
            echo json_encode($result);
            exit;
        }
    }

    public function retrait() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $telephone = $_POST['telephone'] ?? '';
            $montant = $_POST['montant'] ?? 0;

            $result = $this->transactionService->retrait($telephone, floatval($montant));
            header('Content-Type: application/json');
            echo json_encode($result);
            exit;
        }
    }

    public function getAll() {
        header('Content-Type: application/json');
        echo json_encode($this->transactionService->getAllTransactions());
        exit;
    }
}