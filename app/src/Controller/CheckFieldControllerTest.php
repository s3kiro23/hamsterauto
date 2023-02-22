<?php

require_once '../Entity/Control.php';

switch ($_POST['request']) {

    case 'checkField':

        $msg = "";
        $status = 1;
        $data = json_decode($_POST['data'], true);
        $init_control = new Control();
        $check = $init_control->check_fields($data);

        if ($check['status'] == 0) {
            $msg = $check['msg'];
            $status = $check['status'];
        }

        echo json_encode(array("status" => $status, "msg" => $msg));

        break;

    default :

        echo json_encode(1);

        break;

}
