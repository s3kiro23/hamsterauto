# v1.0.74

### #checklist_responsive

- [UPD] responsive pour la checklist + refactoring de la structure

### #fix_designResponsive_JM

- [FIX] responsive de la navbar + ajustement margin
- [UPD] design + responsive sur les différentes pages de connexion + pwd oublié etc..
- [ADD] wizard coté dashclient + index

### #templateMail_and_headerPDF

- [a6e5f66](https://github.com/pguiderdoni/controle_tech/commit/a6e5f66db02a8d9c6029ad3133a5ccd2de3fb987) [FIX] chemin vers les images lors de la génération d'un pdf
- [7da2e1f](https://github.com/pguiderdoni/controle_tech/commit/7da2e1f26dae6f2e4e9cd61370888a599ae1f094) [ADD] d'un QRCODE lors de la génération des pv renvoyant vers le site d'Aflokkat pour le moment, à modifier
- [17e74cf](https://github.com/pguiderdoni/controle_tech/commit/17e74cf4cb1aad31d5974f24db3c15b3d2300e32) [ADD] d'un header custom pour la génération des pv + background sur la vignette de CT
- [c98d23c](https://github.com/pguiderdoni/controle_tech/commit/c98d23c9cd61af9f740d0a27698cd459d763f102) [UPD] du composer.json pour installer le bundle intl-tel-input + modif du chemin script en cas d'utilisation + suppression des CDN inutilisés
- [a6c04fd](https://github.com/pguiderdoni/controle_tech/commit/a6c04fd434902af0e8b1283cbd92450699fe4ca6) [UPD] du template mail et modification de l'emplacement du script des jobs 

### #improve-logs
  
- [16215d5](https://github.com/pguiderdoni/controle_tech/commit/16215d51185e5ba22c186540d05c141c46fd89fb) [UPD] .gitignore pour les dossiers .idea, vendor, etc..
- [FIX] bug sur l'image de profil si le champ est null
- [MV] des scripts dans un dossier "script" et rename des dossiers 
- [RENAME] dossier migration en migration(s)
- [ADD] migration pour l'ajout du field img-profile en BDD
- [ADD] nouvelle class Traces + rename class Error -> LoginAttempts
- [FIX] bug sur l'envoi de mail lors de la récupération d'un pwd
- [UPD] des controller pour tracer les actions user en BDD
- [DEL] des lignes associées à l'ancien write_logs et comment de la fonction

# v1.0.73

### #message expiration de session

- [ADD] avant la fin de session: message "etes vous encore là"?
        (actuellement sessions de 30min, message 1min avant la fin)
- [UPD] désactivation sidebar

### #image-profile

- [ADD] possibilité de changer son image de profil
- [ADD] champ en BDD pour path image
- [UPD] page profil et header pour affichage

### #pierre-mailInscription

- [ADD] envoie d'un mail de confirmation lors de l'inscription 


# v1.0.72

### #exec-cmd

- [UPD] Sortie des traitements d'envoi de mail et sms du thread principal php
- [ADD] Création table Queued + Classe et méthodes associées
- [UPD] Mise à jour des controller utilisant les mails et sms pour générer un job en BDD
- [ADD] Création d'un script pour parcourir les entrées en base et executer les jobs en attentes


# v1.0.7

### #navbar-rebuild

- [ADD] image de profil modern + prénom/nom
- [REVIEW] structure de la navbar pour responsive
- [ADD] d'un autohide au scroll
- [ADD] affichage nombre de pages historique client

### #contact

- [ADD] formulaire de contact avec mailing
- [ADD] génération d'une map google avec settings en BDD + marqueurs + infos
- [ADD] rédaction CGU + mentions légales

### #statut-ouverture

- [ADD] affichage à l'accueil du statut ouvert/fermé, ouvre bientot/ferme bientot


# V1.0.6

### #expiration-session

- [ADD] mise en place expiration de session

# V1.0.55

### #pdf-generator

- [ADD] Déploiement génération de document PDF
- [ADD] Création d'un gabarit pour les pv lors de la validation des CT
- [UPD] mise à jour class Mailing pour envoi des pdf
- [ADD] champ en BDD pour accueillir les docs générés
- [ADD] chiffrement des documents lors du set en BDD
- [UPD] Harmonisation des boutons action tech "prise en charge"

### #design-BOTechTemp

- [UPD] Harmonisation des tabs backoffice/client + designTemp sidebar pour les pages manquantes
- [FIX] correction bug IntlTelInput sur les forms | bug swalMixin profil et index | bug modify-pwd utilisateur
- [ADD] démo suivi temps réel coté client
- [UPD] Modif lien controle_tech dans les mails
- [UPD] Ajustement footer page change-password.html
- [ADD] bouton retour sur change pwd user

### #refactor dossiers JS

- [UPD] Reorganisation + optimisation du code JS

# V1.0.5

### #update-swal-dismiss

- [ADD] impossibilité de skip les Swal avec un gros traitement (checklist, supp RDV...)
- [ADD] IntlTelinput sur index + renommage class Setting & function
- [ADD] option select pour modèle marque form et véhicule
- [FIX] bug checkfield sur marque/modele + bug affichage erreur sur tous les forms lorsque que marque/modele n'est pas select


### #script-bans

- [ADD] script de déblocage de compte (dossier script_ban à la racine de controle_tech)


### #pwd-modify

- [ADD] sous menu dropdown dans le header pour modifier le mot de passe user
- [UPD] de la fonction recovery pour permettre une utilisation dans les deux cas (request et modif user)
- [UPD] du JS pour accepter les requêtes interne vers la page change-password.html

### #multi-tech

- [UPD] de la fonction "dayCases" pour permettre la gestion de plusieurs "ponts" et du coup multi-slot.
- [ADD] champ dans la table settings pour gérer dynamiquement le nombre de slots disponible.

### #logs

- [UPD] Génération de logs sur chaque action users du site

### #authorization

- [ADD] controller "authorization" pour gérer les accès sur les controller
- [ADD] Gestion d'accès sur :
    - clientController
    - backofficeController
    - checklistController
    - les 2 tablesDisplay
    - change-password.html (via jquery avec un if token=0, etc...)
- [ADD] controller checkFieldController pour les besoins de gestion d'accès (préalablement soucis sur l'index)
  suite harmonisation sur index forgot pwd
- [FIX] bug des icônes sur le menu dropdown client


### #sweetalert et loaders

- [UPD] Izitoasts degagés, harmonisation sweetalert, loaders hamster
- [UPD] ré ecriture des messages sweet alerte (harmonisation)
- [ADD] d'une demande confirmation avant validation CT
- [UPD] menu dropdown (backoffice ok, dashclient tjrs buggé)

# v1.0.45

### #design-responsive

- [UPD] refonte page profil + modal modifier
- [UPD] refonte tableau dashboardClient
- [ADD] lib IntlTelInput pour flag tél

### #refactor-MVC

- [UPD] réorganisation MVC

# v1.0.3

### #laisonsBDD

- [UPD] contraintes BDD avec modif de requetes SQL
- [ADD] horloge backoffice

### #checklist-fct

- [UPD] Optimisation en quelques lignes de codes les fonctions JS + PHP de la checklist
- [UPD] Modification de la validation de la checklist en un bouton pour envoyer tableau vide ou non (si non CTNotOK)
- [UPD] Désactivation et optimisation de la fonction sendSMS en objet


### #regex-mail

- [UPD] du regex de vérification des mails (test du type "AA.AAAAA@AA.AA") OK


### #phpmailer + sms

- [UPD] Rajout envoi SMS lors de la création d'un nouveau RDV
- [ADD] Génération et envoi d'un mail lors d'un CTOK / CT_NOT_OK / CT_Canceled et RAZ d'un mot de passe.
- [UPD] Optimisation du code pour l'envoi de mail
- [UPD] Mise en page du body HTML pour les mails


### #cipher_file

- [ADD] Chiffrement des fichiers upload par hash user unique
- [UPD] Modification des fonctions "encrypt" et "decrypt" pour intégrer le hash par user
- [FIX] Debug erreur showinfos qd pas de CG OK
- [UPD] Modification du showInfo Technicien pour bon fonctionnement
- [UPD] Optimisation du code en créant une fonction commune

### #type-account-delete

- [UPD] Suppression du selectForm pour le type de compte lors de la création d'un nouvel utilisateur
- [ADD] Rajout d'une width sur la balise fieldset du formulaire pour éviter le réajustement des champs Suite à l'apparition du message d'erreur lors d'un controle de champ.

### #pattern-immat

- [ADD] Pattern Immat Old-New
- [ADD] field immat stylisé sur tous les form
- [FIX] bug affichage menu user côté tech
- [FIX] bug suppression input immat
- [UPD] Modification et test pattern 'année' sur form pour ne laisser que 1900 à 2099 possible
- [UPD] Réajustement de certains blocs pour design
- [FIX] bug accordéon clientForm + création page congé




