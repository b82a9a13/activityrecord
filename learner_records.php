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
$cid = $_GET['cid'];
$fullname = '';
if(isset($_GET['cid'])){
    if(!preg_match("/^[0-9]*$/", $cid) || empty($cid)){
        $errorText = 'Invalid course id provided.';
    } else {
        $context = context_course::instance($cid);
        require_capability('local/activityrecord:student', $context);
        $PAGE->set_context($context);
        $PAGE->set_course($lib->get_course_record($cid));
        $PAGE->set_url(new moodle_url("/local/activityrecord/learner_records.php?cid=$cid"));
        $PAGE->set_title('Activity Records');
        $PAGE->set_heading('Activity Records');
        $PAGE->set_pagelayout('incourse');
        $fullname = $lib->get_current_user_fullname();
        if(!$lib->check_setup_exists_learner($cid)){
            $errorText = 'Your coach needs to create a setup for you.';
        } else {
            if(!$lib->check_learn_sign_exists($cid)){
                $errorText = "You need to create your signature.";
            } else {
                $_SESSION['ar_lrecord_cid'] = $cid;
            }
        }
    }
} else {
    $errorText = 'No course id provided.';
}

echo $OUTPUT->header();

if($errorText != ''){
    echo("<h1 class='text-error'>$errorText</h1>");
} else {
    $template = (Object)[
        'fullname' => $fullname,
        'coursename' => $lib->get_course_fullname($cid),
        'records' => array_values($lib->get_docs_list_learn($cid))
    ];
    echo $OUTPUT->render_from_template('local_activityrecord/activity_record_learn', $template);
    \local_activityrecord\event\viewed_activity_records_learn::create(array('context' => \context_course::instance($cid), 'courseid' => $cid))->trigger();
}

echo $OUTPUT->footer();