### Compte MAIL :

- user = shadow.s3kir0@gmail.com
- pwd = @flaut0!@20
- pwd appli (générer compte gmail) = qfujcjiaoxuuhqni


## Procédure d'installation du projet avec docker :

### Pré-requis

- Un IDE
- WSL 2 : https://wslstorestorage.blob.core.windows.net/wslblob/wsl_update_x64.msi

Puis taper en ligne de commande administrateur :
    `wsl --set-default-version 2`

- Docker Desktop : https://tinyurl.com/mrktxm5z


## Commandes importantes pour init le projet :

### Depuis la racine du depo git (./) :
*init le build des containers docker (1 serveur apache, 1 database mysql, 1 phpmyadmin).*

-> Si environnement de développement :

    docker compose up -d

*cela aura pour effet de bind le dossier racine de l'application à celui du container pour avoir les changements en live sur le code. (sorte de --watch)*

#### Puis lancer l'install des dépendences (./app) :

    composer install


#### Accès au terminal du docker Apache :
    docker exec -it srv_apache /bin/bash

### URL d'accès au projet :

#### Ajouté au fichier host de la machine local en admin (C:\Windows\System32\drivers\etc) la ligne suivante :
    127.0.0.1 control-tech.local

#### URL 
    controle-tech.local:8001


