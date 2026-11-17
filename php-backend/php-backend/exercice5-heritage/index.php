<?php

declare(strict_types=1);

// La classe mère en premier
require_once __DIR__ . '/CompteBancaire.php';
require_once __DIR__ . '/CompteEpargne.php';
require_once __DIR__ . '/CompteCourant.php';
require_once __DIR__ . '/Banque.php';

// Typé avec la classe mère : accepte n'importe quel type de compte, présent ou futur
function tenterRetrait(CompteBancaire $compte, float $montant): void
{
    try {
        $compte->retirer($montant);
        $etat = 'accepté';
    } catch (RuntimeException | InvalidArgumentException $e) {
        $etat = 'refusé';
    }
    printf("  %-8s %s (%.2f)\n", $compte->getType(), $etat, $compte->getSolde());
}

// 1. Création
$epargne = new CompteEpargne('CB000001', 'Sara Alami', 2000, 3);
$courant = new CompteCourant('CB000002', 'Omar Benani', 800, 1000);

// 2. Une classe abstraite ne s'instancie pas
try {
    $x = new CompteBancaire('CB000009', 'Test');
} catch (Error $e) {
    echo "Erreur attendue : " . $e->getMessage() . PHP_EOL;
}

// 3. Polymorphisme : un seul foreach pour tous les types
$comptes = [$epargne, $courant];
foreach ($comptes as $compte) {
    echo $compte . PHP_EOL;
}

// 6. instanceof : intérêts seulement pour les comptes épargne
foreach ($comptes as $compte) {
    if ($compte instanceof CompteEpargne) {
        $interets = $compte->calculerInterets();
        $compte->ajouterInterets();
        printf("Intérêts crédités : %.2f MAD -> nouveau solde : %.2f MAD\n", $interets, $compte->getSolde());
    }
}

// 4-5. Même appel, résultats différents selon le type réel de l'objet
foreach ([1700, 300] as $montant) {
    echo "Retrait de $montant :" . PHP_EOL;
    foreach ($comptes as $compte) {
        tenterRetrait($compte, $montant);
    }
}
echo "Courant en découvert ? " . ($courant->estEnDecouvert() ? 'oui' : 'non') . PHP_EOL;

// Banque (composition)
$banque = new Banque('Banque Atlas');
$banque->ajouterCompte($epargne);
$banque->ajouterCompte($courant);
try {
    $banque->ajouterCompte($epargne);
} catch (InvalidArgumentException $e) {
    echo "Erreur : " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL . "Comptes de la banque :" . PHP_EOL;
$banque->afficherComptes();
printf("Total des dépôts : %.2f MAD\n", $banque->totalDepots());
echo "Comptes épargne crédités : " . $banque->crediterInterets() . PHP_EOL;
echo "Compte inconnu : " . var_export($banque->trouverCompte('CB999999'), true) . PHP_EOL;
echo "Compte CB000002 : " . $banque->trouverCompte('CB000002') . PHP_EOL;

$types = [];
foreach ($banque->comptesParType() as $type => $nb) {
    $types[] = "$type = $nb";
}
echo "Comptes par type : " . implode(', ', $types) . PHP_EOL;
echo "Nombre de comptes créés : " . CompteBancaire::getNombreComptes() . PHP_EOL;

echo PHP_EOL . "Tri par solde décroissant :" . PHP_EOL;
foreach ($banque->comptesTriesParSolde() as $c) {
    echo $c . PHP_EOL;
}
