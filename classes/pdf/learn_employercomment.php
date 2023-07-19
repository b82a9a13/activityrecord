<?php
require_once(__DIR__.'/../../../../config.php');
use local_activityrecord\lib;
require_login();
$lib = new lib();

$cid = $_GET['cid'];
$pdf = $_GET['pdf'];
$id = $_GET['id'];
if(!preg_match("/^[0-9]*$/", $cid) || empty($cid)){
    exit();
} elseif(!preg_match("/^[0-9]*$/", $id) || empty($id)){
    exit();
} elseif(!preg_match("/^[0-9a-z.]*$/", $pdf) || empty($pdf)){
    exit();
} else{
    $context = context_course::instance($cid);
    require_capability('local/activityrecord:student', $context);
    $data = $lib->get_filename_learn($id, $cid);
    if($data == false){
        exit();
    } else {
        if($data != $pdf){
            exit();
        } else {
            header("Content-type: application/pdf");
            include("./employercomment/$data");
            \local_activityrecord\event\viewed_activity_record_employercomment_pdf_learn::create(array('context' => \context_course::instance($cid), 'courseid' => $cid))->trigger();
        }
    }
}