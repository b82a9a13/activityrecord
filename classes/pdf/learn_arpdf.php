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

$error = '';
$id = null;
$cid = '';
if(isset($_GET['id'])){
    $id = $_GET['id'];
    if(!preg_match("/^[0-9]*$/",$id) || empty($id) || !isset($_SESSION['ar_lrecord_cid'])){
        $error = get_string('invalid_ip', $p);
    } else {
        $cid = $_SESSION['ar_lrecord_cid'];
        $context = context_course::instance($cid);
        require_capability('local/activityrecord:student', $context);
        if(!$lib->check_setup_exists_learner($cid)){
            $error = get_string('coach_ntcs', $p);
        } else {
            if(!$lib->check_learn_sign_exists($cid)){
                $error = get_string('need_tcs', $p);
            }
        }
    }
} else {
    $error = get_string('no_ip', $p);
}

if($error != ''){
    echo("<h1 class='text-error'>$error</h1>");
    exit();
} else {
    $fullname = $lib->get_current_user_fullname();
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
    $pdf->setFont('TImes', 'B', 26);
    $pdf->Cell(0, 0, get_string('activity_rec', $p)." - $fullname", 0, 0, 'C', 0, '', 0);
    $pdf->Ln();
    $pdf->setFont('Times', '', 11);
    $data = $lib->get_record_data_learn($id);
    include('./include_arpdf.php');
    \local_activityrecord\event\viewed_activity_record_pdf_learn::create(array('context' => \context_course::instance($cid), 'courseid' => $cid, 'other' => $id))->trigger();
}