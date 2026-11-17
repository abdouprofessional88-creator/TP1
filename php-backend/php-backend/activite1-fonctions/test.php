<?php

declare(strict_types=1);

require_once __DIR__ . '/fonctions.php';

function titre(string $t): void
{
    echo PHP_EOL . "=== $t ===" . PHP_EOL;
}

titre('Étape 1 : opérations');
echo "12 + 8 = " . additionner(12, 8) . PHP_EOL;
echo "12 - 8 = " . soustraire(12, 8) . PHP_EOL;
echo "12 x 8 = " . multiplier(12, 8) . PHP_EOL;
echo "12 / 8 = " . diviser(12, 8) . PHP_EOL;
try {
    echo diviser(10, 0);
} catch (InvalidArgumentException $e) {
    echo "Erreur : " . $e->getMessage() . PHP_EOL;
}

titre('Étape 2 : fonctions logiques');
echo "7 est pair ? " . (estPair(7) ? "oui" : "non") . PHP_EOL;
echo "10 est pair ? " . (estPair(10) ? "oui" : "non") . PHP_EOL;
echo "-3 est positif ? " . (estPositif(-3) ? "oui" : "non") . PHP_EOL;
echo "maximum(4, 9, 6) = " . maximum(4, 9, 6) . " | avec max() = " . maximumAvecMax(4, 9, 6) . PHP_EOL;
foreach ([1, 2, 17, 20, 97] as $n) {
    echo "$n est premier ? " . (estPremier($n) ? "oui" : "non") . PHP_EOL;
}

titre('Étape 3 : calculs commerciaux');
echo "TTC(100) = " . calculerTTC(100) . PHP_EOL;
echo "TTC(100, 10%) = " . calculerTTC(100, 10) . PHP_EOL;
echo "Remise 10 % sur 1000 = " . calculerRemise(1000, 10) . PHP_EOL;
echo "Facture : " . formaterMontant(calculerFacture(100, 10, 10, 20)) . PHP_EOL;

titre('Étape 4 : statistiques et géométrie');
echo "Moyenne(12, 15, 9) = " . round(moyenne(12, 15, 9), 2) . PHP_EOL;
echo "Moyenne() = " . moyenne() . PHP_EOL;
echo "Aire cercle r=3 : " . aireCercle(3) . " | Périmètre : " . perimetreCercle(3) . PHP_EOL;
echo "25 °C = " . celsiusVersFahrenheit(25) . " °F" . PHP_EOL;
echo "77 °F = " . fahrenheitVersCelsius(77) . " °C" . PHP_EOL;
echo "1000 MAD = " . convertirDevise(1000, 0.092) . " EUR" . PHP_EOL;

titre('Étape 5 : boucles');
echo "5! = " . factorielle(5) . PHP_EOL;
try {
    factorielle(-1);
} catch (InvalidArgumentException $e) {
    echo "Erreur : " . $e->getMessage() . PHP_EOL;
}
echo "Fibonacci(8) : " . implode(', ', fibonacci(8)) . PHP_EOL;
echo "PGCD(48, 18) = " . pgcd(48, 18) . PHP_EOL;
echo "2^10 (boucle) = " . puissance(2, 10) . " | (récursive) = " . puissanceRecursive(2, 10) . PHP_EOL;
echo "somme(1, 2, 3.5) = " . somme(1, 2, 3.5) . PHP_EOL;
