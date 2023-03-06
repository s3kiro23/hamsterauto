# v1.1.1

### improve_datatables_refresh

- [ADD] de appel Ajax à tous les Datatables pour éviter le clignotement des tableaux au refresh
- [UPD] amélioration visuel sur les tableaux
- [FIX] de certains bug sur les affichages admin et tech

### dev

- [UPD] du message de validation lors de la création d'un nouveau compte pour prévenir de l'envoi d'un mail

### redesign_activate_page

- [UPD] du design de la page d'activation des comptes

### dev

- [FIX] bug sur la sélection des modèles/marques dans la modal de modif véhicules
- [FIX] bug avec les tooltips sur les boutons d'actions du dash client


### add_placeholder_anim_login

- [FIX] bug sur le show/hide password page expiration mdp
- [ADD] placeholder animé sur les champs de login

### fix_bug_mailing_pv

- [ADD] Decrypt fonctions avant l'envoi du mail sur le fichier pdf et Encrypt après l'envoi

### fix_bug_tabs_admin

- [UPD] taille du logo sur la page private
- [FIX] bug de responsive sur la modal rdvInfos
- [UPD] style de la police tab rdv admin
- [FIX] du chemin d'accès logo admin
- [FIX] bug responsive sur le tableau des rdv admin + changement des prio

### review_interventionTab_admin

- [UPD] révision des tableaux d'interventions côté admin pour n'afficher que les rdv du jours
- [ADD] export CSV sur les interventions
- [UPD] design des boutons d'actions côté client et admin
- [UPD] du regex de contrôle des années pour n'autoriser que les dates ne dépassant pas l'année en cours
- [FIX] des bugs sur la gestion des heures côté admin
- [UPD] changement du retour de comptage sur l'index admin pour prendre en compte les states 0 et 1
- [ADD] conversion des dates en francais côté admin intervention

### implemente_select2

- [ADD] ajout de la librairie select2 pour mise en forme des sélections de marques/ modèles et ajout d'un champ de recherche

### dev

- [FIX] bug aléatoire d'auto remplissage du champs année et immat

### improve_little_things

- [UPD] margins du bloc de récupération mot de passe
- [UPD] amélioration du show/hide mot de passe dans un input-group
- [ADD] backgrounds sur toutes les pages
- [UPD] logo unscreen sur tous les templates mail
- [UPD] des mentions légales
- [UPD] du warning de la modal user 

### auto_script_vps

- [UPD] mise à jour et test de tous les scripts sur le vps OK
- [ADD] création des tâches cron associées 

# v1.1

### export_csv

- [UPD] style et margins sur certains tableaux du dash admin
- [ADD] d'un bouton d'export au format CSV pour certains tableaux
- [UPD] renommage et trie de tous les fichiers twig

### launch_api_dashadmin

- [ADD] nouveau bouton pour lancer la synchro de l'api matmut manuellement

### placeholder_animation

- [FIX] bug sur la prise de rendez-vous (create User)
- [UPD] code JS pour les placeholder
- [UPD] amélioration du regex sur les années
- [UPD] des placeholder pour tous les formulaires

### dev

- [FIX] d'un bug lors de la sélection des modèles sur la page index
- [UPD] optimisation du code lors de la vérification des créneaux horaires dispo

### admin-comptebans
- [ADD] ajout d'une section admin "comptes bloqués", avec deban du compte

### activation-compte-mail
- [ADD] a l'inscription d'un client envoi d'un mail avec un lien pour activer le compte


### uncheck_all_notification

- [ADD] bouton pour check/uncheck toutes les notifications d'un coup


### review_files_cipher

- [ADD] chiffrement du contenu des fichiers upload et générés par la classe PDF
- [ADD] déchiffrement des fichiers au moment de la visualisation côté admin/tech/client
- [DEL] error_log
- [UPD] du tri sur le tableau archives
- [UPD] nom des fichiers upload pour les rendres unique et éviter l'overwriting

### bind_queued_table_and_notification_uncheck

- [UPD] de la classe Queued côté php et pma pour lié l'id_user
- [ADD] contrôle de l'user si présent dans la liste de diffusion, si oui uncheck

# v1.0.9
### update-settings-admin
- [FIX] bug créneaux suite update wamp
- [FIX] bug sur datatable admin users
- [FIX] bug affichage logo marque sur index admin

### settings_créneaux
- [ADD] ajout durée des créneaux dans settings/admin

### kill-session-inactive-account
- [ADD] mise en place d'une vérification status 'is_active' lors de la prolongation de session, 
si compte inactif alors le moindre clique déconnecte l'utilisateur

### debug_controlFields

- [UPD] modification nom des champs sur le tableau de vérification dans la classe Control
- [FIX] bug avec l'index 'fuel' de l'input enregistré lors de l'appel Ajax, set d'une valeur par défaut si non sélectionné
- [ADD] ajout d'un break à la boucle foreach pour éviter d'écraser la variable de retour et ainsi suivre le cheminement correct des valeurs traitées

### design_improve_JM

- [ADD] lien sur le bouton de chat pour renvoyer vers la page contact
- [UPD] des margins et logo sur la page de changement de password
- [UPD] centrage du texte tabTech awaiting si aucun véhicule n'est présent

### rename_bdd_and_controller

- [UPD] all controller name in PascalCase
- [UDP] all fields and tables in BDD 
- [UPD] some variables with new names
- [FIX] bugs

### improve_code_following_mock_exam

- [UPD] des margins et centrage des card
- [UPD] text décocher au click en JS
- [ADD] d'un nouveau contrôle sur le changement des pwd si l'input correspond à l'ancien
- [UPD] des dernières fonctions dépréciées liées à strftime sur tablesClient et carController
- [UPD] améliorations des requêtes SQL liées au contrôle des prochains rdv et visites techniques
- [DEL] d'un dossier images inutilisé

### dev

- [FIX] bug showPassword sur Signin

### dev

- [FIX] bug cgu on signIn
- [UPD] move style bounce In index + private-login to style.css
- [FIX] bug with dateDropper onChange function
- [FIX] bug avec l'input tel des modals profil et adminProfil 
- [ADD] centrage des colonnes tabs clients

### dashboard-admin
- [UPD] opti code, 1 seule fonction ajax et 1 php pour modif horaires et modifs session coté admin

### dashboard-admin
- [UPD] ajout option pour changer durée des session user et techs, ajout en BDD de 2 champs

### request_expiration

- [ADD] d'un compteur SMS/requestPwd égale à 1 pour le nombre de demande autorisé
- [FIX] bug coté login tech pour la récupération du mot de passe
- [ADD] d'un contrôle de l'expiration des SMS/requestPwd égale à 10min
- [ADD] d'un fichier de migration pour l'add en BDD des nouveaux champs


### improve_code_notification

- [UPD] optimisation du code JS de la modalSettings
- [UPD] optimisation du code PHP coté clientController et Class Notification

### hide-show-password-form

- [ADD] bouton de visualisation du mot de passe page index + JS

### notification2

- [ADD] nouvelle classe Notification pour la gestion des notifs
- [ADD] modal settings coté user pour permettre le paramétrage complet des notifications
- [UPD] des requêtes SQL pour les différents appels
- [ADD] traces pour la gestion des interventions coté tech
- [ADD] d'une fonction tracesIN dans la classe Traces afin d'alléger le code coté controller
- [ADD] Rappel pour les prochains contrôle et rdv (1mois avant et 2jours avant)
- [UPD] des notifications sur les changements d'états des interventions et des différentes actions users
- [FIX] bug avec la modification d'un véhicule coté client
- [UPD] de la priorité des champs sur le tableau rdv coté user

### new_controlcheck_addRDV

- [ADD] date du prochain contrôle technique lors de la validation des checklist si OK pour le véhicule
- [ADD] d'un vérif supplémentaire lors de la validation d'un rdv sur la date du prochain contrôle technique
- [UPD] champs infos_vehicule en next_control + Class Vehicule

### dev

- [UPD] lors de la suppression d'un véhicule côté client, bascule aussi le rdv lié en archives

### rebuild-admin-twig

- [UPD] de baseTemplate.html.twig avec le nouveau theme
- [ADD] des différents éléments du base dans des fichiers distincts que l'on rappel avec des includes pour une meilleur
  lisibilité
- [UPD] du dropdown user pour n'afficher que les menus utile à l'administrateur
- [FIX] bug avec le dossier vendor
- [UPD] du footer pour qu'il soit collé en bas de page
- [ADD] fonction JS pour le switchlogo et sessionEnding

### refactor_depreciated_strftime

- [ADD] nouvelle classe Convert avec toutes les fonctions de conversion de date
- [UPD] des différents contrôleurs avec les nouvelles fct statiques
- [FIX] bug sur les radios boutons du formulaire index + responsive sur mobile
- [UPD] transfert de la requête SQL du case 'basculer_intervention' dans la classe ControlTech
- [FIX] bug avec la génération des pv, l'ID récupéré était vide

# v1.0.80

### opti-chiffrement-ID

- [UPD] chiffrements d'id et timestamps, coté client et tech

### improve_datepicker

- [ADD] du fichier JS complet pour l'appel du nouveau dateDropper
- [UPD] du code d'appel sur les dates pour les différents formulaires
- [UPD] amélioration du case loadCarRecap en scindant les infos renvoyées
- [ADD] de 3 fichiers JS pour remplir les tableaux client indépendamment
- [FIX] bug avec dataTable lors de la suppression ou ajout d'information
- [ADD] rechargement de l'historique sur la page active
- [ADD] gestion des dimanche et jours fériés pour le datedropper

### refactoring_tabsTech

- [UPD] fusion des cases pour l'affichage des tableaux technicien en un seul case PHP
- [UPD] création de 3 fonctions JS pour la gestion dynamique des tableaux
- [FIX] bug sur le bouton retour du tableau Awaiting

### opti sécu

- [UPD] ajout vérification pour empecher de coller un timestamp passé dans la prise de RDV

### ajout table archives

- [UPD] ajout d'une table 'archives', renommage table 'controle_tech' en 'awaiting_interventions'.
  toute intervention terminée (validé, contre visite ou annulée) est basculée dans la table 'archives'
  La table 'awaiting_interventions' ne contient plsu que les rendez-vous en attente et les interventions en cours.

### debugging_infocar_tech

- [FIX] bug sur le filtre immat
- [FIX] bug sur la modal infocar sur le dash tech
- [FIX] bug sur session.js en renommant la fonction loadEvent en loadEventTimer.

### opti-tableaux-technicien

- [UPD] fusion des fonction next_day_rdv et previous_day_rdv en eune seule

### refactoring_HTML_Entity

- [ADD] nouveau dossier dans src/Entity/HTML pour accueillir toutes les classes générant du HTML
- [UPD] de la classe HTML en fonction de son action dans le code vers le dossier HTML
- [UPD] des noms de fonctions pour être plus parlant et en anglais
- [DEL] de la classe HTML suite refactoring
- [UPD] sortie du code HTML présent dans les contrôleurs vers les nouvelles classes HTML
- [UPD] amélioration du code sur le contrôleur contactUsController
- [FIX] responsive sur la checklist pour tablette et Desktop
- [FIX] bug JS sur private-login en créant un nouveau fichier login.js et en y incorporant les fonctions communes à
  index et private-login.
- [NUL] suppression de code inutilisé

### extract-modals

- [UPD] extraction de tout le code HTML lié aux modals du dossier templates et création de fonction JS associées.
- [ADD] d'un nouveau dossier "modals" dans js/components pour accueillir toutes les modals.
- [UPD] des liens sur les différents fichiers JS avec les nouveau noms de modal.

### opticreneaux-chiffrement

- [UPD] chiffrement des ID de créneaux horaires + verif en PHP sur la prise de RDV grâce à la classe Security.

### opti-login-Pierre

- [UPD] suppression des fonction logout et logout tech, toutes les déconnexions se font via
  la classe Session.php grâce a la fonction sessionEnding
- [DEL] suppression du controleur privateLogin.php devenu inutile

### opti-Pierre-security.php

- [UPD] exp session + prolongation integré dans une classe Session pour opti code + sweetAlert 'etes vous la'
  avec compte a rebours avant déconnexion, aussi integré dans la classe JS sweetAlertToast
- [UPD] implémentation de l'expiration de session + prolongation au click sur client-dashboard + back-office
  la prolongation est différente selon type d'user
- [UPD] opti du code classe security.php

### refactoring_sharedController

- [ADD] nouvelle classe Control pour la gestion des champs de formulaires
- [UPD] transfère de toutes les fonctions du fichier shared.php vers Control et MAJ de tous les liens
- [UPD] du contrôle de champs sur tous les formulaires
- [UPD] transfère des fonctions encrypt/decrypt et random_hash vers la classe Security

### refactoring_radioBtn_JS

- [UPD] récupération des données avec nouvelles fonctions JS de tous les formulaires pour code propre
- [UPD] des différents contrôleurs avec le nouveau code de récupération des données
- [UPD] des fonctions de classes concernées par le refactoring
- [FIX] path pour la génération des pdf
- [UPD] noms de variables pour plus de lisibilité
- [DEL] scripts JS inutilisé sur les pages HTML
- [NUL] renommage initMap.js en contactUs.js

# v1.0.75

### dev

- [NUL] Debug controle d'accès
- [UPD] contrôle d'accès sur backoffice.html et checklist.html
- [ADD] crétion classe security.php

### dev

- [NUL] supression de code redondant

### dev

- [FIX] bug avec la validation de la checklist

### #checklist_responsive_v2

- [UPD] responsive pour la checklist + refactoring de la structure

### #fix_designResponsive_JM

- [FIX] responsive de la navbar + ajustement margin
- [UPD] design + responsive sur les différentes pages de connexion + pwd oublié etc..
- [ADD] wizard coté dashclient + index

# v1.0.74

### #templateMail_and_headerPDF

- [a6e5f66](https://github.com/pguiderdoni/controle_tech/commit/a6e5f66db02a8d9c6029ad3133a5ccd2de3fb987) [FIX] chemin
  vers les images lors de la génération d'un pdf
- [7da2e1f](https://github.com/pguiderdoni/controle_tech/commit/7da2e1f26dae6f2e4e9cd61370888a599ae1f094) [ADD] d'un
  QRCODE lors de la génération des pv renvoyant vers le site d'Aflokkat pour le moment, à modifier
- [17e74cf](https://github.com/pguiderdoni/controle_tech/commit/17e74cf4cb1aad31d5974f24db3c15b3d2300e32) [ADD] d'un
  header custom pour la génération des pv + background sur la vignette de CT
- [c98d23c](https://github.com/pguiderdoni/controle_tech/commit/c98d23c9cd61af9f740d0a27698cd459d763f102) [UPD] du
  composer.json pour installer le bundle intl-tel-input + modif du chemin script en cas d'utilisation + suppression des
  CDN inutilisés
- [a6c04fd](https://github.com/pguiderdoni/controle_tech/commit/a6c04fd434902af0e8b1283cbd92450699fe4ca6) [UPD] du
  template mail et modification de l'emplacement du script des jobs

### #improve-logs

- [16215d5](https://github.com/pguiderdoni/controle_tech/commit/16215d51185e5ba22c186540d05c141c46fd89fb) [UPD]
  .gitignore pour les dossiers .idea, vendor, etc..
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
- [FIX] bug checkfield sur marque/modele + bug affichage erreur sur tous les forms lorsque que marque/modele n'est pas
  select

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
- [ADD] Rajout d'une width sur la balise fieldset du formulaire pour éviter le réajustement des champs Suite à l'
  apparition du message d'erreur lors d'un controle de champ.

### #pattern-immat

- [ADD] Pattern Immat Old-New
- [ADD] field immat stylisé sur tous les form
- [FIX] bug affichage menu user côté tech
- [FIX] bug suppression input immat
- [UPD] Modification et test pattern 'année' sur form pour ne laisser que 1900 à 2099 possible
- [UPD] Réajustement de certains blocs pour design
- [FIX] bug accordéon clientForm + création page congé




