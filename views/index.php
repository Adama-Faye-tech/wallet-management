<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wallet Manager</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="app-shell">
        <header class="app-header">
            <div class="header-top">
                <div>
                    <p class="eyebrow">Portefeuille</p>
                    <h1>Mon compte</h1>
                </div>
                <button class="profile-badge">A</button>
            </div>
            <section class="balance-banner">
                <span>Solde total</span>
                <strong id="totalBalance">0 F CFA</strong>
            </section>
        </header>

        <nav class="tab-nav">
            <button class="tab-btn active" data-tab="create">
                <span class="tab-icon">+</span>
                <span>Créer</span>
            </button>
            <button class="tab-btn" data-tab="balance">
                <span class="tab-icon">◌</span>
                <span>Solde</span>
            </button>
            <button class="tab-btn" data-tab="deposit">
                <span class="tab-icon">↑</span>
                <span>Dépôt</span>
            </button>
            <button class="tab-btn" data-tab="withdraw">
                <span class="tab-icon">↓</span>
                <span>Retrait</span>
            </button>
            <button class="tab-btn" data-tab="history">
                <span class="tab-icon">⏱</span>
                <span>Historique</span>
            </button>
        </nav>

        <main class="tab-panels">
            <section id="create" class="tab-panel active panel">
                <div class="panel-header">
                    <span class="tag">+</span>
                    <h2>Créer un wallet</h2>
                </div>
                <form id="createWalletForm" class="form-grid">
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom" placeholder="Ex. Diop" required>
                    </div>
                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" id="prenom" name="prenom" placeholder="Ex. Aminata" required>
                    </div>
                    <div class="form-group full-width">
                        <label for="telephone">Téléphone</label>
                        <input type="tel" id="telephone" name="telephone" placeholder="Ex. 775000000" required>
                    </div>
                    <div class="form-group full-width">
                        <label for="code">Code</label>
                        <input type="text" id="code" name="code" placeholder="Ex. W1234" required>
                    </div>
                    <div class="form-group full-width">
                        <label for="solde">Solde initial</label>
                        <input type="number" id="solde" name="solde" min="0" value="0">
                    </div>
                    <div class="form-actions full-width">
                        <button type="submit">Créer le wallet</button>
                    </div>
                </form>
                <div id="createResult" class="result"></div>
            </section>

            <section id="balance" class="tab-panel panel">
                <div class="panel-header">
                    <span class="tag">◌</span>
                    <h2>Vérifier le solde</h2>
                </div>
                <div class="inline-actions">
                    <div class="form-group compact">
                        <label for="balanceTelephone">Téléphone du wallet</label>
                        <input type="tel" id="balanceTelephone" placeholder="Entrez le numéro">
                    </div>
                    <button id="checkBalanceBtn" class="btn-primary">Voir</button>
                </div>
                <div id="balanceResult" class="result"></div>
            </section>

            <section id="deposit" class="tab-panel panel">
                <div class="panel-header">
                    <span class="tag">↑</span>
                    <h2>Dépôt sur un wallet</h2>
                </div>
                <div class="transaction-form">
                    <div class="form-group">
                        <label for="transactionTelephone">Téléphone du wallet</label>
                        <input type="tel" id="transactionTelephone" placeholder="Numéro du wallet" required>
                    </div>
                    <div class="form-group">
                        <label for="transactionMontant">Montant</label>
                        <input type="number" id="transactionMontant" min="0" step="0.01" placeholder="Ex. 5000" required>
                    </div>
                    <div class="button-group">
                        <button id="depotBtn" class="btn-depot">Faire un dépôt</button>
                    </div>
                </div>
                <div id="transactionResult" class="result"></div>
            </section>

            <section id="withdraw" class="tab-panel panel">
                <div class="panel-header">
                    <span class="tag">↓</span>
                    <h2>Retrait d'un wallet</h2>
                </div>
                <p class="info-text">Frais de retrait : 1% du montant, avec un plafond de 5000 CFA.</p>
                <div class="transaction-form">
                    <div class="form-group">
                        <label for="withdrawTelephone">Téléphone du wallet</label>
                        <input type="tel" id="withdrawTelephone" placeholder="Numéro du wallet" required>
                    </div>
                    <div class="form-group">
                        <label for="withdrawMontant">Montant</label>
                        <input type="number" id="withdrawMontant" min="0" step="0.01" placeholder="Ex. 2000" required>
                    </div>
                    <div class="button-group">
                        <button id="retraitBtn" class="btn-retrait">Faire un retrait</button>
                    </div>
                </div>
                <div id="withdrawResult" class="result"></div>
            </section>

            <section id="history" class="tab-panel panel history-panel">
                <div class="panel-header">
                    <span class="tag">⏱</span>
                    <h2>Historique des transactions</h2>
                </div>
                <div id="transactionsContainer">
                    <table id="transactionsTable">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Titulaire</th>
                                <th>Téléphone</th>
                                <th>Montant</th>
                                <th>Type</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody id="transactionsBody">
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <script src="/js/script.js"></script>
</body>
</html>