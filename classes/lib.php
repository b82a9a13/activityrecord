<?php
/**
 * @package     local_activityrecord
 * @author      Robert Tyrone Cullen
 * @var stdClass $plugin
 */
namespace local_activityrecord;
use stdClass;

class lib{
    
    //Get the category id for apprenticeships
    public function get_category_id(){
        global $DB;
        return $DB->get_record_sql('SELECT id FROM {course_categories} WHERE name = ?',['Apprenticeships'])->id;
    }

    //Get current userid
    public function get_userid(){
        global $USER;
        return $USER->id;
    }

    //Get full course name from course id
    public function get_course_fullname($id){
        global $DB;
        return $DB->get_record_sql('SELECT fullname FROM {course} WHERE id = ?',[$id])->fullname;
    }

    public function get_current_user_fullname(){
        global $DB;
        $record = $DB->get_record_sql('SELECT firstname, lastname FROM {user} WHERE id = ?',[$this->get_userid()]);
        return $record->firstname.' '.$record->lastname;
    }

    //Get user full name from a specific id
    public function get_user_fullname($id){
        global $DB;
        $record = $DB->get_record_sql('SELECT firstname, lastname FROM {user} WHERE id = ?',[$id]);
        return $record->firstname.' '.$record->lastname;
    }

    //Check if the current user is enrolled as a coach in a apprenticeship course
    public function check_coach(){
        global $DB;
        $records = $DB->get_records_sql('SELECT DISTINCT {enrol}.courseid as courseid, {course}.fullname as fullname FROM {user_enrolments}
            INNER JOIN {enrol} ON {enrol}.id = {user_enrolments}.enrolid
            INNER JOIN {context} ON {context}.instanceid = {enrol}.courseid
            INNER JOIN {role_assignments} ON {role_assignments}.contextid = {context}.id
            INNER JOIN {course} ON {course}.id = {enrol}.courseid
            WHERE {role_assignments}.roleid IN (3,4) AND {user_enrolments}.userid = ? AND {course}.category = ? AND {user_enrolments}.status = 0 AND {role_assignments}.userid = {user_enrolments}.userid',
        [$this->get_userid(), $this->get_category_id()]);
        $array = [];
        foreach($records as $record){
            array_push($array, [$record->courseid, $record->fullname]);
        }
        return $array;
    }

    //Check if the current user is enrolled in the course provided as a coach
    public function check_coach_course($id){
        global $DB;
        $record = $DB->get_record_sql('SELECT DISTINCT {enrol}.courseid as courseid FROM {user_enrolments}
            INNER JOIN {enrol} ON {enrol}.id = {user_enrolments}.enrolid
            INNER JOIN {context} ON {context}.instanceid = {enrol}.courseid
            INNER JOIN {role_assignments} ON {role_assignments}.contextid = {context}.id
            INNER JOIN {course} ON {course}.id = {enrol}.courseid
            WHERE {role_assignments}.roleid IN (3,4) AND {user_enrolments}.userid = ? AND {course}.category = ? AND {user_enrolments}.status = 0 AND {role_assignments}.userid = {user_enrolments}.userid AND {course}.id = ?',
        [$this->get_userid(), $this->get_category_id(), $id]);
        if($record->courseid != null){
            return true;
        } else {
            return false;
        }
    }

    //Get the record for a specific course
    public function get_course_record($id){
        global $DB;
        return $DB->get_record_sql('SELECT * FROM {course} WHERE id = ?',[$id]);
    }

    //Get learners for a specific course
    public function get_enrolled_learners($id){
        global $DB;
        if($this->check_coach_course($id)){
            $records = $DB->get_records_sql('SELECT {user}.firstname as firstname, {user}.lastname as lastname, {user}.id as id FROM {user_enrolments} 
                INNER JOIN {enrol} ON {enrol}.id = {user_enrolments}.enrolid
                INNER JOIN {context} ON {context}.instanceid = {enrol}.courseid
                INNER JOIN {role_assignments} ON {role_assignments}.contextid = {context}.id
                INNER JOIN {course} ON {course}.id = {enrol}.courseid 
                INNER JOIN {user} ON {user}.id = {user_enrolments}.userid
                WHERE {enrol}.courseid = ? AND {user_enrolments}.status = 0 AND {role_assignments}.roleid = 5 AND {course}.category = ? AND {user_enrolments}.userid = {role_assignments}.userid',
            [$id, $this->get_category_id()]);
            if(count($records) > 0){
                $array = [];
                foreach($records as $record){
                    //Need to add data for if a setup is created or not
                    $tmpRecord = $DB->get_record_sql('SELECT coachsign FROM {trainingplan_setup} WHERE userid = ? and courseid = ?',[$record->id, $id]);
                    if($tmpRecord != null){
                        if($tmpRecord->coachsign != '' && $tmpRecord->coachsign != null){
                            array_push($array, [$record->firstname.' '.$record->lastname, $id, $record->id, true, true]);
                        } else {
                            array_push($array, [$record->firstname.' '.$record->lastname, $id, $record->id, true, false]);
                        }
                    } else {
                        array_push($array, [$record->firstname.' '.$record->lastname, $id, $record->id, false, false]);
                    }
                }
                asort($array);
                return $array;
            } else {
                return ['invalid'];
            }
        } else {
            return 'invalid';
        }
    }
    
    //Check if a userid is enrolled in a course as a learner
    public function check_learner_enrolment($cid, $uid){
        global $DB;
        $record = $DB->get_record_sql('SELECT {user}.firstname as firstname, {user}.lastname as lastname FROM {user_enrolments} 
            INNER JOIN {enrol} ON {enrol}.id = {user_enrolments}.enrolid
            INNER JOIN {context} ON {context}.instanceid = {enrol}.courseid
            INNER JOIN {role_assignments} ON {role_assignments}.contextid = {context}.id
            INNER JOIN {course} ON {course}.id = {enrol}.courseid 
            INNER JOIN {user} ON {user}.id = {user_enrolments}.userid
            WHERE {enrol}.courseid = ? AND {user_enrolments}.status = 0 AND {role_assignments}.roleid = 5 AND {course}.category = ? AND {user_enrolments}.userid = {role_assignments}.userid AND {user_enrolments}.userid = ?',
        [$cid, $this->get_category_id(), $uid]);
        if($record->firstname != null){
            return $record->firstname.' '.$record->lastname;
        } else {
            return false;
        }
    }

    //Get docs id from a specific userid and courseid
    public function get_docsid($uid, $cid){
        global $DB;
        return ($DB->record_exists('activityrecord_docs', [$DB->sql_compare_text('userid') => $uid, $DB->sql_compare_text('courseid') => $cid])) ? $DB->get_record_sql('SELECT id FROM {activityrecord_docs} WHERE userid = ? and courseid = ?',[$uid, $cid])->id : null;
    }

    //Create a activity record
    public function create_activity_record($data){
        global $DB;
        if(empty($_SESSION['ar_records_uid']) || empty($_SESSION['ar_records_cid'])){
            return false;
        }
        $record = new stdClass();
        $record->userid = $_SESSION['ar_records_uid'];
        $record->courseid = $_SESSION['ar_records_cid'];
        if(!$DB->record_exists('activityrecord_docs', [$DB->sql_compare_text('userid') => $record->userid, $DB->sql_compare_text('courseid') => $record->courseid])){
            $DB->insert_record('activityrecord_docs', $record, true);
        }
        $record->docsid = $this->get_docsid($record->userid, $record->courseid);
        $record->apprentice = $data[0];
        $record->reviewdate = $data[1];
        if($DB->record_exists('activityrecord_docs_info', [$DB->sql_compare_text('docsid') => $record->docsid, $DB->sql_compare_text('reviewdate') => $record->reviewdate])){
            return false;
        }
        $record->standard = $data[2];
        $record->employerandstore = $data[3];
        $record->coach = $data[4];
        $record->managerormentor = $data[5];
        $record->progress = $data[6];
        $record->expectprogress = $data[7];
        $record->progresscom = $data[8];
        $record->hours = $data[9];
        $record->expecthours = $data[10];
        $record->otjhcom = $data[11];
        $record->recap = $data[12];
        $record->impact = $data[13];
        $record->details = $data[14];
        $record->detailsksb = $data[15];
        $record->detailimpact = $data[16];
        $record->todaymath = $data[17];
        $record->nextmath = $data[18];
        $record->todayeng = $data[19];
        $record->nexteng = $data[20];
        $record->alnsupport = $data[21];
        $record->coachfeedback = $data[22];
        $record->safeguarding = $data[23];
        $record->agreedaction = $data[24];
        $record->employercomment = $data[25];
        $record->apprenticecomment = $data[26];
        $record->nextdate = $data[27];
        $record->nexttype = $data[28];
        $record->healthandsafety = $data[29];
        $record->equalityad = $data[30];
        $record->informationaag = $data[31];
        if($DB->insert_record('activityrecord_docs_info', $record, true)){
            return true;
        } else {
            return false;
        }
    }

    //Get activity records for a specific userid and courseid
    public function get_docs_list($uid, $cid){
        global $DB;
        $records = $DB->get_records_sql('SELECT reviewdate, id FROM {activityrecord_docs_info} WHERE docsid = ?',[$this->get_docsid($uid, $cid)]);
        $array = [];
        foreach($records as $record){
            array_push($array, [date('d-m-Y',$record->reviewdate), $record->id]);
        }
        asort($array);
        return $array;
    }

    //Get course progress to date for a specific userid and courseid
    public function get_course_progress($uid, $cid, $totalmonths, $startdate){
        global $DB;
        $record = $DB->get_record_sql('SELECT count(*) as total FROM {course_modules}
            INNER JOIN {course_modules_completion} ON {course_modules_completion}.coursemoduleid = {course_modules}.id
            WHERE {course_modules}.course = ? AND {course_modules}.completion != 0 AND {course_modules_completion}.userid = ? and {course_modules_completion}.completionstate = 1',
        [$cid, $uid])->total;
        $totalModules = $DB->get_record_sql('SELECT count(*) as total FROM {course_modules} WHERE course = ? and completion != 0',[$cid])->total;
        $percent = round(($record / $totalModules) * 100);
        $expected = round(((
                (($totalModules / $totalmonths) / 4) * 
                (round((date('U') - $startdate) / 604800))
                ) / $totalModules) * 100
        );
        $expected = ($expected < 0) ? 0 : $expected;
        $percent = ($percent < 0) ? 0 : $percent;
        return [$percent, $expected];
    }

    //Get OTJH progress to date for a specific userid and courseid
    public function get_otjh_progress($uid, $cid, $startdate, $otjhours, $totalmonths){
        global $DB;
        $records = $DB->get_records_sql('SELECT {hourslog_hours_info}.id, {hourslog_hours_info}.duration as duration FROM {hourslog_hours} 
            INNER JOIN {hourslog_hours_info} ON {hourslog_hours_info}.hoursid = {hourslog_hours}.id
            WHERE {hourslog_hours}.userid = ? AND {hourslog_hours}.courseid = ?',
        [$uid, $cid]);
        $expected = floatval(
            number_format((($otjhours / $totalmonths) / 4) *
            (round((date('U') - $startdate) / 604800) / $otjhours) * 100, 0, '.',' ')
        );
        $expected = ($expected < 0) ? 0 : $expected;
        $percent = 0;
        if(count($records) > 0){
            $duration = 0;
            foreach($records as $rec){
                $duration += $rec->duration;
            }
            $percent = floatval(number_format(($duration / $otjhours) * 100, 0, '.',' '));
            $percent = ($percent < 0) ? 0 : $percent;
            $percent = ($percent > 100) ? 100 : $percent;
        }
        return [$percent, $expected];
    }

    //Return true or false depending on if actions were in the last record
    public function check_prev_actions($uid, $cid){
        global $DB;
        $docsid = $this->get_docsid($uid, $cid);
        if($DB->record_exists('activityrecord_docs_info', [$DB->sql_compare_text('docsid') => $docsid])){
            $record = $DB->get_record_sql('SELECT MAX(reviewdate), impact FROM {activityrecord_docs_info} WHERE docsid = ?',[$docsid]);
            if($record != null){
                if($record->impact != null){
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //Get default values for a activity record from a specfic userid and courseid
    public function get_doc_defualt($uid, $cid){
        global $DB;
        $record = $DB->get_record_sql('SELECT planfilename, employerorstore, coach, managerormentor, totalmonths, startdate, otjhours FROM {trainingplan_setup} WHERE userid = ? and courseid = ?',[$uid, $cid]);
        return [
            $this->get_training_plan_name($record->planfilename), 
            $record->employerorstore, 
            $record->coach, 
            $record->managerormentor, 
            $this->get_course_progress($uid, $cid, $record->totalmonths, $record->startdate),
            $this->get_otjh_progress($uid, $cid, $record->startdate, $record->otjhours, $record->totalmonths),
            $this->check_prev_actions($uid, $cid)
        ];
    }

    //Get the name of a training plan from a plan file name
    public function get_training_plan_name($file){
        global $CFG;
        $json = file_get_contents($CFG->dirroot.'/local/trainingplan/templates/json/'.$file);
        $json = json_decode($json);
        return $json->name;
    }

    //Get all data for a specific form id
    public function get_record_data($id){
        global $DB;
        if(empty($_SESSION['ar_records_uid']) && empty($_SESSION['ar_records_cid'])){
            return false;
        } else {
            $uid = $_SESSION['ar_records_uid'];
            $cid = $_SESSION['ar_records_cid'];
            $docsid = $this->get_docsid($uid, $cid);
            $record = $DB->get_record_sql('SELECT * FROM {activityrecord_docs_info} WHERE docsid = ? AND id = ?',[$docsid, $id]);
            $array = [
                $record->apprentice,
                date('Y-m-d',$record->reviewdate),
                $record->standard,
                $record->employerandstore,
                $record->coach,
                $record->managerormentor,
                $record->progress,
                $record->expectprogress,
                $record->progresscom,
                $record->hours,
                $record->expecthours,
                $record->otjhcom,
                $record->recap,
                $record->impact,
                $record->details,
                $record->detailsksb,
                $record->detailimpact,
                $record->todaymath,
                $record->nextmath,
                $record->todayeng,
                $record->nexteng,
                $record->alnsupport,
                $record->coachfeedback,
                $record->safeguarding,
                $record->agreedaction,
                $record->apprenticecomment,
                $record->employercomment,
                date('Y-m-d',$record->ntasigndate),
                date('Y-m-d',$record->learnsigndate),
                str_replace(" ","+",$record->learnsign),
                str_replace(" ","+",$record->ntasign),
                date('Y-m-d H:m',$record->nextdate),
                $record->nexttype,
                $record->healthandsafety,
                $record->equalityad,
                $record->informationaag
            ];
            return $array;
        }
    }

    //Get pdf filename and record date
    public function get_filename($id, $uid, $cid){
        global $DB;
        if(!$DB->record_exists('activityrecord_docs', [$DB->sql_compare_text('userid') => $uid, $DB->sql_compare_text('courseid') => $cid])){
            return false;
        } else {
            $docsid = $this->get_docsid($uid, $cid);
            $record = $DB->get_record_sql('SELECT employercomment FROM {activityrecord_docs_info} WHERE docsid = ? and id = ?',[$docsid, $id]);
            return $record->employercomment;
        }
    }

    //Delete a activity record from the id provided
    public function del_record_data($id){
        global $DB;
        if(empty($_SESSION['ar_records_uid']) || empty($_SESSION['ar_records_cid'])){
            return false;
        }
        $docsid = $this->get_docsid($_SESSION['ar_records_uid'], $_SESSION['ar_records_cid']);
        $file = $DB->get_record_sql('SELECT employercomment FROM {activityrecord_docs_info} WHERE docsid = ? and id = ?',[$docsid, $id])->employercomment;
        if(file_exists('../pdf/employercomment/'.$file)){
            unlink('../pdf/employercomment/'.$file);
        }
        if($DB->delete_records('activityrecord_docs_info', [$DB->sql_compare_text('docsid') => $docsid, $DB->sql_compare_text('id') => $id])){
            return true;
        } else {
            return false;
        }
    }

    //Update activity record for a specific id
    public function update_activity_record($data){
        global $DB;
        if(empty($_SESSION['ar_records_uid']) || empty($_SESSION['ar_records_cid']) || empty($_SESSION['ar_records_rid'])){
            return false;
        }
        $docsid = $this->get_docsid($_SESSION['ar_records_uid'], $_SESSION['ar_records_cid']);
        if(!$DB->record_exists_sql('SELECT * FROM {activityrecord_docs_info} WHERE docsid = ? AND id = ? AND reviewdate = ? AND (learnsign IS NULL OR learnsigndate IS NULL OR ntasign IS NULL OR ntasigndate IS NULL OR employercomment IS NULL)',[$docsid, $_SESSION['ar_records_rid'], $data[1]])){
            return false;
        }
        $file = $DB->get_record_sql('SELECT employercomment FROM {activityrecord_docs_info} WHERE docsid = ? and id = ?',[$docsid, $_SESSION['ar_records_rid']])->employercomment;
        if($data[25] != $file && $file != null && $data[25] != null && !empty($data[25]) && !empty($file)){
            if(file_exists('../pdf/employercomment/'.$file)){
                unlink('../pdf/employercomment/'.$file);
            }
        } elseif($file != null) {
            $data[25] = $file;
        }
        $record = new stdClass();
        $record->id = $_SESSION['ar_records_rid'];
        $record->docsid = $docsid;
        $record->apprentice = $data[0];
        $record->reviewdate = $data[1];
        $record->standard = $data[2];
        $record->employerandstore = $data[3];
        $record->coach = $data[4];
        $record->managerormentor = $data[5];
        $record->progress = $data[6];
        $record->expectprogress = $data[7];
        $record->progresscom = $data[8];
        $record->hours = $data[9];
        $record->expecthours = $data[10];
        $record->otjhcom = $data[11];
        $record->recap = $data[12];
        $record->impact = $data[13];
        $record->details = $data[14];
        $record->detailsksb = $data[15];
        $record->detailimpact = $data[16];
        $record->todaymath = $data[17];
        $record->nextmath = $data[18];
        $record->todayeng = $data[19];
        $record->nexteng = $data[20];
        $record->alnsupport = $data[21];
        $record->coachfeedback = $data[22];
        $record->safeguarding = $data[23];
        $record->agreedaction = $data[24];
        $record->employercomment = $data[25];
        $record->apprenticecomment = $data[26];
        $record->ntasigndate = $data[27];
        $record->ntasign = $DB->get_record_sql('SELECT coachsign FROM {trainingplan_setup} WHERE userid = ? AND teachid = ? AND courseid = ?',[$_SESSION['ar_records_uid'], $this->get_userid(), $_SESSION['ar_records_cid']])->coachsign;
        $record->nextdate = $data[28];
        $record->nexttype = $data[29];
        $record->healthandsafety = $data[30];
        $record->equalityad = $data[31];
        $record->informationaag = $data[32];
        if($DB->update_record('activityrecord_docs_info', $record, true)){
            return true;
        } else {
            return false;
        }
    }

    //Check if a setup exists for a specific userid and course id for a learner
    public function check_setup_exists_learner($cid){
        global $DB;
        if($DB->record_exists('trainingplan_setup', [$DB->sql_compare_text('userid') => $this->get_userid(), $DB->sql_compare_text('courseid') => $cid])){
            return true;
        } else {
            return false;
        }
    }

    //Check if a learner signature exists for a specific course id
    public function check_learn_sign_exists($cid){
        global $DB;
        $record = $DB->get_record_sql('SELECT learnersign FROM {trainingplan_setup} WHERE userid = ? and courseid = ?',[$this->get_userid(), $cid]);
        if($record->learnersign != '' && $record->learnersign != null){
            return true;
        } else {
            return false;
        }
    }

    //Get activity record list for a learner
    public function get_docs_list_learn($cid){
        return $this->get_docs_list($this->get_userid(), $cid);
    }

    //Get activity record data for a learner
    public function get_record_data_learn($id){
        global $DB;
        if(empty($_SESSION['ar_lrecord_cid'])){
            return false;
        } else {
            $cid = $_SESSION['ar_lrecord_cid'];
            $uid = $this->get_userid();
            $docsid = $this->get_docsid($uid, $cid);
            $record = $DB->get_record_sql('SELECT * FROM {activityrecord_docs_info} WHERE docsid = ? and id = ?',[$docsid, $id]);
            $array = [
                $record->apprentice,
                date('Y-m-d',$record->reviewdate),
                $record->standard,
                $record->employerandstore,
                $record->coach,
                $record->managerormentor,
                $record->progress,
                $record->expectprogress,
                $record->progresscom,
                $record->hours,
                $record->expecthours,
                $record->otjhcom,
                $record->recap,
                $record->impact,
                $record->details,
                $record->detailsksb,
                $record->detailimpact,
                $record->todaymath,
                $record->nextmath,
                $record->todayeng,
                $record->nexteng,
                $record->alnsupport,
                $record->coachfeedback,
                $record->safeguarding,
                $record->agreedaction,
                $record->apprenticecomment,
                $record->employercomment,
                date('Y-m-d',$record->ntasigndate),
                date('Y-m-d',$record->learnsigndate),
                str_replace(" ","+",$record->learnsign),
                str_replace(" ","+",$record->ntasign),
                date('Y-m-d H:m',$record->nextdate),
                $record->nexttype,
                $record->healthandsafety,
                $record->equalityad,
                $record->informationaag
            ];
            return $array;
        }
    }

    //Get pdf filename and record date
    public function get_filename_learn($id, $cid){
        global $DB;
        $uid = $this->get_userid();
        if(!$DB->record_exists('activityrecord_docs', [$DB->sql_compare_text('userid') => $uid, $DB->sql_compare_text('courseid') => $cid])){
            return false;
        } else {
            $docsid = $this->get_docsid($uid, $cid);
            $record = $DB->get_record_sql('SELECT employercomment FROM {activityrecord_docs_info} WHERE docsid = ? and id = ?',[$docsid, $id]);
            return $record->employercomment;
        }
    }

    //Update activity record for a learner
    public function update_activity_record_learner($data){
        global $DB;
        if(!isset($_SESSION['ar_lrecord_cid']) || !isset($_SESSION['ar_lrecords_rid'])){
            return false;
        } else {
            $uid = $this->get_userid();
            $rid = $_SESSION['ar_lrecords_rid'];
            $cid = $_SESSION['ar_lrecord_cid'];
            if(!$DB->record_exists('activityrecord_docs', [$DB->sql_compare_text('userid') => $uid, $DB->sql_compare_text('courseid') => $cid])){
                return false;
            } else {
                $docsid = $this->get_docsid($uid, $cid);
                if(!$DB->record_exists_sql('SELECT * FROM {activityrecord_docs_info} WHERE docsid = ? AND id = ? AND (learnsign IS NULL OR learnsigndate IS NULL OR ntasign IS NULL OR ntasigndate IS NULL OR employercomment IS NULL)',[$docsid, $_SESSION['ar_lrecords_rid']])){
                    return false;
                }
                $record = new stdClass();
                $record->id = $rid;
                $record->docsid = $docsid;
                $record->learnsigndate = $data[0];
                $record->learnsign = $DB->get_record_sql('SELECT learnersign FROM {trainingplan_setup} WHERE userid = ? and courseid = ?',[$uid, $cid])->learnersign;
                $record->apprenticecomment = $data[1];
                if($DB->update_record('activityrecord_docs_info', $record, true)){
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    //Get date for a activity report for a coach
    public function get_ar_date($id){
        global $DB;
        if(empty($_SESSION['ar_records_uid']) || empty($_SESSION['ar_records_cid'])){
            return false;
        } else {
            $uid = $_SESSION['ar_records_uid'];
            $cid = $_SESSION['ar_records_cid'];
            $docsid = $this->get_docsid($uid, $cid);
            $record = $DB->get_record_sql('SELECT reviewdate FROM {activityrecord_docs_info} WHERE docsid = ? AND id = ?',[$docsid, $id]);
            return date('Y-m-d',$record->reviewdate);
        }
    }

    //Get date for a activity report for a learner
    public function get_ar_date_learn($id){
        global $DB;
        if(!isset($_SESSION['ar_lrecord_cid'])){
            return false;
        } else {
            $uid = $this->get_userid();
            $cid = $_SESSION['ar_lrecord_cid'];
            if(!$DB->record_exists('activityrecord_docs', [$DB->sql_compare_text('userid') => $uid, $DB->sql_compare_text('courseid') => $cid])){
                return false;
            } else {
                $docsid = $this->get_docsid($uid, $cid);
                $record = $DB->get_record_sql('SELECT reviewdate FROM {activityrecord_docs_info} WHERE docsid = ? AND id = ?',[$docsid, $id]);
                return date('Y-m-d',$record->reviewdate);
            }
        }
    }
}