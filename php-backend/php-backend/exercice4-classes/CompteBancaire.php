<?php

declare(strict_types=1);

class CompteBancaire
{
    public const DEVISE = 'MAD';
    public const PLAFOND_RETRAIT = 5000.0;

    private static int $nombreComptes = 0;

    // Question de réflexion : pourquoi pas de setSolde() ni de setNumero() ?
    // - Le solde ne doit changer QUE par deposer() / retirer() / virerVers(), qui
    //   valident chaque opération. Un setSolde() permettrait à n'importe qui de
    //   mettre un solde arbitraire : l'objet pourrait alors devenir invalide.
    // - Un numéro de compte est un identifiant : il ne change jamais après la
    //   création. On le fixe dans le constructeur et on n'offre qu'un getter.
    private string $numero;
    private string $titulaire;
    private float $solde;

    public function __construct(string $numero, string $titulaire, float $soldeInitial = 0.0)
    {
        if (!self::estNumeroValide($numero)) {
            throw new InvalidArgumentException("Numéro de compte invalide : $numero");
        }
        if ($soldeInitial < 0) {
            throw new InvalidArgumentException("Le solde initial ne peut pas être négatif.");
        }

        $this->numero = $numero;
        $this->setTitulaire($titulaire); // on réutilise le setter pour valider le nom
        $this->solde = $soldeInitial;

        self::$nombreComptes++;
    }

    // --- Getters / setter ---
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

    // --- Méthodes d'instance ---
    public function deposer(float $montant): void
    {
        if ($montant <= 0) {
            throw new InvalidArgumentException("Le montant doit être strictement positif.");
        }
        $this->solde += $montant;
    }

    public function retirer(float $montant): void
    {
        if ($montant <= 0) {
            throw new InvalidArgumentException("Le montant doit être strictement positif.");
        }
        if ($montant > $this->solde) {
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
        $this->retirer($montant);           // si ça échoue, rien n'est déposé
        $destinataire->deposer($montant);
    }

    public function __toString(): string
    {
        return sprintf("%s - %s : %.2f %s", $this->numero, $this->titulaire, $this->solde, self::DEVISE);
    }

    // --- Méthodes de classe ---
    public static function getNombreComptes(): int
    {
        return self::$nombreComptes;
    }

    public static function estNumeroValide(string $numero): bool
    {
        return preg_match('/^CB\d{6}$/', $numero) === 1;
    }
}
