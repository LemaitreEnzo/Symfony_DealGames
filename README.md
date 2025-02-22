# DealGames

## Fonctionnalités de l'application

L'application **DealGames** doit permettre à tous les internautes de visualiser l’ensemble des annonces en ligne. Les principales fonctionnalités sont les suivantes :

### Gestion des annonces

- **Créer une annonce** : Publier une nouvelle annonce avec un titre, une description, une référence de l’auteur, une photo, une date de publication et une catégorie (consoles, jeux, accessoires).
- **Modifier une annonce** : Modifier les informations d'une annonce existante.
- **Afficher la liste des annonces** : Voir toutes les annonces disponibles en ligne.
- **Afficher les détails d’une annonce** : Voir les détails complets d'une annonce spécifique.
- **Supprimer une annonce** : Supprimer une annonce existante.

### Gestion de l’espace membre

- **Authentification** : Permettre aux utilisateurs de se connecter à leur compte.
- **Déconnexion** : Permettre aux utilisateurs de se déconnecter de leur compte.
- **Se souvenir de moi** : Option pour rester connecté.
- **Réinitialisation du mot de passe** : Permettre aux utilisateurs de réinitialiser leur mot de passe.
- **Création de compte utilisateur** : Permettre aux nouveaux utilisateurs de créer un compte.
- **Validation du compte par la vérification de l’adresse mail** : Envoyer un email de validation pour activer le compte.

### Gestion de profil

- **Visualisation du profil** : Voir les informations de profil d'un utilisateur.
- **Modification des informations du profil** : Permettre aux utilisateurs de modifier leurs informations personnelles.
- **Modification du mot de passe** : Permettre aux utilisateurs de changer leur mot de passe.

### Gestion des rôles

- **Rôle pour la gestion des annonces** : Seul l’auteur d’une annonce a les droits d’administration (modification, suppression) sur celle-ci.
- **Rôle pour la gestion du profil** : Les utilisateurs peuvent gérer leur propre profil.
- **Rôle d’administrateur** : Confère des droits sans restriction sur toutes les sections de l’application.


## Ressources

- Documentation officielle Symfony 5 : [Symfony Documentation](https://symfony.com/doc/5.4/index.html)
- Installation de Docker : [Guide Docker](https://korben.info/installer-docker-windows-home.html)
- Tutoriels Symfony 5 : [Symfonycasts](https://symfonycasts.com/tracks/symfony)
- Utilisation de Docker avec Symfony : [Yoandev](https://yoandev.co/un-environnement-de-d%C3%A9veloppement-symfony-5-avec-docker-et-docker-compose/)
- Authentification avec Symfony 5 et PHP 8 : [Nouvelle Techno](https://nouvelle-techno.fr/articles/symfony-5-3-6-authentification-notions-avancee-php8)
- Bundles intéressants :
  - Upload d’images : [VichUploaderBundle](https://github.com/dustin10/VichUploaderBundle)
  - Redimensionnement et optimisation d’images : [LiipImagineBundle](https://github.com/dustin10/VichUploaderBundle)
  - Rendre les dates plus friendly : [KnpTimeBundle](https://github.com/KnpLabs/KnpTimeBundle)
  - Filtres et helpers pour le formatage de texte : [TwigExtraBundle](https://github.com/twigphp/twig-extra-bundle)

