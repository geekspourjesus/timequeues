<?php /* $Id: install.php $ */

if($amp_conf["AMPDBENGINE"] == "sqlite3")  {
	$sql = "
	CREATE TABLE IF NOT EXISTS timeconditions (
		`timeconditions_id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
		`displayname` VARCHAR( 50 ) ,
		`time` INT( 11 ) ,
		`truegoto` VARCHAR( 50 ) ,
		`falsegoto` VARCHAR( 50 ),
		`deptname` VARCHAR( 50 )
	)
	";
$sql = " ALTER TABLE `timeconditions` ADD `timequeue` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL AFTER `falsegoto`,
ADD`agent` VARCHAR(50) ,
ADD `agent2` VARCHAR(50) ,
ADD `agent3` VARCHAR(50) AFTER `timequeue`";

}
else  {
	$sql = "
	CREATE TABLE IF NOT EXISTS timeconditions (
		`timeconditions_id` INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
		`displayname` VARCHAR( 50 ) ,
		`time` INT( 11 ) ,
		`truegoto` VARCHAR( 50 ) ,
		`falsegoto` VARCHAR( 50 ),
		`deptname` VARCHAR( 50 )
	)
	";
$sql = " ALTER TABLE `timeconditions` ADD `timequeue` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL AFTER `falsegoto`,
ADD`agent4` VARCHAR(50) ,
ADD `agent5` VARCHAR(50) ,
ADD `agent6` VARCHAR(50) AFTER `timequeue`";
 
}
$check = $db->query($sql);
if(DB::IsError($check)) {
		die_freepbx("Can not create `timeconditions` table: " .  $check->getMessage() .  "\n");
}
?>
