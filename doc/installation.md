# Installer l’application Trouver un masque

Actuellement, cette version du code est encore à l’état de **prototype**.

La plateforme **ne fonctionne pas encore**, ne l’installez que pour collaborer au développement du projet.

## Prérequis

- Serveur Apache
- Base de données (MySQL, par exemple)
- PHP 7.1 ou plus récent
- [Composer](https://getcomposer.org/download/)
- Pour le reste des prérequis, voir la [documentation Symfony](https://symfony.com/doc/current/setup.html).

## Créer le vhost Apache

Par sécurité, votre site devrait tourner **exclusivement** en https.

La racine publique du site doit pointer vers le dossier `public` de cette application.

```apacheconfig
# …
DocumentRoot /home/votre_site/public_html/public
# …
<Directory /home/votre_site/public_html/public>
# …
</Directory>
# …
```
## Installer l’application

Récupérer une copie du dépôt git sur votre serveur :

```shell script
$ git clone https://github.com/Trouver-un-masque/trouver-un-masque.git ./
```

Installer le code source sur le site web que vous avez créé :

```shell script
$ git --work-tree=/home/votre_site/public_html checkout -f master
```

La branche `master` contiendra la version stable du code.

Copier le fichier `.env` dans `.env.local` et paramétrer votre instance de l’application :

```shell script
$ cd /home/votre_site/public_html
$ cp .env .env.local
$ nano .env.local
```

Pour plus d’informations sur le format du contenu du fichier `.env.local`, consultez
la documentation de Symfony à ce sujet (voir l’url dans le fichier)

Installer les dépendances de l’application :

```shell script
$ composer install
```

Mettre à jour le schéma de la base de données :

```shell script
$ php bin/console doctrine:migrations:migrate
```

Affaire à suivre 🚧
