/********************* TEST 01 BEGIN ***********************/
<?php
$dbConn = new PDO('pgsql:host=localhost;port=5432;dbname=dbtest;user=utest;password=ptest');

   $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode to exception
   echo 'Connected', '<br />';

$sql ='select * from anagrafica';
$s = $dbConn->query($sql);


foreach($s as $r)
{
	print_r($r);
	 echo '<br />';
}
?>
/********************* END TEST 01 ***********************/

/********************* TEST 02 BEGIN ***********************/
<?php
/**
 * Testing PDO MySQL Database Connection, query() and exec().
 * For CREATE TABLE, INSERT, DELETE, UPDATE:
 *   exec(): returns the affected rows.
 * For SELECT:
 *   query(): returns a result set.
 */
define('DB_HOST', 'localhost');  // MySQL server hostname
define('DB_PORT', '5432');       // MySQL server port number (default 3306)
define('DB_NAME', 'dbtest');       // MySQL database name
define('DB_USER', 'utest');   // MySQL username
define('DB_PASS', 'ptest');       // password
 
// Create a database connection to PostgreSQL server.
try {
   // new PDO('pgsql:host=hostname;port=number;dbname=database;user=username;password=pw')
   $dbConn = new PDO('pgsql:host=' . DB_HOST . ';'
                     . 'port=' . DB_PORT . ';'
                     . 'dbname=' . DB_NAME . ';'
                     . 'user=' . DB_USER . ';'
                     . 'password=' . DB_PASS);
   $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode to exception
   echo 'Connected', '<br />';
 
} catch (PDOException $e) {
   $fileName = basename($e->getFile(), ".php"); // File that triggers the exception
   $lineNumber = $e->getLine();          // Line number that triggers the exception
   die("[$fileName][$lineNumber] Database connect failed: " . $e->getMessage() . '<br />');
}
 
// Run SQL statements
try {
   // Use exec() to run a CREATE TABLE, DROP TABLE, INSERT, DELETE and UPDATE,
   // which returns the affected row count.
   $rowCount = $dbConn->exec('DROP TABLE IF EXISTS test');
   echo 'DROP TABLE: ', $rowCount, ' rows', '<br />';
 
   $rowCount = $dbConn->exec(
         'CREATE TABLE IF NOT EXISTS test (
           id SERIAL,
           name VARCHAR(20),
           PRIMARY KEY (id))');
   echo 'CREATE TABLE: ', $rowCount, ' rows', '<br />';
 
   $rowCount = $dbConn->exec("INSERT INTO test (id, name) VALUES (1001, 'peter')");
   echo 'INSERT INTO: ', $rowCount, ' rows', '<br />';
// Cannot called lastInsertId as nextval() was not called in the previous insert.
// echo 'LAST_INSERT_ID (of the AUTO_INCREMENT column) is ', $dbConn->lastInsertId('test_id_seq'), '<br />';
 
   $rowCount = $dbConn->exec("INSERT INTO test (name) VALUES ('paul'),('patrick')");
   echo 'INSERT INTO: ', $rowCount, ' rows', '<br />';
   echo 'LAST_INSERT_ID (of the AUTO_INCREMENT column) is ', $dbConn->lastInsertId('test_id_seq'), '<br />';
 
   // Use query() to run a SELECT, which returns a resultset.
   $sql = 'SELECT * FROM test';
   $resultset = $dbConn->query($sql);
   foreach ($resultset as $row) {
      echo 'Using column name: id=', $row['id'], ' name=', $row['name'], '<br />';
      echo 'Using column number: id=', $row[0], ' name=', $row[1], '<br />';
      print_r($row); // for illustrating the contents of resultset's row
                     // indexed by both column-name and 0-indexed column-number
      echo '<br />';
   }
 
   // Run again with FETCH_ASSOC.
   $resultset = $dbConn->query($sql, PDO::FETCH_ASSOC);
   foreach ($resultset as $row) {  // by column-name only
      echo 'Using column name: id=', $row['id'], ' name=', $row['name'], '<br />';
      print_r($row); // for illustrating the contents of resultset's row
                     // indexed by column-name only
      echo '<br />';
   }
 
   // PostgreSQL supports "INSERT ... RETURNING ID", which returns a resultset of IDs.
   $sql = "INSERT INTO test (name) VALUES ('john'), ('jimmy') RETURNING ID";
   $resultset = $dbConn->query($sql, PDO::FETCH_ASSOC);
   foreach ($resultset as $row) {
      echo "Last insert id is {$row['id']}<br />";
   }
 
   // Close the database connection  (optional).
   $dbConn = NULL;
 
} catch (PDOException $e) {
   $fileName = basename($e->getFile(), ".php"); // File that trigger the exception
   $lineNumber = $e->getLine();         // Line number that triggers the exception
   die("[$fileName][$lineNumber] Database error: " . $e->getMessage() . '<br />');
}
?>
/********************* END TEST 02 ***********************/


