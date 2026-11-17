<?php

declare(strict_types=1);

abstract class CompteBancaire
{
    public const DEVISE = 'MAD';
    public const PLAFOND_RETRAIT = 5000.0;

    private static int $nombreComptes = 0;

    private string $numero;
    private string $titulaire;
    // protected : les classes filles en ont besoin (soldeDisponible)
    protected float $solde;

    public function __construct(string $numero, string $titulaire, float $soldeInitial = 0.0)
    {
        if (!self::estNumeroValide($numero)) {
            throw new InvalidArgumentException("Numéro de compte invalide : $numero");
        }
        if ($soldeInitial < 0) {
            throw new InvalidArgumentException("Le solde initial ne peut pas être négatif.");
        }

        $this->numero = $numero;
        $this->setTitulaire($titulaire);
        $this->solde = $soldeInitial;

        self::$nombreComptes++;
    }

    // Chaque type de compte doit dire quel est son type
    abstract public function getType(): string;

    // Montant maximal retirable. Par défaut : le solde. Les filles redéfinissent.
    protected function soldeDisponible(): float
    {
        return $this->solde;
    }

    public function getNumero(): string
    {
        return $this->numero;
    }

    public function getTitulaire(): string
    {
        return $this->titulaire;
    }

    public function getSolde(): float
    {
        return $this->solde;
    }

    public function setTitulaire(string $titulaire): void
    {
        $titulaire = trim($titulaire);
        if ($titulaire === '') {
            throw new InvalidArgumentException("Le nom du titulaire ne peut pas être vide.");
        }
        $this->titulaire = $titulaire;
    }

    public function deposer(float $montant): void
    {
        if ($montant <= 0) {
            throw new InvalidArgumentException("Le montant doit être strictement positif.");
        }
        $this->solde += $montant;
    }

    // Patron de méthode : l'algorithme est écrit ici une seule fois,
    // seule la notion de « solde disponible » varie selon la classe fille.
    public function retirer(float $montant): void
    {
        if ($montant <= 0) {
            throw new InvalidArgumentException("Le montant doit être strictement positif.");
        }
        if ($montant > $this->soldeDisponible()) {
            throw new RuntimeException("Solde insuffisant.");
        }
        if ($montant > self::PLAFOND_RETRAIT) {
            throw new RuntimeException("Plafond de retrait dépassé (" . self::PLAFOND_RETRAIT . ").");
        }
        $this->solde -= $montant;
    }

    public function virerVers(CompteBancaire $destinataire, float $montant): void
    {
        if ($destinataire === $this) {
            throw new InvalidArgumentException("Impossible de virer vers soi-même.");
        }
        $this->retirer($montant);
        $destinataire->deposer($montant);
    }

    public function __toString(): string
    {
        return sprintf(
            "[%s] %s - %s : %.2f %s",
            $this->getType(),
            $this->numero,
            $this->titulaire,
            $this->solde,
            self::DEVISE
        );
    }

    public static function getNombreComptes(): int
    {
        return self::$nombreComptes;
    }

    public static function estNumeroValide(string $numero): bool
    {
        return preg_match('/^CB\d{6}$/', $numero) === 1;
    }
}
