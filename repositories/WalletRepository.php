<?php
require_once __DIR__ . '/../models/Wallet.php';

class WalletRepository {
    private $storagePath;

    public function __construct() {
        $this->storagePath = __DIR__ . '/../storage/wallets.json';
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
    }

    private function loadAll() {
        $content = file_get_contents($this->storagePath);
        return $content ? json_decode($content, true) : [];
    }

    private function saveAll($wallets) {
        file_put_contents($this->storagePath, json_encode($wallets, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function create($data) {
        $wallets = $this->loadAll();
        $id = count($wallets) + 1;

        $wallet = [
            'id' => $id,
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'telephone' => $data['telephone'],
            'code' => $data['code'],
            'solde' => (float) ($data['solde'] ?? 0),
            'date_creation' => date('Y-m-d H:i:s')
        ];

        $wallets[] = $wallet;
        $this->saveAll($wallets);
        return $id;
    }

    public function findByTelephone($telephone) {
        foreach ($this->loadAll() as $data) {
            if ($data['telephone'] === $telephone) {
                return new Wallet($data);
            }
        }
        return null;
    }

    public function findByCode($code) {
        foreach ($this->loadAll() as $data) {
            if ($data['code'] === $code) {
                return new Wallet($data);
            }
        }
        return null;
    }

    public function updateSolde($walletId, $newSolde) {
        $wallets = $this->loadAll();
        foreach ($wallets as &$wallet) {
            if ((int) $wallet['id'] === (int) $walletId) {
                $wallet['solde'] = (float) $newSolde;
                $this->saveAll($wallets);
                return true;
            }
        }
        return false;
    }

    public function getAll() {
        $wallets = [];
        foreach ($this->loadAll() as $data) {
            $wallets[] = new Wallet($data);
        }
        usort($wallets, function ($a, $b) {
            return strtotime($b->getDateCreation()) - strtotime($a->getDateCreation());
        });
        return $wallets;
    }

    public function existsByTelephone($telephone) {
        foreach ($this->loadAll() as $data) {
            if ($data['telephone'] === $telephone) {
                return true;
            }
        }
        return false;
    }

    public function existsByCode($code) {
        foreach ($this->loadAll() as $data) {
            if ($data['code'] === $code) {
                return true;
            }
        }
        return false;
    }
}