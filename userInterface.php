
<!DOCTYPE html >
<head>    
    <link rel="icon" type="image/png" href="http://2.bp.blogspot.com/-Khmw4e4ChOQ/UbSGNP9lSiI/AAAAAAAAC-U/DNcYoNYK2XU/s1600/favicon.gif">
    <link rel="stylesheet" type="text/css" href="styling.css">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>Real-Time Run</title>
</head>

<body id='uiContainer'>
    <div id='welcome'>
        <h1><em>Welcome! Please select a User!</em></h1>
    
    <div id='userList'>
        
        <?php
        //get constants for database
        require("database.php");
        
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
        
        $query = "SELECT * FROM route WHERE 1";
        $result = mysqli_query($connection, $query);
     
       
            echo '<select name="list" style="width:400px;">';
              
                while($r = mysqli_fetch_assoc($result)){
                    echo "<option>".$r['alt']."</option>"; 
                }
                
            echo '</select>';
            ?>
        <div id='buttonHolder'>
             <button id='button' type="button">Get Started!</button> 
        </div>
    </div>
  </div>
    
</body>
</html>