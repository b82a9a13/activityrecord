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

class viewed_activity_records_learn extends base {
    protected function init(){
        $this->data['crud'] = 'r';
        $this->data['edulevel'] = self::LEVEL_PARTICIPATING;
    }
    public static function get_name(){
        return 'Learner activity records page viewed';
    }
    public function get_description(){
        return "The user with id '".$this->userid."' viewed their activity records page for the course with id '".$this->courseid."'";
    }
    public function get_url(){
        return new \moodle_url('/local/activityrecord/learner_records.php?cid='.$this->courseid);
    }
    public function get_id(){
        return $this->objectid;
    }
}