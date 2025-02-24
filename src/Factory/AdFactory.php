<?php

namespace App\Factory;

use App\Entity\Ad;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @extends PersistentProxyObjectFactory<Ad>
 */
final class AdFactory extends PersistentProxyObjectFactory
{

    private $httpClient;

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        $this->httpClient = HttpClient::create();
    }

    public static function class(): string
    {
        return Ad::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'author' => self::faker()->name(),
            'description' => self::faker()->paragraph(),
            'title' => self::faker()->sentence(),
            'imageFile' => self::getRandomImageUrl(),
            'category' => CategoryFactory::random(),
            'user' => UserFactory::random(),
            'created_at' => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('-1 month', 'now')),
            
        ];
    }

    private static function getRandomImageUrl(): UploadedFile
    {
        $faker = self::faker();
        $imagePath = sys_get_temp_dir() . '/' . $faker->uuid() . '.jpg';
        file_put_contents($imagePath, file_get_contents('https://picsum.photos/640/480'));
        $uploadsDir = __DIR__ . '/../../public/uploads/images/';
        $newImagePath = $uploadsDir . basename($imagePath);
        rename($imagePath, $newImagePath);

        return new UploadedFile(
            $newImagePath,
            basename($imagePath),
            'image/jpeg',
            null,
            true
        );   
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Ad $ad): void {})
        ;
    }
}
