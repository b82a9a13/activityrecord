<?php
/**
 * @package     local_activityrecord
 * @author      Robert Tyrone Cullen
 * @var stdClass $plugin
 */
require_once(__DIR__.'/../../../../config.php');
use local_activityrecord\lib;
require_login();
$lib = new lib;
$p = 'local_activityrecord';

$id = null;
if(!isset($_GET['id'])){
    echo("<h1 class='text-error'>".get_string('no_ip', $p)."</h1>");
    exit();
} else {
    $id = $_GET['id'];
    if(!preg_match("/^[0-9]*$/", $id) || empty($id) || !isset($_SESSION['ar_records_uid']) || !isset($_SESSION['ar_records_cid'])){
        echo("<h1 class='text-error'>".get_string('invalid_ip', $p)."</h1>");
        exit();
    }
    $cid = $_SESSION['ar_records_cid'];
    $uid = $_SESSION['ar_records_uid'];
    if(!$lib->check_coach_course($cid)){
        echo("<h1 class='text-error'>".get_string('not_eacicp', $p)."</h1>");
        exit();
    } else {
        $context = context_course::instance($cid);
        require_capability('local/activityrecord:teacher', $context);
        $fullname = $lib->check_learner_enrolment($cid, $uid);
        if($fullname == false){
            echo("<h1 class='text-error'>".get_string('selected_neal', $p)."</h1>");
            exit();
        } else {
            $filename = $lib->get_filename($id, $uid, $cid);
            if($filename == false){
                echo("<h1 class='text-error'>".get_string('employer_cde', $p)."</h1>");
                exit();
            } else {
                header('Content-Type: application/pdf');
                $coursename = $lib->get_course_fullname($cid);
                $date = $lib->get_ar_date($id);
                header("Content-Disposition:inline;filename=MonthlyActivityRecord-".str_replace(' ','_',$fullname)."-".str_replace(' ','_',$coursename)."-$date-EmployerComment.pdf");
                include('./employercomment/'.$filename);
                \local_activityrecord\event\viewed_activity_record_employercomment_pdf::create(array('context' => \context_course::instance($cid), 'courseid' => $cid, 'relateduserid' => $uid))->trigger();
            }
        }
    }
}