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

$id = $_GET['id'];
$error = '';
if(!preg_match("/^[0-9]*$/", $id) || empty($id) || !isset($_SESSION['ar_records_uid']) || !isset($_SESSION['ar_records_cid'])){
    $error = 'Invalid id provided.';
}
$cid = $_SESSION['ar_records_cid'];
$uid = $_SESSION['ar_records_uid'];
if(!$lib->check_coach_course($cid)){
    $error = "You aren't enrolled as a coach in the course provided.";
} else {
    $context = context_course::instance($cid);
    require_capability('local/activityrecord:teacher', $context);
    $fullname = $lib->check_learner_enrolment($cid, $uid);
    if($fullname == false){
        $error = 'The user selected is not a learner in the selected course.';
    }
}

if($error != ''){
    echo("<h1 class='text-error'>$error</h1>");
    exit();
} else {
    require_once($CFG->libdir.'/filelib.php');
    require_once($CFG->libdir.'/tcpdf/tcpdf.php');
    class MYPDF extends TCPDF {
        public function Header(){
            $this->Image('./../img/ntalogo.png', $this->GetPageWidth() - 32, $this->GetPageHeight() - 22, 30, 20, 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);
        }
        public function Footer(){

        }
    }
    $pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $coursename = $lib->get_course_fullname($cid);
    $pdf->addPage('P', 'A4');
    $pdf->Cell(0, 0, "Activity Record - $fullname - $coursename", 0, 0, 'C', 0, '', 0);
    $pdf->Ln();
    $data = $lib->get_record_data($id);
    include('./include_arpdf.php');
    \local_activityrecord\event\viewed_activity_record_pdf::create(array('context' => \context_course::instance($cid), 'courseid' => $cid, 'relateduserid' => $uid, 'other' => $id))->trigger();
}