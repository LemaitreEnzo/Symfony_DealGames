<?php

namespace App\Factory;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<User>
 */
final class UserFactory extends PersistentProxyObjectFactory
{
    private UserPasswordHasherInterface $passwordHasher;
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     * 
     * @param UserPasswordHasherInterface $passwordEncoder
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public static function class(): string
    {
        return User::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {    
        return [
            'email' => self::faker()->email(),
            'firstname' => self::faker()->firstName(),
            'isVerified' => self::faker()->boolean(true),
            'lastname' => self::faker()->lastName(),
            'password' => $this->passwordHasher->hashPassword(new User(), "azerty"),
            'roles' => $this->generateRoles(),
        ];
    }

    /**
     * Génère un tableau de rôles avec ROLE_USER par défaut et parfois ROLE_ADMIN
     */
    private function generateRoles(): array
    {
        $roles = ["ROLE_USER"];

        if (self::faker()->boolean(10)) {
            $roles[] = "ROLE_ADMIN";
        }

        return $roles;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(User $user): void {})
        ;
    }
}
