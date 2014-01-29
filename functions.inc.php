<?php



function timequeues_get_config($engine) 
{
class ext_Execiftime extends extension {
      var $true_priority;
       var $condition;
       function ext_Execiftime($condition, $true_priority) {
           global $version;
           if (version_compare($version, "1.6", "ge")) {
         //change from '|' to ','
         $this->condition = str_replace("|", ",", $condition);
             }
         else {
             $this->condition = $condition;
             }
         $this->true_priority = $true_priority;
       }
       function output() {
         return 'ExecIfTime(' .$this->condition. '?' .$this->true_priority. ')' ;
       }
       function incrementContents($value) {
         $this->true_priority += $value;
       }
     }

    global $ext;  // is this the best way to pass this?
    global $conferences_conf;

    switch($engine) 
    {
        case "asterisk":
            $timelist = timeconditions_list(true);
            if(is_array($timelist))
        {
                foreach($timelist as $item)
            {
                    // add dialplan
          // note we don't need to add 2nd optional option of true, gotoiftime will convert '|' to ',' for 1.6+
                    $times = timeconditions_timegroups_get_times($item['time']);
$queueno = $item['timequeue'];
//$queueno = substr(trim($queueno),11,3);
$extno = $item['agent'];
//$extno = substr(trim($extno),11,3);
$ext->add('ext-did-0001', s, '', new  ext_Set('le'.$extno,'FALSE'));
$extnos = array();
                    if (is_array($times))
                {
foreach ($times as $time)
                    {
if (array_key_exists($extno,$extnos))
{

}
else
{
$extnos[] = $extno;
}

}
                        foreach ($times as $time)
                    {
$ext->add('ext-did-0001', s, '', new  ext_Execiftime($time[1],'Set(le'.$extno.'=TRUE)'));
                    }
                }
if ($item['enabled']=="0") {
$ext->add('ext-did-0001', s, '', new  ext_Set('le'.$extno,'FALSE'));
                            }
$ext->add('ext-did-0001', s, '',new ext_Execif('$[${le'.$extno.'}=TRUE]',AddQueueMember,$queueno.',Local/'.$extno.'@from-queue/n,,,'.$extno.',sip/'.$extno,RemoveQueueMember,$queueno.',Local/'.$extno.'@from-queue/n'));

} //end of timelist
            
        }// end of case asterisk
        break;
    } // end of case engine
}//end of get config

function timequeues_check_destinations($dest=true) {
    global $active_modules;

    $destlist = array();
    if (is_array($dest) && empty($dest)) {
        return $destlist;
    }
    $sql = "SELECT timeconditions_id, displayname, timequeue, agent, enabled FROM timeconditions ";
    if ($dest !== true) {
        $sql .= "WHERE (timequeue in ('".implode("','",$dest)."') ) OR (agent in ('".implode("','",$dest)."') )";
    }
    $results = sql($sql,"getAll",DB_FETCHMODE_ASSOC);

    $type = isset($active_modules['timeconditions']['type'])?$active_modules['timeconditions']['type']:'setup';

    foreach ($results as $result) {
        $thisdest    = $result['timequeue'];
        $thisid      = $result['timeconditions_id'];
        $description = sprintf(_("Time Condition: %s"),$result['displayname']);
        $thisurl     = 'config.php?display=timeconditions&itemid='.urlencode($thisid);
        if ($dest === true || $dest = $thisdest) {
            $destlist[] = array(
                'dest' => $thisdest,
                'description' => $description,
                'edit_url' => $thisurl,
            );
        }
    }
    return $destlist;
}
function timequeues_change_extension($old_dest, $new_dest) {
	$sql = 'UPDATE timeconditions SET truegoto = "' . $new_dest . '" WHERE truegoto = "' . $old_dest . '"';
	sql($sql, "query");
	
	$sql = 'UPDATE timeconditions SET falsegoto = "' . $new_dest . '" WHERE falsegoto = "' . $old_dest . '"';
	sql($sql, "query");
}

function timequeues_add($post){
    if(!timequeues_chk($post)) {
        return false;
    }
    extract($post);

    // $time = timeconditions_get_time( $hour_start, $minute_start, $hour_finish, $minute_finish, $wday_start, $wday_finish, $mday_start, $mday_finish, $month_start, $month_finish);

    if(empty($displayname)) {
         $displayname = "unnamed";
    }
	$enable=!$disable;
    $results = sql("INSERT INTO timeconditions (displayname,time,truegoto,falsegoto,timequeue,agent,enabled,deptname) values (\"$displayname\",\"$time\",\"${$goto0.'0'}\",\"${$goto1.'1'}\",\"$timequeue\",\"$agent\",\"$enable\",\"$deptname\")");
}

function timequeues_edit($id,$post){
    if(!timequeues_chk($post)) {
        return false;
    }
    extract($post);

    // $time = timeconditions_get_time( $hour_start, $minute_start, $hour_finish, $minute_finish, $wday_start, $wday_finish, $mday_start, $mday_finish, $month_start, $month_finish);
    
    if(empty($displayname)) { 
        $displayname = "unnamed";
    }
	$enable=!$disable;
    $results = sql("UPDATE timeconditions SET displayname = \"$displayname\", time = \"$time\", timequeue = \"$timequeue\", agent = \"$agent\", enabled = \"$enable\", deptname = \"$deptname\" WHERE timeconditions_id = \"$id\"");
}

// ensures post vars is valid
function timequeues_chk($post){
    return true;
}
    function timequeues_del($id){
        global $astnam;
        $results = sql("DELETE FROM timeconditions WHERE timeconditions_id = \"$id\"","query");
        
        $fcc = new featurecode('timeconditions', 'toggle-mode-'.$id);
        $fcc->delete();
        unset($fcc);
        if ($astman != null) {
            $astman->database_del("TC",$id);
        }
    }



/*
The following functions are available to other modules.

function timequeuemembers_list_queues()
	returns an array of id and descriptions for any time groups defined by the user
	the array contains inidces 0 and 1 for the rnav and associative value and text for select boxes


function timeconditions_timegroups_buildtime( $hour_start, $minute_start, $hour_finish, $minute_finish, $wday_start, $wday_finish, $mday_start, $mday_finish, $month_start, $month_finish) 
	should never be needed by another module, as this module should be the only place creating the time string, as it returns the string to other modules.

function timequeuemembers__drawqueueselects($name, $time)
	should never be needed by another module, as this module should be the only place drawing the time selects
*/
//$sql = "select extension, descr from queues_config order by descr";
//lists any time groups defined by the user
function timequeuemembers_list_queues() {
global $db;
	$tmparray = array();

	$sql = "select extension, descr from queues_config order by descr";
	$results = $db->getAll($sql);
	if(DB::IsError($results)) {
		$results = null;
	}

	foreach ($results as $val) {
		$tmparray[] = array("value" => $val[0],"text" => $val[1]." (".$val[0].")");
	}
	return $tmparray;


}

function timequeuemembers_drawqueueselects($elemname, $currentvalue = '', $canbeempty = true, $onchange = '', $default_option = '') {
	global $tabindex;
	$output = '';
	$onchange = ($onchange != '') ? " onchange=\"$onchange\"" : '';
	
	$output .= "\n\t\t\t<select name=\"$elemname\" tabindex=\"".++$tabindex."\" id=\"$elemname\"$onchange>\n";
	// include blank option if required
	if ($canbeempty) {
		$output .= '<option value="">'.($default_option == '' ? _("== Select a Queue ==") : $default_option).'</option>';			
	}
	// build the options
	$valarray = timequeuemembers_list_queues();
	foreach ($valarray as $item) {
		$itemvalue = (isset($item['value']) ? $item['value'] : '');
		$itemtext = (isset($item['text']) ? _($item['text']) : '');
		$itemselected = ($currentvalue == $itemvalue) ? ' selected' : '';
		
		$output .= "\t\t\t\t<option value=\"$itemvalue\"$itemselected>$itemtext</option>\n";
	}
	$output .= "\t\t\t</select>\n\t\t";
	return $output;
}
    function timequeuemembers_list_extensions() {
        global $db;
        $tmparray = array();
        
        $sql = "select extension, descr from queues_config order by descr";
        $results = $db->getAll($sql);
        if(DB::IsError($results)) {
            $results = null;
        }
        
        $names = core_users_list();
        foreach ($names as $val) {
            $tmparray[] = array("value" => $val[0],"text" => $val[1]." (".$val[0].")");
        }
        return $tmparray;
        
        
    }
    function timequeuemembers_drawextensionselects($elemname, $currentvalue = '', $canbeempty = true, $onchange = '', $default_option = '') {
        global $tabindex;
        $output = '';
        $onchange = ($onchange != '') ? " onchange=\"$onchange\"" : '';
        
        $output .= "\n\t\t\t<select name=\"$elemname\" tabindex=\"".++$tabindex."\" id=\"$elemname\"$onchange>\n";
        // include blank option if required
        if ($canbeempty) {
            $output .= '<option value="">'.($default_option == '' ? _("== Select Extension ==") : $default_option).'</option>';
        }
        // build the options
        $valarray = timequeuemembers_list_extensions();
        foreach ($valarray as $item) {
            $itemvalue = (isset($item['value']) ? $item['value'] : '');
            $itemtext = (isset($item['text']) ? _($item['text']) : '');
            $itemselected = ($currentvalue == $itemvalue) ? ' selected' : '';
            
            $output .= "\t\t\t\t<option value=\"$itemvalue\"$itemselected>$itemtext</option>\n";
        }
        $output .= "\t\t\t</select>\n\t\t";
        return $output;
    }


?>