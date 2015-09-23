<?php
//script to obtain runner location information from my database, will be retrieved using ajax

//get constants for database
require("database.php");

// Start XML file, create parent node

$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);

// Opens a connection to a MySQL server
$connection = mysqli_connect ($server, $username, $password);
if (!$connection){  
    die('Not connected : ' . mysqli_error());
    }

// Set the active MySQL database
$db_selected = mysqli_select_db($connection, $database);
if (!$db_selected) {
  die ('Can\'t use db : ' . mysqli_error($connection));
}

// Select all the rows in the markers table
$query = "SELECT * FROM route WHERE 1";
$result = mysqli_query($connection, $query);
if (!$result){
  die('Invalid query: ' . mysqli_error($connection));
}

//set header to xml
\header("Content-type: text/xml");

// Iterate through the rows, adding XML nodes for each
while ($row = mysqli_fetch_assoc($result)){
  // ADD TO XML DOCUMENT NODE
  $node = $dom->createElement("marker");
  $newnode = $parnode->appendChild($node);
 
  $newnode->setAttribute("lat", $row['lat']);
  $newnode->setAttribute("lng", $row['lng']);
  $newnode->setAttribute("alt", $row['alt']);
  $newnode->setAttribute("id", $row['id']);
}

echo $dom->saveXML();

?>




