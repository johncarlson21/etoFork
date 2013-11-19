<?php
/*
 *	Etomite 1.x Backup Script
 *	Author: John Carlson <johncarlson21@gmail.com>
 *	Desctiption:
 *	Run this script in your browser to make a backup of your database
 *	and your site files.
 *	Usage: Upload this file to your Etomite Folder, then visit this page in your browser.
 *	Example: http://yourdomain.com/etomite_backup.php
 *	The Etomite folder must be writeable by the webserver
 *
*/

/* ********* FUNCTIONS ************ */
function backup_tables($host,$user,$pass,$name,$tables = '*')
{
	$link = mysql_connect($host,$user,$pass);
	mysql_select_db($name,$link) or die('Error: '.mysql_error());
	
	//get all of the tables
	if($tables == '*')
	{
		$tables = array();
		$result = mysql_query('SHOW TABLES');
		while($row = mysql_fetch_row($result))
		{
			$tables[] = $row[0];
		}
	}
	else
	{
		$tables = is_array($tables) ? $tables : explode(',',$tables);
	}
	
	//cycle through
	foreach($tables as $table)
	{
		$result = mysql_query('SELECT * FROM '.$table);
		$num_fields = mysql_num_fields($result);
		
		$return.= 'DROP TABLE '.$table.';';
		$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
		$return.= "\n\n".$row2[1].";\n\n";
		
		for ($i = 0; $i < $num_fields; $i++) 
		{
			while($row = mysql_fetch_row($result))
			{
				$return.= 'INSERT INTO '.$table.' VALUES(';
				for($j=0; $j<$num_fields; $j++) 
				{
					$row[$j] = addslashes($row[$j]);
					$row[$j] = ereg_replace("\n","\\n",$row[$j]);
					if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
					if ($j<($num_fields-1)) { $return.= ','; }
				}
				$return.= ");\n";
			}
		}
		$return.="\n\n\n";
	}
	
	//save file
	$fname = 'etomite-db-backup-'.time().'.sql';
	$handle = fopen($fname,'w+');
	fwrite($handle,$return);
	fclose($handle);
	return $fname;
}

// get db info from current etomite config
include_once('manager/includes/config.inc.php');

// backup db
$fname = backup_tables($database_server,$database_user,$database_password,str_replace("`","",$dbase));

// zip up files
$zipFname = 'etomite-backup-'.time().'.zip';
@exec('zip -r '.$zipFname.' * -x etomite_backup.php');

header('Location: '.$zipFname);

?>