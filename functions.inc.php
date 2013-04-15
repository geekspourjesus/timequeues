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

					if (is_array($times))
				{
						foreach ($times as $time)
					{
$ext->add('ext-did-0001', s, '', new  ext_iftime($time[1],Set(foo=1);

							$ext->add('ext-did-0001', s, '', new  ext_Execiftime($time[1],'AddQueueMember('.$queueno.',Local/'.$extno.'@from-queue/n,,,'.$extno.',sip/'.$extno.')'));

							if ($item['enabled']==0) {
								$ext->add('ext-did-0001', s, '',new ext_removequeuemember($queueno,'Local/'.$extno.'@from-queue/n'));
							}
					}
				}

			}
		}
		break;
	}
}

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
		$thisdest = $result['agent'];
		if ($dest === true || $dest = $thisdest) {
			$destlist[] = array(
				'dest' => $thisdest,
				'description' => $description,
				'edit_url' => $thisurl,
			);
		}
		$thisdest = $result['enabled'];
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
function timequeues_add($post){
	if(!timequeues_chk($post)) {
		return false;
	}
	extract($post);

	// $time = timeconditions_get_time( $hour_start, $minute_start, $hour_finish, $minute_finish, $wday_start, $wday_finish, $mday_start, $mday_finish, $month_start, $month_finish);

	if(empty($displayname)) {
	 	$displayname = "unnamed";
	}
	$results = sql("INSERT INTO timeconditions (displayname,time,timequeue,agent,deptname) values (\"$displayname\",\"$time\",\"${$goto0.'0'}\",\"${$goto1.'1'}\",\"$deptname\")");
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

	$results = sql("UPDATE timeconditions SET displayname = \"$displayname\", time = \"$time\", timequeue = \"${$goto0.'0'}\", agent = \"${$insexten.'1'}\", deptname = \"$deptname\" WHERE timeconditions_id = \"$id\"");
}

// ensures post vars is valid
function timequeues_chk($post){
	return true;
}



?>
