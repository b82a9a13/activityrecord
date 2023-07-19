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
        'fullname' => $fullname,
        'coursename' => $lib->get_course_fullname($cid),
        'btm_ext' => $e,
        'records' => array_values($lib->get_docs_list($uid, $cid))
    ];
    echo $OUTPUT->render_from_template('local_activityrecord/activity_record', $template);
    \local_activityrecord\event\viewed_activity_records::create(array('context' => \context_course::instance($cid), 'courseid' => $cid, 'relateduserid' => $uid, 'other' => $_GET['e']))->trigger();
}
echo $OUTPUT->footer();