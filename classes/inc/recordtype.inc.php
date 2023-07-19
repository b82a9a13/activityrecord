<?php
require_once(__DIR__.'/../../../../config.php');
use local_activityrecord\lib;
require_login();
$lib = new lib;
$returnText = new stdClass();

$error = false;
$type = $_POST['type'];
if(!empty($_POST['type']) && $type != 'new' && $type != 'edit'){
    $error = true;
}

if($error){
    $returnText->error = 'Invalid type.';
} else {
    if(isset($_SESSION['ar_records_uid']) && isset($_SESSION['ar_records_cid'])){
        if($type == 'new'){
            $defaults = $lib->get_doc_defualt($_SESSION['ar_records_uid'], $_SESSION['ar_records_cid']);
            $array = [
                ['apprentice', $lib->get_user_fullname($_SESSION['ar_records_uid'])],
                ['standard', $defaults[0]],
                ['eors', $defaults[1]],
                ['coach', $defaults[2]],
                ['morm', $defaults[3]],
                ['ar_form_script', './classes/js/newrecord.js'],
                ['activityrecord_title', 'New Activity Record'],
                ['coursep', $defaults[4][0]],
                ['courseep', $defaults[4][1]],
                ['otjhc', $defaults[5][0]],
                ['otjhe', $defaults[5][1]],
                ['reviewdate', date('Y-m-d',time())],
                ['impact_required', $defaults[6]]
            ];
            $returnText->return = $array;
        } else{
            $number = $_POST['num'];
            if(!empty($_POST['num']) && !preg_match('/^[0-9]*$/', $number)){
                $error = true;
            } else {
                if($type == 'edit'){
                    $data = $lib->get_record_data($number);
                    $_SESSION['ar_records_rid'] = $number;
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
                            ['activityrecord_title', 'Edit Activity Record'],
                            ['ar_form_script', './classes/js/editrecord.js'],
                            ['ar_sign_div', 'flex'],
                            ['filesrc', "./classes/pdf/employercomment.php?cid=".$_SESSION['ar_records_cid']."&uid=".$_SESSION['ar_records_uid']."&id=$number&pdf=$data[26]"],
                            ['nextdate', $data[31]],
                            ['remotef2f', $data[32]]
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
                        \local_activityrecord\event\viewed_activity_record::create(array('context' => \context_course::instance($_SESSION['ar_records_cid']), 'courseid' => $_SESSION['ar_records_cid'], 'relateduserid' => $_SESSION['ar_records_uid']))->trigger();
                    } else {
                        $returnText->error = 'No data available.';
                    }
                }
            }
        }
    } else {
        $returnText->error = 'Required variables are not set.';
    }
}
echo(json_encode($returnText));