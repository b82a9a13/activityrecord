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

$title = 'Activity Record';
$type = '';
$enrolments = [];
$id = null;
$errorText = '';
if(isset($_GET['id'])){
    $id = $_GET['id'];
    if(!preg_match("/^[0-9]*$/", $id) || empty($id)){
        $errorText = 'Invalid Course id.';
    } else {
        if($lib->check_coach_course($id)){
            $context = context_course::instance($id);
            require_capability('local/activityrecord:teacher', $context);
            $PAGE->set_context($context);
            $PAGE->set_course($lib->get_course_record($id));
        } else {
            $errorText = "You are not enrolled as a coach in the course provided.";
        }
    }
    $type = 'one';
} else {
    $enrolments = $lib->check_coach();
    if(count($enrolments) > 0){
        $context = context_course::instance($enrolments[0][0]);
        require_capability('local/activityrecord:teacher', $context);
        $PAGE->set_context($context);
        $type = 'all';
    } else {
        $errorText .= 'No courses available.';
    }
}

$PAGE->set_url(new moodle_url('/local/activityrecord/teacher.php'));
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagelayout('incourse');

echo $OUTPUT->header();

if($errorText != ''){
    echo("<h1 class='text-error'>$errorText</h1>");
} else {
    if($type == 'all'){
        $_SESSION['ar_menu_type'] = 'all';
        $template = (Object)[
            'title' => get_string('activity_r', $p),
            'enrolments' => array_values($enrolments)
        ];
        echo $OUTPUT->render_from_template('local_activityrecord/teacher_all_courses', $template);
        echo("<script src='./amd/min/teacher_course.min.js' defer></script>");
       \local_activityrecord\event\viewed_menu::create(array('context' => \context_system::instance(), 'courseid' => $id))->trigger();
    } elseif($type == 'one'){
        $_SESSION['ar_menu_type'] = 'one';
        $template = (Object)[
            'title' => get_string('activity_r', $p),
            'coursename' => $lib->get_course_fullname($id)
        ];
        echo $OUTPUT->render_from_template('local_activityrecord/teacher_one_course',$template);
        echo("<script src='./amd/min/teacher_course.min.js'></script>");
        echo("<script defer>course_clicked($id)</script>");
        \local_activityrecord\event\viewed_menu::create(array('context' => \context_course::instance($id), 'courseid' => $id))->trigger();
    }
}

echo $OUTPUT->footer();