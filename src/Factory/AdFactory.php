<?php

namespace App\Factory;

use App\Entity\Ad;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Factory pour créer des instances d'annonces (Ad).
 *
 * @extends PersistentProxyObjectFactory<Ad>
 */
final class AdFactory extends PersistentProxyObjectFactory
{
    private $httpClient;

    /**
     * Constructeur de la factory.
     * 
     * Crée une instance de HttpClient pour effectuer des requêtes HTTP si nécessaire.
     *
     * @todo Injecter les services nécessaires si besoin.
     */
    public function __construct()
    {
        // Création d'un client HTTP pour récupérer des images aléatoires.
        $this->httpClient = HttpClient::create();
    }

    /**
     * Retourne la classe cible pour la factory (Ad).
     *
     * @return string
     */
    public static function class(): string
    {
        return Ad::class;
    }

    /**
     * Valeurs par défaut pour la création d'une annonce.
     * 
     * Les valeurs par défaut sont définies à l'aide de Faker pour générer des données aléatoires.
     * Exemple : un auteur généré aléatoirement, une description, un titre, une image, etc.
     *
     * @return array|callable
     */
    protected function defaults(): array|callable
    {
        return [
            // Auteur de l'annonce (nom généré aléatoirement)
            'author' => self::faker()->name(),
            // Description de l'annonce (paragraphe généré aléatoirement)
            'description' => self::faker()->paragraph(),
            // Titre de l'annonce (phrase générée aléatoirement)
            'title' => self::faker()->sentence(),
            // Image aléatoire récupérée via une URL externe
            'imageFile' => self::getRandomImageUrl(),
            // Catégorie de l'annonce (tirée d'une autre factory)
            'category' => CategoryFactory::random(),
            // Utilisateur (tiré d'une autre factory)
            'user' => UserFactory::random(),
            // Date de création de l'annonce (date générée aléatoirement)
            'created_at' => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('-1 month', 'now')),
        ];
    }

    /**
     * Génère une image aléatoire à partir de Picsum.
     *
     * Récupère une image depuis 'https://picsum.photos', la sauvegarde localement, 
     * puis renvoie un objet UploadedFile pour être utilisé dans la création d'une annonce.
     *
     * @return UploadedFile
     */
    private static function getRandomImageUrl(): UploadedFile
    {
        // Initialisation de Faker
        $faker = self::faker();
        // Génération d'un chemin temporaire pour l'image
        $imagePath = sys_get_temp_dir() . '/' . $faker->uuid() . '.jpg';
        
        // Téléchargement de l'image depuis Picsum et sauvegarde dans le répertoire temporaire
        file_put_contents($imagePath, file_get_contents('https://picsum.photos/640/480'));
        
        // Déplacement de l'image vers le répertoire de téléchargements
        $uploadsDir = __DIR__ . '/../../public/uploads/images/';
        $newImagePath = $uploadsDir . basename($imagePath);
        rename($imagePath, $newImagePath);

        // Retourne un objet UploadedFile représentant l'image
        return new UploadedFile(
            $newImagePath,
            basename($imagePath),
            'image/jpeg',
            null,
            true
        );
    }

    /**
     * Initialisation de la factory.
     *
     * Permet de définir des actions supplémentaires à effectuer après l'instanciation d'un objet.
     *
     * @return static
     */
    protected function initialize(): static
    {
        return $this
            // Par exemple, on pourrait ajouter une action après instantiation d'une annonce.
            // ->afterInstantiate(function(Ad $ad): void {})
        ;
    }
}
