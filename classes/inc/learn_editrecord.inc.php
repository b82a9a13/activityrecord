<?php
require_once(__DIR__.'/../../../../config.php');
use local_activityrecord\lib;
require_login();
$lib = new lib;
$returnText = new stdClass();

$errorarray = [];
$date = $_POST['date'];
if($date != null && !empty($date)){
    if(!preg_match("/^[0-9\-]*$/", $date)){
        array_push($errorarray, ['learnsigndate', 'Learner Signature Date']);
    } else {
        $date = (new DateTime($date))->format('U');
    }
}
$com = $_POST['com'];
if(!preg_match("/^[a-zA-Z0-9 ,.!'();:\s\-#\/]*$/", $com) || empty($com)){
    array_push($errorarray, ['apprencom', 'Apprentice Comment:'.preg_replace("/[a-zA-Z0-9 ,.!'():;\s\-#\/]/",'', $com)]);
}

if($errorarray != []){
    $returnText->error = $errorarray;
} else {
    $result = $lib->update_activity_record_learner([
        $date,
        $com
    ]);
    if($result){
        $returnText->return = true;
        \local_activityrecord\event\updated_activity_record_learn::create(array('context' => \context_course::instance($_SESSION['ar_lrecord_cid']), 'courseid' => $_SESSION['ar_lrecord_cid']))->trigger();
    } else {
        $returnText->return = false;
    }
}
echo(json_encode($returnText));