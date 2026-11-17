<?php

declare(strict_types=1);

const SEUIL_ADMISSION = 10.0;
const SEUIL_RUPTURE = 10;

function titre(string $t): void
{
    echo PHP_EOL . "=== $t ===" . PHP_EOL;
}

function mention(float $moyenne): string
{
    if ($moyenne < 10) {
        return 'Ajourné';
    }
    if ($moyenne < 14) {
        return 'Passable';
    }
    if ($moyenne < 16) {
        return 'Assez bien';
    }
    return 'Bien';
}

// ---------- Étape 4 : fonctions de gestion de stock ----------
function ajouterProduit(array &$stock, string $ref, string $designation, float $prix, int $qte): bool
{
    if (array_key_exists($ref, $stock)) {
        return false; // la référence existe déjà
    }
    $stock[$ref] = ['designation' => $designation, 'prix' => $prix, 'quantite' => $qte];
    return true;
}

function retirerStock(array &$stock, string $ref, int $qte): bool
{
    // Référence inconnue ou quantité insuffisante : pas d'avertissement PHP
    if (!isset($stock[$ref]) || $stock[$ref]['quantite'] < $qte) {
        return false;
    }
    $stock[$ref]['quantite'] -= $qte;
    return true;
}

function valeurTotale(array $stock): float
{
    $total = 0.0;
    foreach ($stock as $produit) {
        $total += $produit['prix'] * $produit['quantite'];
    }
    return $total;
}

function produitsEnRupture(array $stock, int $seuil = SEUIL_RUPTURE): array
{
    $refs = [];
    foreach ($stock as $ref => $produit) {
        if ($produit['quantite'] < $seuil) {
            $refs[] = $ref;
        }
    }
    return $refs;
}

function afficherStock(array $stock): void
{
    foreach ($stock as $ref => $p) {
        printf("%-6s %-10s %9.2f MAD x %3d\n", $ref, $p['designation'], $p['prix'], $p['quantite']);
    }
}

// Pour aller plus loin : regroupement générique
function grouperPar(array $liste, string $cle): array
{
    $groupes = [];
    foreach ($liste as $element) {
        $groupes[$element[$cle]][] = $element;
    }
    return $groupes;
}

// ---------- Étape 1 : la fiche d'un stagiaire ----------
titre('Étape 1 : la fiche d\'un stagiaire');
$stagiaire = [
    'nom' => 'Alami',
    'prenom' => 'Sara',
    'age' => 21,
    'filiere' => 'Développement digital',
    'notes' => ['php' => 16, 'js' => 14, 'sql' => 15],
];

echo $stagiaire['prenom'] . ' ' . strtoupper($stagiaire['nom']) . ', '
    . $stagiaire['age'] . ' ans, filière ' . $stagiaire['filiere'] . PHP_EOL;

$stagiaire['ville'] = 'Casablanca';
$stagiaire['age'] = 22;
echo "Ville : {$stagiaire['ville']}, âge : {$stagiaire['age']}" . PHP_EOL;

echo "Email : " . ($stagiaire['email'] ?? 'non renseigné') . PHP_EOL;

$stagiaire['telephone'] = null;
echo "isset(telephone) : " . (isset($stagiaire['telephone']) ? 'true' : 'false') . PHP_EOL;
echo "array_key_exists(telephone) : " . (array_key_exists('telephone', $stagiaire) ? 'true' : 'false') . PHP_EOL;

unset($stagiaire['ville']);
echo "Clés restantes : " . implode(', ', array_keys($stagiaire)) . PHP_EOL;

echo "Note de PHP : " . $stagiaire['notes']['php'] . PHP_EOL;
printf("Moyenne : %.2f\n", array_sum($stagiaire['notes']) / count($stagiaire['notes']));
foreach ($stagiaire['notes'] as $matiere => $note) {
    echo "$matiere : $note/20" . PHP_EOL;
}

// ---------- Étape 2 : le panier d'achat ----------
titre('Étape 2 : le panier d\'achat');
$panier = [
    'Clavier' => 250,
    'Souris' => 90,
    'Ecran' => 1400,
    'Casque' => 320,
];

echo "Total du panier : " . array_sum($panier) . " MAD (" . count($panier) . " articles)" . PHP_EOL;

$tri = $panier;
arsort($tri);
$plusCher = array_key_first($tri);
$moinsCher = array_key_last($tri);
echo "Plus cher : $plusCher ({$panier[$plusCher]}) | Moins cher : $moinsCher ({$panier[$moinsCher]})" . PHP_EOL;

// array_map conserve les clés quand on lui passe un seul tableau
$remise = array_map(fn($p) => $p * 0.9, $panier);
echo "Prix après remise : ";
$morceaux = [];
foreach ($remise as $nom => $prix) {
    $morceaux[] = "$nom $prix";
}
echo implode(', ', $morceaux) . PHP_EOL;

// array_filter conserve aussi les clés
$chers = array_filter($panier, fn($p) => $p > 200);
echo "Articles > 200 MAD : " . implode(', ', array_keys($chers)) . PHP_EOL;

$copie = $panier;
ksort($copie);
echo "Par nom : " . implode(', ', array_keys($copie)) . PHP_EOL;
$copie = $panier;
asort($copie);
echo "Prix croissant : " . implode(', ', array_keys($copie)) . PHP_EOL;
$copie = $panier;
arsort($copie);
echo "Prix décroissant : " . implode(', ', array_keys($copie)) . PHP_EOL;

// Ajout de Webcam seulement s'il n'existe pas ; sinon on incrémente la quantité
$quantites = [];
foreach (['Webcam', 'Webcam'] as $produit) {
    if (!array_key_exists($produit, $panier)) {
        $panier[$produit] = 180;
        $quantites[$produit] = 1;
    } else {
        $quantites[$produit] = ($quantites[$produit] ?? 1) + 1;
    }
}
echo "Quantités : " . json_encode($quantites) . PHP_EOL;

foreach ($panier as $produit => $prix) {
    printf("%-10s %8.2f MAD\n", $produit, $prix);
}

// ---------- Étape 3 : tableaux multidimensionnels ----------
titre('Étape 3 : la classe de stagiaires');
$stagiaires = [
    ['nom' => 'Alami', 'filiere' => 'Développement', 'notes' => [16, 14, 15]],
    ['nom' => 'Benani', 'filiere' => 'Infrastructure', 'notes' => [11, 13, 12]],
    ['nom' => 'Chraibi', 'filiere' => 'Développement', 'notes' => [18, 17, 19]],
    ['nom' => 'Daoudi', 'filiere' => 'Infrastructure', 'notes' => [9, 10, 8]],
];

// foreach par référence, puis unset pour éviter d'écraser le dernier élément
foreach ($stagiaires as &$s) {
    $s['moyenne'] = array_sum($s['notes']) / count($s['notes']);
    $s['mention'] = mention($s['moyenne']);
}
unset($s);

echo "Noms : " . implode(', ', array_column($stagiaires, 'nom')) . PHP_EOL;
echo "nom => moyenne : " . json_encode(array_column($stagiaires, 'moyenne', 'nom')) . PHP_EOL;

usort($stagiaires, fn($a, $b) => $b['moyenne'] <=> $a['moyenne']);
echo "Classement :" . PHP_EOL;
foreach ($stagiaires as $i => $s) {
    printf("%d. %s (%.2f)\n", $i + 1, $s['nom'], $s['moyenne']);
}

$meilleur = $stagiaires[0];
$moyenneClasse = array_sum(array_column($stagiaires, 'moyenne')) / count($stagiaires);
printf("Meilleur : %s | Moyenne générale : %.2f\n", $meilleur['nom'], $moyenneClasse);

$admis = array_filter($stagiaires, fn($s) => $s['moyenne'] >= SEUIL_ADMISSION);
echo "Admis : " . implode(', ', array_column($admis, 'nom')) . PHP_EOL;

$parFiliere = grouperPar($stagiaires, 'filiere');
$resume = [];
foreach ($parFiliere as $filiere => $liste) {
    $moy = array_sum(array_column($liste, 'moyenne')) / count($liste);
    echo "$filiere : effectif " . count($liste) . PHP_EOL;
    $resume[] = sprintf("Moyenne %s : %.2f", $filiere, $moy);
}
echo implode(' | ', $resume) . PHP_EOL;

foreach ($stagiaires as $s) {
    echo "{$s['nom']} → {$s['mention']}" . PHP_EOL;
}

// ---------- Étape 4 : mini-projet gestion de stock ----------
titre('Étape 4 : gestion de stock');
$stock = [
    'P001' => ['designation' => 'Clavier', 'prix' => 250.0, 'quantite' => 12],
    'P002' => ['designation' => 'Souris', 'prix' => 90.0, 'quantite' => 40],
    'P003' => ['designation' => 'Ecran', 'prix' => 1400.0, 'quantite' => 5],
];

echo "Ajout P004 : " . (ajouterProduit($stock, 'P004', 'Casque', 320.0, 8) ? 'ok' : 'refusé') . PHP_EOL;
echo "Ajout doublon P001 : " . (ajouterProduit($stock, 'P001', 'Clavier', 250.0, 1) ? 'ok' : 'refusé') . PHP_EOL;
echo "Retrait de 3 claviers : " . (retirerStock($stock, 'P001', 3) ? 'ok' : 'refusé') . PHP_EOL;
echo "Retrait de 100 écrans : " . (retirerStock($stock, 'P003', 100) ? 'ok' : 'refusé') . PHP_EOL;
echo "Retrait réf. inconnue : " . (retirerStock($stock, 'P999', 1) ? 'ok' : 'refusé') . PHP_EOL;

afficherStock($stock);
printf("Valeur totale : %.2f MAD\n", valeurTotale($stock));
echo "En rupture (< " . SEUIL_RUPTURE . ") : " . implode(', ', produitsEnRupture($stock)) . PHP_EOL;

// ---------- Pour aller plus loin : JSON ----------
titre('JSON');
$json = json_encode($stagiaires, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
echo $json . PHP_EOL;
$relu = json_decode($json, true);
echo "Relu : " . count($relu) . " stagiaires" . PHP_EOL;
