<?php

declare(strict_types=1);

// Composition : une banque « a des » comptes (elle n'est pas un compte)
class Banque
{
    /** @var array<string, CompteBancaire> comptes indexés par numéro */
    private array $comptes = [];

    public function __construct(private string $nom)
    {
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function ajouterCompte(CompteBancaire $compte): void
    {
        if (isset($this->comptes[$compte->getNumero()])) {
            throw new InvalidArgumentException("Le compte {$compte->getNumero()} existe déjà.");
        }
        $this->comptes[$compte->getNumero()] = $compte;
    }

    public function trouverCompte(string $numero): ?CompteBancaire
    {
        return $this->comptes[$numero] ?? null;
    }

    public function totalDepots(): float
    {
        return array_sum(array_map(fn(CompteBancaire $c) => $c->getSolde(), $this->comptes));
    }

    public function crediterInterets(): int
    {
        $nombre = 0;
        foreach ($this->comptes as $compte) {
            if ($compte instanceof CompteEpargne) {
                $compte->ajouterInterets();
                $nombre++;
            }
        }
        return $nombre;
    }

    public function afficherComptes(): void
    {
        foreach ($this->comptes as $compte) {
            echo $compte . PHP_EOL; // polymorphisme sur __toString / getType
        }
    }

    public function comptesParType(): array
    {
        $types = [];
        foreach ($this->comptes as $compte) {
            $type = $compte->getType();
            $types[$type] = ($types[$type] ?? 0) + 1;
        }
        return $types;
    }

    // Pour aller plus loin : tri polymorphe par solde décroissant
    public function comptesTriesParSolde(): array
    {
        $liste = array_values($this->comptes);
        usort($liste, fn(CompteBancaire $a, CompteBancaire $b) => $b->getSolde() <=> $a->getSolde());
        return $liste;
    }
}
