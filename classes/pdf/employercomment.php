<?php
require_once(__DIR__.'/../../../../config.php');
use local_activityrecord\lib;
require_login();
$lib = new lib();

$cid = $_GET['cid'];
$uid = $_GET['uid'];
$id = $_GET['id'];
$pdf = $_GET['pdf'];
if(!preg_match("/^[0-9]*$/", $id) || empty($id)){
    exit();
} elseif(!preg_match("/^[0-9]*$/", $uid) || empty($uid)){
    exit();
} elseif(!preg_match("/^[0-9]*$/", $cid) || empty($cid)){
    exit();
} elseif(!preg_match("/^[0-9a-z.]*$/", $pdf) || empty($pdf)){
    exit();
} else{
    if($lib->check_coach_course($cid)){
        $context = context_course::instance($cid);
        require_capability('local/activityrecord:teacher', $context);
        $data = $lib->get_filename($id, $uid, $cid);
        if($data == false){
            exit();
        } else {
            $fullname = $lib->check_learner_enrolment($cid, $uid);
            if($fullname == false){
                exit();
            } else {
                if($data != $pdf){
                    exit();
                } else {
                    header("Content-type: application/pdf");
                    include("./employercomment/$data");
                    \local_activityrecord\event\viewed_activity_record_employercomment_pdf::create(array('context' => \context_course::instance($cid), 'courseid' => $cid, 'relateduserid' => $uid))->trigger();
                }
            }
        }
    } else {
        exit();
    }
}