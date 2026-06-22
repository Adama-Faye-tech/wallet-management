<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../router/Router.php';
require_once __DIR__ . '/../controllers/WalletController.php';
require_once __DIR__ . '/../controllers/TransactionController.php';

$router = new Router();

$router->addRoute('POST', '/wallet/create', 'WalletController', 'create');
$router->addRoute('POST', '/wallet/balance', 'WalletController', 'balance');
$router->addRoute('GET', '/wallet/all', 'WalletController', 'getAll');
$router->addRoute('POST', '/transaction/depot', 'TransactionController', 'depot');
$router->addRoute('POST', '/transaction/retrait', 'TransactionController', 'retrait');
$router->addRoute('GET', '/transaction/all', 'TransactionController', 'getAll');

$router->dispatch();