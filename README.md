<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

## Installation

Prerequis :

ce petit guide sapplique a windows, pour dautre plateforme voir la documentation

2 option soffre a vous sail(docker) ou xampp/wamp(old school)

1.  wamp , xampp composer
    wamp: https://www.wampserver.com/
    ou
    xampp: https://www.apachefriends.org/fr/index.html
    composer : https://getcomposer.org/download/

ou

1.  docker et laravel sail
    avoir un environnement Docker

    1. linux: https://docs.docker.com/engine/install/

    avoir un environnement Docker/wsl

    1. docker (windows uniquement): https://docs.docker.com/desktop/windows/install/
    1. wsl2 (windows uniquement):https://docs.docker.com/desktop/windows/wsl/
    1. installer windows terminal : https://www.microsoft.com/store/productId/9N0DX20HK701 1. installer un ubuntu (1..1.):https://www.microsoft.com/store/productId/9N6SVWS3RX71

    1. installer vscode et remote - wsl

2.  clone le projet depuis ce repo:
    soit avec github desktop sois avec la commandes git clone

3.  installer les dependances composer :

        1. soit avec composer directement installer sur votre machine : composer install

        2.  soit avec docker :
        docker run --rm \
         -u "$(id -u):$(id -g)" \
         -v $(pwd):/var/www/html \
         -w /var/www/html \
         laravelsail/php81-composer:latest \
         composer install --ignore-platform-reqs

4.  Creations des bases de données

            1.(xamp wamp):
            acceder a votre instance mysql et cree 2 base de donnee:
              api_fruits
              test_api_fruit

            2. lancer la creation de vos container mysql et http

            .vendor/bin/sail up -d

            Acceder en root au CLI de mysql
            et cree la BDD de test

            Create database test_api_fruit;

            grant all on *.* to 'sail'@'%';

            flush privileges;

    en cas de probleme a ce niveau ( probleme de mot de passe ou de nom de bdd la commande

        .vendor/bin/sail down -v

    permet de supprimer les container et surtout effacer les volumes)

5.  Creation de lapplication key
    via php (wamp,xamp...) :

        php artisan key:generate

    via docker:

        ./vendor/bin/sail artisan key:generate

6.  remplir la bdd

    1.  jouer les migration et les seeder

            xamp,wamp :

                php artisan migrate:fresh --seed

            docker :

                .vendor/bin/sail artisan migrate:fresh --seed

    tester un endpoint

        taper laddresse suivate sur    votre
        navigateur :

                localhost/api/recipes

    1. si aucune erreur apparait

        1. vous etes chanceux
        1. bien jouer

    1. si une erreur survient
        1. bonne chance et
        1. bonne decouverte de laravel et stack overflow

7.  lancer les test

        .vendor/bin/sail artisan test --env=testing --coverage-html report/

    un code coverage seras alors disponible sur lurl suivante

        via le plug-in go live sur vscode
        http://localhost:5500/report/index.html
