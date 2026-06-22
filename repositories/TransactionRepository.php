<?php
require_once __DIR__ . '/../models/Transaction.php';

class TransactionRepository {
    private $storagePath;
    private $walletsPath;

    public function __construct() {
        $this->storagePath = __DIR__ . '/../storage/transactions.json';
        $this->walletsPath = __DIR__ . '/../storage/wallets.json';
        $this->ensureStorage();
    }

    private function ensureStorage() {
        $dir = dirname($this->storagePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        if (!file_exists($this->storagePath)) {
            file_put_contents($this->storagePath, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        if (!file_exists($this->walletsPath)) {
            file_put_contents($this->walletsPath, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }

    private function loadAll() {
        $content = file_get_contents($this->storagePath);
        return $content ? json_decode($content, true) : [];
    }

    private function saveAll($transactions) {
        file_put_contents($this->storagePath, json_encode($transactions, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    private function loadWallets() {
        $content = file_get_contents($this->walletsPath);
        return $content ? json_decode($content, true) : [];
    }

    public function create($data) {
        $transactions = $this->loadAll();
        $id = count($transactions) + 1;

        $transactions[] = [
            'id' => $id,
            'code' => $data['code'],
            'montant' => (float) $data['montant'],
            'date_heure' => date('Y-m-d H:i:s'),
            'type' => $data['type'],
            'wallet_id' => (int) $data['wallet_id']
        ];

        $this->saveAll($transactions);
        return $id;
    }

    public function findByWalletId($walletId) {
        $transactions = [];
        foreach ($this->loadAll() as $data) {
            if ((int) $data['wallet_id'] === (int) $walletId) {
                $transactions[] = new Transaction($data);
            }
        }
        usort($transactions, function ($a, $b) {
            return strtotime($b->getDateHeure()) - strtotime($a->getDateHeure());
        });
        return $transactions;
    }

    public function getAll() {
        $wallets = [];
        foreach ($this->loadWallets() as $wallet) {
            $wallets[(int) $wallet['id']] = $wallet;
        }

        $result = [];
        foreach ($this->loadAll() as $data) {
            $wallet = $wallets[(int) $data['wallet_id']] ?? null;
            if ($wallet) {
                $result[] = [
                    'id' => $data['id'],
                    'code' => $data['code'],
                    'montant' => $data['montant'],
                    'date_heure' => $data['date_heure'],
                    'type' => $data['type'],
                    'wallet_id' => $data['wallet_id'],
                    'nom' => $wallet['nom'],
                    'prenom' => $wallet['prenom'],
                    'telephone' => $wallet['telephone']
                ];
            }
        }

        usort($result, function ($a, $b) {
            return strtotime($b['date_heure']) - strtotime($a['date_heure']);
        });
        return $result;
    }
}