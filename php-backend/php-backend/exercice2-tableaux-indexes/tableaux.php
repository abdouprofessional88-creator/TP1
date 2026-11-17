<?php

declare(strict_types=1);

// Seuil de réussite : une constante, pas de « valeur magique » dans le code
const SEUIL_ADMISSION = 10.0;

// ---------- Étape 8 : les fonctions du carnet de notes ----------
function moyenne(array $notes): float
{
    // Tableau vide : on retourne 0 au lieu de diviser par zéro
    return $notes === [] ? 0.0 : array_sum($notes) / count($notes);
}

function mediane(array $notes): float
{
    if ($notes === []) {
        return 0.0;
    }
    sort($notes); // $notes est une copie locale : l'original n'est pas modifié
    $n = count($notes);
    $milieu = intdiv($n, 2);
    return $n % 2 === 1
        ? (float) $notes[$milieu]
        : ($notes[$milieu - 1] + $notes[$milieu]) / 2;
}

function admis(array $notes, float $seuil = SEUIL_ADMISSION): array
{
    // array_values pour réindexer après array_filter
    return array_values(array_filter($notes, fn($n) => $n >= $seuil));
}

function meilleures(array $notes, int $n = 3): array
{
    rsort($notes);
    return array_slice($notes, 0, $n);
}

function histogramme(array $notes): void
{
    foreach ($notes as $i => $note) {
        printf("Note %d : %s (%s)\n", $i + 1, str_repeat('*', (int) $note), $note);
    }
}

function mention(float $note): string
{
    if ($note < 10) {
        return 'Ajourné';
    }
    if ($note < 14) {
        return 'Passable';
    }
    if ($note < 16) {
        return 'Assez bien';
    }
    return 'Bien';
}

function titre(string $t): void
{
    echo PHP_EOL . "=== $t ===" . PHP_EOL;
}

// ---------- Jeu de données (ne pas modifier : on travaille sur des copies) ----------
$notes = [12, 15, 8, 17, 9, 14, 11];
$prenoms = ['Sara', 'Youssef', 'Imane', 'Omar', 'Khadija'];

// ---------- Étape 1 : lecture et parcours ----------
titre('Étape 1 : lecture et parcours');
echo "Première note : " . $notes[0] . PHP_EOL;
echo "Dernière note : " . $notes[array_key_last($notes)] . PHP_EOL;
echo "Nombre de notes : " . count($notes) . PHP_EOL;

for ($i = 0; $i < count($notes); $i++) {
    echo "Note n°" . ($i + 1) . " : " . $notes[$i] . PHP_EOL;
}
foreach ($notes as $i => $note) {
    echo "Note n°" . ($i + 1) . " : $note" . PHP_EOL;
}
foreach ($prenoms as $i => $prenom) {
    echo ($i + 1) . ". $prenom" . PHP_EOL;
}

// ---------- Étape 2 : ajouter, retirer, insérer ----------
titre('Étape 2 : ajouter, retirer, insérer');
$a = $notes;
$a[] = 16;                 // façon 1 : crochets vides
$b = $notes;
array_push($b, 16);        // façon 2 : array_push
echo "Façon 1 : " . implode(', ', $a) . PHP_EOL;
echo "Façon 2 : " . implode(', ', $b) . PHP_EOL;

$t = $a;
array_unshift($t, 10);
echo "Après ajout de 10 au début : " . implode(', ', $t) . PHP_EOL;

$derniere = array_pop($t);
$premiere = array_shift($t);
echo "Retirées : dernière = $derniere, première = $premiere" . PHP_EOL;
echo "Tableau : " . implode(', ', $t) . PHP_EOL;

array_splice($t, 2, 0, [13]); // insère 13 à l'indice 2 sans rien écraser
echo "Après insertion de 13 à l'indice 2 : " . implode(', ', $t) . PHP_EOL;

unset($t[1]);
echo "Après unset(\$t[1]) (notez le « trou ») : ";
print_r($t);
$t = array_values($t);
echo "Après array_values : " . implode(', ', $t) . PHP_EOL;

// ---------- Étape 3 : recherche ----------
titre('Étape 3 : recherche');
echo "La note 17 existe ? " . (in_array(17, $notes) ? 'oui' : 'non') . PHP_EOL;
$indice = array_search(9, $notes);
// === false : array_search peut retourner 0 (premier indice)
echo $indice === false ? "La note 9 n'existe pas" : "La note 9 est à l'indice $indice";
echo PHP_EOL;
echo "Omar dans la liste ? " . (in_array('Omar', $prenoms, true) ? 'oui' : 'non') . PHP_EOL;

// ---------- Étape 4 : statistiques ----------
titre('Étape 4 : statistiques');
$notes = [12, 15, 8, 17, 9, 14, 11];
$somme = array_sum($notes);
printf(
    "Somme : %d | Moyenne : %.2f | Min : %d | Max : %d\n",
    $somme,
    round($somme / count($notes), 2),
    min($notes),
    max($notes)
);

$compteur = 0;
foreach ($notes as $n) {
    if ($n >= SEUIL_ADMISSION) {
        $compteur++;
    }
}
$compteur2 = count(array_filter($notes, fn($n) => $n >= SEUIL_ADMISSION));
echo "Notes >= 10 : $compteur (boucle) | $compteur2 (array_filter)" . PHP_EOL;
printf(
    "Admis (>= 10) : %d sur %d (%.2f %%)\n",
    $compteur,
    count($notes),
    $compteur / count($notes) * 100
);
echo "Médiane : " . mediane($notes) . PHP_EOL;

// ---------- Étape 5 : tri et extraction ----------
titre('Étape 5 : tri et extraction');
$copie = $notes;
sort($copie);
echo "Croissant : " . implode(', ', $copie) . PHP_EOL;
$copie = $notes;
rsort($copie);
echo "Décroissant : " . implode(', ', $copie) . PHP_EOL;
echo "3 meilleures notes : " . implode(', ', array_slice($copie, 0, 3)) . PHP_EOL;

$tries = $prenoms;
sort($tries);
echo "Prénoms triés : " . implode(', ', $tries) . PHP_EOL;
echo "Prénoms inversés : " . implode(', ', array_reverse($tries)) . PHP_EOL;
echo "Notes d'origine intactes : " . implode(', ', $notes) . PHP_EOL;

// ---------- Étape 6 : array_map / array_filter / array_reduce ----------
titre('Étape 6 : transformer');
$bonus = array_map(fn($n) => min($n + 1, 20), $notes);
echo "Avec bonus : " . implode(', ', $bonus) . PHP_EOL;

$tableauAdmis = array_values(array_filter($notes, fn($n) => $n >= SEUIL_ADMISSION));
echo "Admis : " . implode(', ', $tableauAdmis) . PHP_EOL;

$total = array_reduce($notes, fn($cumul, $n) => $cumul + $n, 0);
echo "Total (array_reduce) : $total" . PHP_EOL;

echo "Prénoms en majuscules : " . implode(', ', array_map('strtoupper', $prenoms)) . PHP_EOL;

$mentions = array_map(fn($n) => "$n → " . mention((float) $n), $notes);
echo "Mentions : " . implode(' | ', $mentions) . PHP_EOL;

// ---------- Étape 7 : chaînes, fusion et ensembles ----------
titre('Étape 7 : chaînes, fusion, ensembles');
echo implode(', ', $prenoms) . PHP_EOL;

$langages = array_unique(explode(',', 'php,js,sql,php,html,js'));
echo "Sans doublons : " . implode(', ', $langages) . PHP_EOL;

$fusion = array_unique(array_merge(['php', 'sql'], ['js', 'sql']));
echo "Fusion dédoublonnée : " . implode(', ', $fusion) . PHP_EOL;

$a = ['php', 'js', 'sql'];
$b = ['js', 'html', 'sql'];
echo "Communs : " . implode(', ', array_intersect($a, $b)) . PHP_EOL;
echo "Seulement dans a : " . implode(', ', array_diff($a, $b)) . PHP_EOL;

$paquets = array_chunk(range(1, 10), 3);
echo "Paquets de 3 : ";
foreach ($paquets as $p) {
    echo '[' . implode(',', $p) . '] ';
}
echo PHP_EOL;

// ---------- Étape 8 : carnet de notes ----------
titre('Étape 8 : carnet de notes');
printf("Moyenne : %.2f\n", moyenne($notes));
echo "Médiane : " . mediane($notes) . PHP_EOL;
echo "Admis : " . implode(', ', admis($notes)) . PHP_EOL;
echo "Meilleures : " . implode(', ', meilleures($notes)) . PHP_EOL;
histogramme($notes);

echo PHP_EOL . "--- Avec un tableau vide (aucune erreur attendue) ---" . PHP_EOL;
$vide = [];
echo "Moyenne : " . moyenne($vide) . PHP_EOL;
echo "Médiane : " . mediane($vide) . PHP_EOL;
echo "Admis : [" . implode(', ', admis($vide)) . "]" . PHP_EOL;
echo "Meilleures : [" . implode(', ', meilleures($vide)) . "]" . PHP_EOL;
histogramme($vide);
echo "(histogramme vide : rien à afficher)" . PHP_EOL;
