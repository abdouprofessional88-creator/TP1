<?php

declare(strict_types=1);

require_once __DIR__ . '/CompteBancaire.php';

// 1. Création des comptes
$c1 = new CompteBancaire('CB000001', 'Sara Alami', 1000);
$c2 = new CompteBancaire('CB000002', 'Omar Benani'); // solde initial par défaut : 0

// 2. Dépôt, retrait, virement
$c1->deposer(500);
$c1->retirer(200);
$c1->virerVers($c2, 300);
echo $c1 . PHP_EOL;
echo $c2 . PHP_EOL;

// 3. Cas d'erreur : chacun est intercepté pour que le script continue
try {
    $c1->retirer(5000);
} catch (RuntimeException | InvalidArgumentException $e) {
    echo "Erreur : " . $e->getMessage() . PHP_EOL;
}

try {
    $c1->deposer(-50);
} catch (InvalidArgumentException $e) {
    echo "Erreur : " . $e->getMessage() . PHP_EOL;
}

try {
    new CompteBancaire('XYZ', 'Inconnu');
} catch (InvalidArgumentException $e) {
    echo "Erreur : " . $e->getMessage() . PHP_EOL;
}

try {
    $c1->virerVers($c1, 10);
} catch (InvalidArgumentException $e) {
    echo "Erreur : " . $e->getMessage() . PHP_EOL;
}

// 4. setTitulaire avec des espaces superflus
$c2->setTitulaire('  Omar BENANI  ');
echo $c2 . PHP_EOL;

// 6. Statique et constante (avant l'erreur fatale éventuelle)
echo "Nombre de comptes créés : " . CompteBancaire::getNombreComptes()
    . " (devise : " . CompteBancaire::DEVISE . ")" . PHP_EOL;

// 5. Accès direct à un attribut privé : PHP lance une Error.
//    Message : « Cannot access private property CompteBancaire::$solde ».
//    $solde est private : seule la classe elle-même peut le modifier, d'où
//    l'encapsulation. Ici on intercepte avec catch (Error $e), sinon c'est une
//    erreur fatale qui arrête le script.
try {
    $c1->solde = 1000000;
} catch (Error $e) {
    echo "Erreur PHP : " . $e->getMessage() . PHP_EOL;
}
