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

class updated_activity_record extends base {
    protected function init(){
        $this->data['crud'] = 'd';
        $this->data['edulevel'] = self::LEVEL_TEACHING;
    }
    public static function get_name(){
        return 'Coach updated a activity record';
    }
    public function get_description(){
        return "The user with id '".$this->userid."' updated a activity record for the user with id '".$this->relateduserid."' and for the course with id '".$this->courseid."'";
    }
    public function get_url(){
        return new \moodle_url('/local/activityrecord/teacher_records.php?cid='.$this->courseid.'&uid='.$this->relateduserid.'&e=a');
    }
    public function get_id(){
        return $this->objectid;
    }
}