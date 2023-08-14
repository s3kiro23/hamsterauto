## **Versions minimums**
<br />

Afin de s'assurer du bon fonctionnement des différents process de l'application, voici les versions minimums des technologies à respecter :

- WampServer 3.3.0
- MySql 8.0.31
- PHP 7.4.33

<br />
Vérifier également que la version de PHP est présente dans les variables d'environnement **système** de la machine:

![Alt text](docs\env_var.PNG?raw=true "Screen var env")

----------------

### **Config CRON pour l'envoi des mails et SMS automatique en Prod**
<br />

#### Une tâche cron tourne toutes les minutes :

    * *     * * *   root    /usr/sbin/cron.10sec > /dev/null

#### Puis on execute un script ("/usr/sbin/cron.10sec") qui va parcourir le dossier "/etc/cron.10sec" toutes les 10sec pendant 50sec et lancer les commandes présentent :

    #!/bin/bash

    for COUNT in `seq 5` ; do
        run-parts --report /etc/cron.10sec &
        disown
        sleep 10
    done

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
    127.0.0.1 hamsterauto.local

#### URL 
    hamsterauto.local:8001


