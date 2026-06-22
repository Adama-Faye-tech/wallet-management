function showResult(elementId, isSuccess, message) {
    const element = document.getElementById(elementId);
    element.innerHTML = `<div class="${isSuccess ? 'success' : 'error'}">${message}</div>`;
}

function parseResponseText(response) {
    return response.text().then(text => {
        if (!text) {
            return null;
        }

        try {
            return JSON.parse(text);
        } catch (e) {
            return {
                success: false,
                message: text
            };
        }
    });
}

function updateBalanceBanner(amount) {
    const formatted = Number(amount || 0).toLocaleString('fr-FR');
    document.getElementById('totalBalance').textContent = `${formatted} F CFA`;
}

function activateTab(tabName) {
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.tab === tabName);
    });

    document.querySelectorAll('.tab-panel').forEach(panel => {
        panel.classList.toggle('active', panel.id === tabName);
    });
}

document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => activateTab(btn.dataset.tab));
});

document.getElementById('createWalletForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('/wallet/create', {
        method: 'POST',
        body: formData
    })
    .then(parseResponseText)
    .then(data => {
        const resultDiv = document.getElementById('createResult');
        if (data.success) {
            resultDiv.innerHTML = `
                <div class="success">
                    <strong>Wallet créé avec succès.</strong><br>
                    ${data.message}
                </div>`;
            const phone = document.getElementById('telephone').value.trim();
            document.getElementById('balanceTelephone').value = phone;
            document.getElementById('transactionTelephone').value = phone;
            document.getElementById('withdrawTelephone').value = phone;
            updateBalanceBanner(formData.get('solde') || 0);
            this.reset();
            loadTransactions();
        } else {
            resultDiv.innerHTML = `<div class="error">${data.message}</div>`;
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        document.getElementById('createResult').innerHTML = `<div class="error">Erreur lors de la création du wallet</div>`;
    });
});

function checkBalance() {
    const telephone = document.getElementById('balanceTelephone').value.trim();
    if (!telephone) {
        showResult('balanceResult', false, 'Veuillez saisir un numéro de téléphone');
        return;
    }

    const formData = new FormData();
    formData.append('telephone', telephone);

    fetch('/wallet/balance', {
        method: 'POST',
        body: formData
    })
    .then(parseResponseText)
    .then(data => {
        if (data.success) {
            updateBalanceBanner(data.solde);
            showResult(
                'balanceResult',
                true,
                `<strong>${data.nom} ${data.prenom}</strong><br>` +
                `Téléphone : ${data.telephone}<br>` +
                `Solde actuel : <strong>${Number(data.solde).toLocaleString('fr-FR')} CFA</strong>`
            );
        } else {
            showResult('balanceResult', false, data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showResult('balanceResult', false, 'Erreur lors de la récupération du solde');
    });
}

document.getElementById('checkBalanceBtn').addEventListener('click', checkBalance);

function handleTransaction(type) {
    const telephone = document.getElementById(type === 'depot' ? 'transactionTelephone' : 'withdrawTelephone').value;
    const montant = parseFloat(document.getElementById(type === 'depot' ? 'transactionMontant' : 'withdrawMontant').value);
    const resultId = type === 'depot' ? 'transactionResult' : 'withdrawResult';

    if (!telephone) {
        showResult(resultId, false, 'Veuillez saisir un numéro de téléphone');
        return;
    }

    if (isNaN(montant) || montant <= 0) {
        showResult(resultId, false, 'Veuillez saisir un montant valide');
        return;
    }

    const formData = new FormData();
    formData.append('telephone', telephone);
    formData.append('montant', montant);

    const url = type === 'depot'
        ? '/transaction/depot'
        : '/transaction/retrait';

    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(parseResponseText)
    .then(data => {
        if (data.success) {
            let message = data.message;
            if (data.frais) {
                message += `<br>Frais: ${data.frais} CFA`;
            }
            if (data.newSolde !== undefined) {
                message += `<br>Nouveau solde: ${data.newSolde} CFA`;
                updateBalanceBanner(data.newSolde);
            }
            showResult(resultId, true, message);
            if (type === 'depot') {
                document.getElementById('transactionTelephone').value = '';
                document.getElementById('transactionMontant').value = '';
            } else {
                document.getElementById('withdrawTelephone').value = '';
                document.getElementById('withdrawMontant').value = '';
            }
            loadTransactions();
        } else {
            showResult(resultId, false, data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showResult(resultId, false, 'Erreur lors de la transaction');
    });
}

document.getElementById('depotBtn').addEventListener('click', function() {
    handleTransaction('depot');
});

document.getElementById('retraitBtn').addEventListener('click', function() {
    handleTransaction('retrait');
});

function loadTransactions() {
    fetch('/transaction/all')
        .then(parseResponseText)
        .then(data => {
            const tbody = document.getElementById('transactionsBody');
            tbody.innerHTML = '';

            if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="no-data">Aucune transaction</td></tr>`;
                return;
            }

            data.forEach(transaction => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${transaction.code}</td>
                    <td>${transaction.nom} ${transaction.prenom}</td>
                    <td>${transaction.telephone}</td>
                    <td>${transaction.montant} CFA</td>
                    <td><span class="type-${transaction.type.toLowerCase()}">${transaction.type}</span></td>
                    <td>${new Date(transaction.date_heure).toLocaleString('fr-FR')}</td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(error => {
            console.error('Erreur:', error);
            document.getElementById('transactionsBody').innerHTML = `<tr><td colspan="6" class="error">Erreur lors du chargement des transactions</td></tr>`;
        });
}

document.addEventListener('DOMContentLoaded', function() {
    activateTab('create');
    loadTransactions();
    setInterval(loadTransactions, 30000);
});