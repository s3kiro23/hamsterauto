<?php

require_once '../Controller/shared.php';


switch ($_POST['request']) {

    case 'checkField':

        $msg = "";
        $status = 1;

        if (isset($_POST['field']) && $_POST['field'] == 'inputNom') {

            if (empty($_POST['fieldVal'])) {

                $msg = "Veuillez renseigner votre nom!";
                $status = 0;

            }

        } else if (isset($_POST['field']) && $_POST['field'] == 'inputPrenom') {

            if (empty($_POST['fieldVal'])) {

                $msg = "Veuillez renseigner votre prénom!";
                $status = 0;

            }

        }
        if (isset($_POST['field']) && $_POST['field'] == 'inputTel') {

            if (empty($_POST['fieldVal'])) {

                $msg = "Veuillez renseigner votre numéro de téléphone!";
                $status = 0;

            }
            if (!checkTel($_POST['fieldVal'])) {

                $msg = "Veuillez renseigner un numéro de téléphone valide!";
                $status = 0;

            }

        }
        if (isset($_POST['field']) && $_POST['field'] == 'inputEmail') {

            if (!checkMail($_POST['fieldVal'])) {

                $msg = "Veuillez renseigner un mail valide!";
                $status = 0;

            } else if (empty($_POST['fieldVal'])) {

                $msg = "Le champ email est vide!";
                $status = 0;

            }

        }
        if (isset($_POST['field']) && $_POST['field'] == 'inputAddr') {

            if (empty($_POST['fieldVal'])) {

                $msg = "Veuillez renseigner une adresse!";
                $status = 0;

            }

        }
        if (isset($_POST['field']) && $_POST['field'] == 'inputCP') {

            if (empty($_POST['fieldVal'])) {

                $msg = "Veuillez renseigner un code postal!";
                $status = 0;

            } else if (!checkCP($_POST['fieldVal'])) {

                $msg = "Veuillez renseigner un code postal valide!";
                $status = 0;

            }

        }
        if (isset($_POST['field']) && $_POST['field'] == 'inputVille') {

            if (empty($_POST['fieldVal'])) {

                $msg = "Veuillez indiquer une ville!";
                $status = 0;

            }

        }

        echo json_encode(array("status" => $status, "msg" => $msg));

        break;

    default :

        echo json_encode(1);

        break;

}