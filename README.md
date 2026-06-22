# Gestion de Wallet

## Description
Application web de gestion de porte-monnaie électronique (Wallet) permettant de créer des wallets, effectuer des dépôts et des retraits, et visualiser les transactions.

## Fonctionnalités
- Création de wallet avec validation des données
- Dépôt sur un wallet
- Retrait d'un wallet avec calcul des frais (1% plafonné à 5000 CFA)
- Affichage des transactions

## Architecture
- MVC (Model-View-Controller)
- Repository Pattern pour les données
- Service Pattern pour la logique métier
- Routage personnalisé

## Technologies
- PHP 7.4+
- MySQL 
- HTML5 / CSS3
- JavaScript 

## Installation

### Prérequis
- Serveur web (Apache)
- PHP 7.4+
- MySQL 5.7+

### Étapes
1. Cloner le projet
```bash
git clone https://github.com/votre-nom/wallet-management.git