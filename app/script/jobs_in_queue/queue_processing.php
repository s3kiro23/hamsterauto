<?php

spl_autoload_register(function ($classe) {
    require '../../src/Entity/' . $classe . '.php';
});

$db = new Database();
$GLOBALS['Database'] = $db->connexion();

$jobs_in_queued = Queued::getJobs_queued();

if (!empty($jobs_in_queued)) {
    $state = 0;
    foreach ($jobs_in_queued as $job) {
        try {
            if ($job['type'] == "mail") {
                $parsed_template = (json_decode($job['template'], true));
                $mail = new Mailing();
                $mail->send($parsed_template);
                $state = 1;
            } else if ($job['type'] == "sms") {
                $parsed_template = (json_decode($job['template'], true));
                $sms = new SMS(0);
                $sms->send($parsed_template);
                $state = 1;
            }
            if ($state != 0) {
                $job_object = new Queued($job['id_queue']);
                $job_object->delete();
            }
        } catch (Throwable $e) {
            echo ("Captured Throwable: " . $e->getMessage() . PHP_EOL);
        }
    }
}
