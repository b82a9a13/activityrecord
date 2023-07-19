<?php
// This file is part of the activityrecord plugin
/**
 * @package     local_activityrecord
 * @author      Robert Tyrone Cullen
 * @var stdClass $plugin
 */
defined('MOODLE_INTERNAL') || die();

$plugin->component = 'local_activityrecord';
$plugin->version = 12;
$plugin->requires = 2016052314;
$plugin->dependencies = [
    'local_trainingplan' => 12,
    'local_hourslog' => 9
];
