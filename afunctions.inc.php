<?php
class ext_execiftime extends extension {
	  var $true_priority;
 	  var $condition;
 	  function ext_execiftime($condition, $true_priority) {
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


function timequeues_get_config($engine) 
{
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
$ext->add('ext-did', s, '',new ext_removequeuemember($item['timequeue'],'SIP/'.$item['agent']));
					if (is_array($times))
				{
						foreach ($times as $time)
					{
							$ext->add('ext-did', s, '', new  ext_Execiftime($time[1],'AddQueueMember('.$item['timequeue'].',SIP/'.$item['agent'].')'));
					}
				}

			}
		}
		break;
	}
}
?>
