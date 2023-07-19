<?php
require_once(__DIR__.'/../../../../config.php');
use local_activityrecord\lib;
require_login();
$lib = new lib;
$returnText = new stdClass();

$error = '';
$id = $_POST['id'];
if(!preg_match("/^[0-9]*$/", $id) || empty($id)){
    $error = 'Invalid id';
}

if($error != ''){
    $returnText->error = $error;
} else {
    $deletion = $lib->del_record_data($id);
    if($deletion){
        $returnText->return = true;
        \local_activityrecord\event\deleted_activity_record::create(array('context' => \context_course::instance($_SESSION['ar_records_cid']), 'courseid' => $_SESSION['ar_records_cid'], 'relateduserid' => $_SESSION['ar_records_uid']))->trigger();
    } else {
        $returnText->return = false;
    }
}
echo(json_encode($returnText));