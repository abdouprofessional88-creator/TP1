<?php

declare(strict_types=1);

class CompteEpargne extends CompteBancaire
{
    public const SOLDE_MINIMUM = 100.0;

    private float $taux; // en %

    public function __construct(string $numero, string $titulaire, float $soldeInitial, float $taux)
    {
        parent::__construct($numero, $titulaire, $soldeInitial);
        if ($taux < 0 || $taux > 100) {
            throw new InvalidArgumentException("Le taux doit être compris entre 0 et 100.");
        }
        $this->taux = $taux;
    }

    // Un compte épargne doit toujours conserver SOLDE_MINIMUM
    protected function soldeDisponible(): float
    {
        return max(0, $this->solde - self::SOLDE_MINIMUM);
    }

    public function getType(): string
    {
        return 'Épargne';
    }

    public function calculerInterets(): float
    {
        return $this->solde * $this->taux / 100;
    }

    public function ajouterInterets(): void
    {
        $this->deposer($this->calculerInterets());
    }

    public function __toString(): string
    {
        return parent::__toString() . " (taux {$this->taux} %)";
    }
}
