<?php
//simple approach for trying to use the system command (doesn't work properly)
//tutorial:
//http://www.php-mysql-tutorial.com/wikis/mysql-tutorials/using-php-to-backup-mysql-databases.aspx

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = 'root';
$dbname = 'projectmanagement';


$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die                      ('Error connecting to mysql');
mysql_select_db($dbname);


$backupFile = $dbname . date("Y-m-d-H-i-s")  . '.gz';
$command = "mysqldump --opt -h $dbhost -u $dbuser -p$dbpass $dbname | gzip > $backupFile";
//echo $command;

system($command);


// an example of closedb.php
// it does nothing but closing
// a mysql database connection

mysql_close($conn);