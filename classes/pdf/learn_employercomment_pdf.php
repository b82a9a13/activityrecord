<?php
require_once(__DIR__.'/../../../../config.php');
use local_activityrecord\lib;
require_login();
$lib = new lib();

$id = $_GET['id'];
$cid = '';
if(isset($_GET['id'])){
    if(!preg_match("/^[0-9]*$/",$id) || empty($id) || !isset($_SESSION['ar_lrecord_cid'])){
        echo("<h1 class='text-error'>Invalid id provided.</h1>");
        exit();
    } else {
        $cid = $_SESSION['ar_lrecord_cid'];
        $context = context_course::instance($cid);
        require_capability('local/activityrecord:student', $context);
        if(!$lib->check_setup_exists_learner($cid)){
            echo("<h1 class='text-error'>Your coach needs to create a setup for you.</h1>");
            exit();
        } else {
            if(!$lib->check_learn_sign_exists($cid)){
                echo("<h1 class='text-error'>You need to create your signature.</h1>");
                exit();
            } else {
                $filename = $lib->get_filename_learn($id, $cid);
                if($filename == false){
                    echo("<h1 class='text-error'>Employer comment doesn't exist.</h1>");
                    exit();
                } else {
                    header('Content-Type: application/pdf');
                    $fullname = $lib->get_current_user_fullname();
                    $coursename = $lib->get_course_fullname($cid);
                    $date = $lib->get_ar_date_learn($id);
                    header("Content-Disposition:inline;filename=MonthlyActivityRecord-".str_replace(' ','_',$fullname)."-".str_replace(' ','_',$coursename)."-$date-EmployerComment.pdf");
                    include('./employercomment/'.$filename);
                    \local_activityrecord\event\viewed_activity_record_employercomment_pdf_learn::create(array('context' => \context_course::instance($cid), 'courseid' => $cid))->trigger();
                }
            }
        }
    }
}