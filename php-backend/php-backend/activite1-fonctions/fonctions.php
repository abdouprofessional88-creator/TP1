<?php

declare(strict_types=1);

// ---------- Étape 1 : les quatre opérations ----------
function additionner(float $a, float $b): float
{
    return $a + $b;
}

function soustraire(float $a, float $b): float
{
    return $a - $b;
}

function multiplier(float $a, float $b): float
{
    return $a * $b;
}

function diviser(float $a, float $b): float
{
    if ($b == 0) {
        throw new InvalidArgumentException("Division par zéro impossible");
    }
    return $a / $b;
}

// ---------- Étape 2 : fonctions logiques ----------
function estPair(int $n): bool
{
    return $n % 2 === 0;
}

function estPositif(float $n): bool
{
    return $n > 0;
}

// Version sans max() : on compare manuellement
function maximum(float $a, float $b, float $c): float
{
    $plusGrand = $a;
    if ($b > $plusGrand) {
        $plusGrand = $b;
    }
    if ($c > $plusGrand) {
        $plusGrand = $c;
    }
    return $plusGrand;
}

// Version avec max() : plus courte et plus lisible
function maximumAvecMax(float $a, float $b, float $c): float
{
    return max($a, $b, $c);
}

function estPremier(int $n): bool
{
    if ($n < 2) {
        return false;
    }
    // Il suffit de tester les diviseurs jusqu'à la racine carrée de n
    $limite = (int) floor(sqrt($n));
    for ($i = 2; $i <= $limite; $i++) {
        if ($n % $i === 0) {
            return false;
        }
    }
    return true;
}

// ---------- Étape 3 : calculs commerciaux ----------
function calculerTTC(float $ht, float $tva = 20.0): float
{
    return $ht * (1 + $tva / 100);
}

function calculerRemise(float $prix, float $pourcentage): float
{
    return round($prix * (1 - $pourcentage / 100), 2);
}

// Réutilise calculerRemise et calculerTTC au lieu de répéter leur code
function calculerFacture(float $prixUnitaire, int $quantite, float $remise = 0, float $tva = 20.0): float
{
    $totalHt = $prixUnitaire * $quantite;
    $apresRemise = calculerRemise($totalHt, $remise);
    return round(calculerTTC($apresRemise, $tva), 2);
}

function formaterMontant(float $montant): string
{
    return number_format($montant, 2, ',', ' ') . ' MAD';
}

// ---------- Étape 4 : statistiques et géométrie ----------
function moyenne(float ...$notes): float
{
    // Cas « aucune note » : on évite la division par zéro
    if (count($notes) === 0) {
        return 0.0;
    }
    return array_sum($notes) / count($notes);
}

function aireCercle(float $rayon): float
{
    return round(M_PI * $rayon ** 2, 2);
}

function perimetreCercle(float $rayon): float
{
    return round(2 * M_PI * $rayon, 2);
}

function celsiusVersFahrenheit(float $c): float
{
    return $c * 9 / 5 + 32;
}

function fahrenheitVersCelsius(float $f): float
{
    return ($f - 32) * 5 / 9;
}

function convertirDevise(float $montant, float $taux): float
{
    return round($montant * $taux, 2);
}

// ---------- Étape 5 : fonctions à boucle ----------
function factorielle(int $n): int
{
    if ($n < 0) {
        throw new InvalidArgumentException("La factorielle d'un nombre négatif n'existe pas");
    }
    $resultat = 1;
    for ($i = 2; $i <= $n; $i++) {
        $resultat *= $i;
    }
    return $resultat;
}

function fibonacci(int $n): array
{
    $suite = [];
    $a = 0;
    $b = 1;
    for ($i = 0; $i < $n; $i++) {
        $suite[] = $a;
        [$a, $b] = [$b, $a + $b];
    }
    return $suite;
}

// Algorithme d'Euclide
function pgcd(int $a, int $b): int
{
    while ($b !== 0) {
        [$a, $b] = [$b, $a % $b];
    }
    return abs($a);
}

// Défi : puissance sans ** ni pow() — version boucle
function puissance(float $x, int $n): float
{
    $resultat = 1.0;
    for ($i = 0; $i < abs($n); $i++) {
        $resultat *= $x;
    }
    return $n >= 0 ? $resultat : 1 / $resultat;
}

// Défi : version récursive
function puissanceRecursive(float $x, int $n): float
{
    if ($n < 0) {
        return 1 / puissanceRecursive($x, -$n);
    }
    if ($n === 0) {
        return 1.0;
    }
    return $x * puissanceRecursive($x, $n - 1);
}

// Fonction à nombre variable d'arguments (rappel de cours)
function somme(float ...$nombres): float
{
    return array_sum($nombres);
}
