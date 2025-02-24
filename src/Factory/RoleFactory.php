<?php

namespace App\Factory;

use App\Entity\Role;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * Factory pour créer des instances de rôles (Role).
 *
 * @extends PersistentProxyObjectFactory<Role>
 */
final class RoleFactory extends PersistentProxyObjectFactory
{
    /**
     * Liste des rôles par défaut.
     * 
     * Ces rôles seront créés lors de l'appel de la méthode `createRoles()`.
     *
     * @var array
     */
    private static $roles = [
        'ROLE_USER',
        'ROLE_ADMIN',
    ];

    /**
     * Constructeur de la factory.
     * 
     * Aucun service externe n'est injecté pour l'instant, mais cela peut être ajouté si nécessaire.
     *
     * @todo Injecter des services nécessaires si besoin.
     */
    public function __construct()
    {
    }

    /**
     * Retourne la classe cible pour la factory (Role).
     *
     * @return string
     */
    public static function class(): string
    {
        return Role::class;
    }

    /**
     * Définit les valeurs par défaut lors de la création d'un rôle.
     * 
     * Actuellement, seule la propriété 'name' est définie, mais elle pourrait être enrichie.
     *
     * @return array|callable
     */
    protected function defaults(): array|callable
    {
        return [
            // Nom du rôle (actuellement null par défaut)
            'name' => null,
        ];
    }

    /**
     * Initialisation de la factory.
     * 
     * Cette méthode permet d'ajouter des actions supplémentaires après l'instanciation d'un rôle, si nécessaire.
     * Pour l'instant, il n'y a pas d'actions supplémentaires définies.
     *
     * @return static
     */
    protected function initialize(): static
    {
        return $this
            // Exemple d'utilisation pour ajouter des actions après instanciation d'un rôle.
            // ->afterInstantiate(function(Role $role): void {})
        ;
    }

    /**
     * Crée les rôles par défaut définis dans la liste `$roles`.
     *
     * Cette méthode crée une instance de rôle pour chaque nom dans la liste des rôles
     * et les persiste dans la base de données.
     *
     * @return void
     */
    public static function createRoles(): void
    {
        // Parcours de la liste des rôles et création de chaque rôle.
        foreach (self::$roles as $rolesName) {
            // Création d'un rôle avec le nom et persistance dans la base de données.
            self::createOne(['name' => $rolesName]);
        }
    }
}
