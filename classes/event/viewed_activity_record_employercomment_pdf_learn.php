<?php
// This file is part of Moodle Activity Record Plugin
/**
 * @package     local_activityrecord
 * @author      Robert Tyrone Cullen
 * @var stdClass $plugin
 */

namespace local_activityrecord\event;

use core\event\base;

defined('MOODLE_INTERNAL') || die();

class viewed_activity_record_employercomment_pdf_learn extends base {
    protected function init(){
        $this->data['crud'] = 'r';
        $this->data['edulevel'] = self::LEVEL_PARTICIPATING;
    }
    public static function get_name(){
        return 'Learner viewed a employer comment pdf for a activity record';
    }
    public function get_description(){
        return "The user with id '".$this->userid."' viewed a employer comment pdf for a activity record for the course with id '".$this->courseid."'";
    }
    public function get_url(){
        return new \moodle_url('/local/activityrecord/learner_records.php?cid='.$this->courseid);
    }
    public function get_id(){
        return $this->objectid;
    }
}