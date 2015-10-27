// origin
$dblink1=mysql_connect('contentadmin.embibe.com', 'content_ro', 'c0ntent_r0'); 
mysql_select_db('db1', $dblink1);  

// destination
$dblink2=mysql_connect('localhost', 'Afzal', '123'); 
mysql_select_db('db2',$dblink2); 

$res_from = mysql_query("show tables", $dblink1); 
if($res_from === FALSE) {
    die(mysql_error()); // for error handling
}

while($table = mysql_fetch_array($res_from)) { 
echo($table[0] . "<br />"); // optional, only for show the name tables

$table=$table[0];

$tableinfo = mysql_fetch_array(mysql_query("SHOW CREATE TABLE $table  ",$dblink1)); // retrieve table structure from mysite1.com 

mysql_query(" $tableinfo[1] ",$dblink2); // use found structure to make table on mysite2.com

$res_to = mysql_query("SELECT * FROM $table  ",$dblink1); // select all content from table mysite1.com  
while ($row = mysql_fetch_array($res_to, MYSQL_ASSOC) ) {
    $sql_error = "";
    mysql_query("INSERT INTO $table (".implode(", ",array_keys($row)).") VALUES ('".implode("', '",array_values($row))."')",$dblink2); // insert one row into new table on mysite2.com`enter code here`
    $sql_error = mysql_error($dblink2);
}
if ($sql_error) {
    echo $sql_error;
}else {
    echo "copy table ".$table." done.<br />";
}
    flush();
}

mysql_close($dblink1); 
mysql_close($dblink2);