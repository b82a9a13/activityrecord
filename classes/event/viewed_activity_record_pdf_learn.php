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

class viewed_activity_record_pdf_learn extends base {
    protected function init(){
        $this->data['crud'] = 'r';
        $this->data['edulevel'] = self::LEVEL_PARTICIPATING;
    }
    public static function get_name(){
        return 'Learner viewed a activity record pdf';
    }
    public function get_description(){
        return "The user with id '".$this->userid."' viewed one of their activity record pdf's for the course with id '".$this->courseid."'";
    }
    public function get_url(){
        return new \moodle_url('/local/activityrecord/classes/pdf/learn_arpdf.php?id='.$this->other);
    }
    public function get_id(){
        return $this->objectid;
    }
}