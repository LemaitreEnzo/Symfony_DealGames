<?php

namespace App\Factory;

use App\Entity\Category;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * Factory pour créer des instances de catégories (Category).
 *
 * @extends PersistentProxyObjectFactory<Category>
 */
final class CategoryFactory extends PersistentProxyObjectFactory
{
    /**
     * Liste des noms de catégories par défaut.
     * 
     * Ces catégories seront créées lors de l'appel de la méthode `createCategories()`.
     *
     * @var array
     */
    private static $categories = [
        'Jeux',
        'Accessoires',
        'Consoles',
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
     * Retourne la classe cible pour la factory (Category).
     *
     * @return string
     */
    public static function class(): string
    {
        return Category::class;
    }

    /**
     * Définit les valeurs par défaut lors de la création d'une catégorie.
     * 
     * Actuellement, seule la propriété 'name' est définie, mais elle pourrait être enrichie.
     *
     * @return array|callable
     */
    protected function defaults(): array|callable
    {
        return [
            // Nom de la catégorie (actuellement null par défaut)
            'name' => null,
        ];
    }

    /**
     * Initialisation de la factory.
     * 
     * Cette méthode permet d'ajouter des actions supplémentaires après l'instanciation d'une catégorie, si nécessaire.
     * Pour l'instant, il n'y a pas d'actions supplémentaires définies.
     *
     * @return static
     */
    protected function initialize(): static
    {
        return $this
            // Exemple d'utilisation pour ajouter des actions après instanciation d'une catégorie.
            // ->afterInstantiate(function(Category $category): void {})
        ;
    }

    /**
     * Crée les catégories par défaut définies dans la liste `$categories`.
     *
     * Cette méthode crée une instance de catégorie pour chaque nom dans la liste des catégories
     * et les persiste dans la base de données.
     *
     * @return void
     */
    public static function createCategories(): void
    {
        // Parcours de la liste des catégories et création de chaque catégorie.
        foreach (self::$categories as $categoryName) {
            // Création d'une catégorie avec le nom et persistance dans la base de données.
            self::createOne(['name' => $categoryName]);
        }
    }
}
