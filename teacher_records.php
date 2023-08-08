<?php
/**
 * @package     local_activityrecord
 * @author      Robert Tyrone Cullen
 * @var stdClass $plugin
 */

require_once(__DIR__.'/../../config.php');
use local_activityrecord\lib;
require_login();
$lib = new lib;
$p = 'local_activityrecord';

$errorText = '';
$e = $_GET['e'];
$uid = $_GET['uid'];
$cid = $_GET['cid'];
$fullname = '';
if($_GET['e']){
    if(($e != 'a' && $e != 'c') || empty($e)){
        $errorText = 'Invalid e character provided.';
    } else {
        if($_GET['uid']){
            if(!preg_match("/^[0-9]*$/", $uid) || empty($uid)){
                $errorText = 'Invalid user id provided.';
            } else {
                if($_GET['cid']){
                    if(!preg_match("/^[0-9]*$/", $cid) || empty($cid)){
                        $errorText = 'Invalid course id provided.';
                    } else {
                        if($lib->check_coach_course($cid)){
                            $context = context_course::instance($cid);
                            require_capability('local/activityrecord:teacher', $context);
                            $PAGE->set_context($context);
                            $PAGE->set_course($lib->get_course_record($cid));
                            $PAGE->set_url(new moodle_url("/local/activityrecord/activityrecord.php?cid=$cid&uid=$uid"));
                            $PAGE->set_title('Activity Records');
                            $PAGE->set_heading('Activity Records');
                            $PAGE->set_pagelayout('incourse');
                            $fullname = $lib->check_learner_enrolment($cid, $uid);
                            if($fullname == false){
                                $errorText = 'The user selected is not enrolled as a learner in the course selected.';
                            } else {
                                $_SESSION['ar_records_uid'] = $uid;
                                $_SESSION['ar_records_cid'] = $cid;
                            }
                        } else  {
                            $errorText = 'You are not enrolled as a coach in the course provided.';
                        }
                    }
                } else {
                    $errorText = 'No course id provided.';
                }
            }
        } else {
            $errorText = 'No user id provided.';
        }
    }
}

echo $OUTPUT->header();
if($errorText != ''){
    echo("<h1 class='text-error'>$errorText</h1>");
} else {
    if($e == 'a'){
        $e = '';
    } elseif($e == 'c'){
        $e = '?id='.$cid;
    }
    $template = (Object)[
        'btm' => get_string('btm', $p),
        'title' => get_string('activity_r', $p),
        'records_txt' => get_string('records', $p),
        'edit' => get_string('edit', $p),
        'delete' => get_string('delete', $p),
        'yes' => get_string('yes', $p),
        'no' => get_string('no', $p),
        'pdf' => get_string('pdf', $p),
        'new_record' => get_string('new_record', $p),
        'apprentice' => get_string('apprentice', $p),
        'review_date' => get_string('review_date', $p),
        'standard' => get_string('standard', $p),
        'employer_os' => get_string('employer_os', $p),
        'coach' => get_string('coach', $p),
        'manager_om' => get_string('manager_om', $p),
        'summary_op' => get_string('summary_op', $p),
        'course_ptd' => get_string('course_ptd', $p),
        'course_eptd' => get_string('course_eptd', $p),
        'comments' => get_string('comments', $p),
        'otjh_c' => get_string('otjh_c', $p),
        'expected_otjh_aptp' => get_string('expected_otjh_aptp', $p),
        'safeguarding' => get_string('safeguarding', $p),
        'health_as' => get_string('health_as', $p),
        'equality_ad' => get_string('equality_ad', $p),
        'information_aag' => get_string('information_aag', $p),
        'recap_act_title' => get_string('recap_act_title', $p),
        'what_impact_title' => get_string('what_impact_title', $p),
        'modules_aksb' => get_string('modules_aksb', $p),
        'what_impactw_title' => get_string('what_impactw_title', $p),
        'details_ot_title' => get_string('details_ot_title', $p),
        'functional_sp' => get_string('functional_sp', $p),
        'learning_t' => get_string('learning_t', $p),
        'target_fnv' => get_string('target_fnv', $p),
        'aln_title' => get_string('aln_title', $p),
        'agreed_act_title' => get_string('agreed_act_title', $p),
        'coach_otf' => get_string('coach_otf', $p),
        'apprentice_com_title' => get_string('apprentice_com_title', $p),
        'employer_cop' => get_string('employer_cop', $p),
        'choose_apdf' => get_string('choose_apdf', $p),
        'date_at_title' => get_string('date_at_title', $p),
        'remote_ftf' => get_string('remote_ftf', $p),
        'face_tf' => get_string('face_tf', $p),
        'remote' => get_string('remote', $p),
        'learner_s' => get_string('learner_s', $p),
        'coach_s' => get_string('coach_s', $p),
        'add_s' => get_string('add_s', $p),
        'submit' => get_string('submit', $p),
        'fullname' => $fullname,
        'coursename' => $lib->get_course_fullname($cid),
        'btm_ext' => $e,
        'records' => array_values($lib->get_docs_list($uid, $cid))
    ];
    echo $OUTPUT->render_from_template('local_activityrecord/activity_record', $template);
    \local_activityrecord\event\viewed_activity_records::create(array('context' => \context_course::instance($cid), 'courseid' => $cid, 'relateduserid' => $uid, 'other' => $_GET['e']))->trigger();
}
echo $OUTPUT->footer();