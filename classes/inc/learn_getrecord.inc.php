<?php
require_once(__DIR__.'/../../../../config.php');
use local_activityrecord\lib;
require_login();
$lib = new lib;
$returnText = new stdClass();

$error = false;
$number = $_POST['num'];
if(!preg_match("/^[0-9]*$/", $number) || empty($number)){
    $error = true;
}

if($error){
    $returnText->error = 'Invalid id.';
} else {
    $data = $lib->get_record_data_learn($number);
    $_SESSION['ar_lrecords_rid'] = $number;
    if($data != false){
        $array = [
            ['apprentice', $data[0]],
            ['reviewdate', $data[1]],
            ['standard', $data[2]],
            ['eors', $data[3]],
            ['coach', $data[4]],
            ['morm', $data[5]],
            ['coursep', $data[6]],
            ['courseep', $data[7]],
            ['coursecomment', $data[8]],
            ['otjhc', $data[9]],
            ['otjhe', $data[10]],
            ['otjhcomment', $data[11]],
            ['recap', $data[12]],
            ['recapimpact', $data[13]],
            ['details', $data[14]],
            ['detailsmod', $data[15]],
            ['impact', $data[16]],
            ['mathtoday', $data[17]],
            ['mathnext', $data[18]],
            ['engtoday', $data[19]],
            ['engnext', $data[20]],
            ['aln', $data[21]],
            ['coachfeed', $data[22]],
            ['safeguard', $data[23]],
            ['agreedact', $data[24]],
            ['apprencom', $data[25]],
            ['filesrc', "./classes/pdf/learn_employercomment.php?cid=".$_SESSION['ar_lrecord_cid']."&id=$number&pdf=$data[26]"],
            ['nextdate', $data[31]],
            ['remotef2f', $data[32]],
            ['hands', $data[33]],
            ['eandd', $data[34]],
            ['iaag', $data[35]]
        ];
        if($data[27] != '1970-01-01'){
            array_push($array, ['coachsigndate', $data[27]]);
            array_push($array, ['coachsignimg', $data[30]]);
        }
        if($data[28] != '1970-01-01'){
            array_push($array, ['learnsigndate', $data[28]]);
            array_push($array, ['learnsignimg', $data[29]]);
        }
        $returnText->return = $array;
        \local_activityrecord\event\viewed_activity_record_learn::create(array('context' => \context_course::instance($_SESSION['ar_lrecord_cid']), 'courseid' => $_SESSION['ar_lrecord_cid']))->trigger();
    } else {
        $returnText->error = 'No data available.';
    }
}
echo(json_encode($returnText));