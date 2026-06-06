<?php

namespace App\Enums;

enum RoleUtilisateur: string
{
    case Utilisateur = 'user';
    case Administrateur = 'admin';

    public function estAdministrateur(): bool
    {
        return $this === self::Administrateur;
    }
}
