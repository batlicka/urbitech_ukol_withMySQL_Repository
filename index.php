<?php
$error = '';
$date = '';
$note='';
$SendedButtonID="";

//proč nestačí definovat zde? Pro funkci get_data()
$servername = "localhost";
$username = "root";
$password = "";

function clean_text($string)
{
  $string = trim($string);//vyjme white space z pravé a levé strany stringu
  $string = stripslashes($string);//vyjme lomítka ze stringu
  $string = htmlspecialchars($string);//Convert special characters to HTML entities
  return $string;
}

if(isset($_POST["submit"])) {
    if(empty($_POST["date"])){
        $error .='<p><label class="text-danger">please enter date</label></p>';  
    } else {
        $date=clean_text($_POST["date"]);    
    }
    if(empty($_POST["note"])){
        $error .='<p><label class="text-danger">please enter note</label></p>';
    }else{
        $note=clean_text($_POST["note"]);      
    }
  }
  
//ukládání zadaných hodnot do Databáze
if($error =='' && $note != '' && $date != ''){
    try{        
        $conn = new PDO("mysql:host=$servername;dbname=todolist", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);        
        $sql_question = "INSERT INTO todotable (date, content, done) VALUES ('$date', '$note', 'aktualni')";
        $conn->exec($sql_question);
        echo 
        $conn = null;
        header('Location: index.php');//redirect - řekne prohlížeči po odeslání formuláře, aby se reloadoval a zapomenul aktuální stav
        exit;
    }
    catch(PDOException $e){
        echo "Connection failed: " . $e->getMessage();
    }          
}
//pro update při splnění úkolu + mazani
  if (isset($_GET["ok"])){  
    //předám si číslo řádku na kterém se vyskytovalo poklikané tlačítko OK
    $SendedButtonID = $_GET["ButtonID"];   
    try{
        $conn= new PDO("mysql:host=$servername;dbname=todolist", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql_question= "UPDATE todotable SET done='OK' where id=$SendedButtonID";
        $conn->exec($sql_question);
        //$conn = null;
    }
    catch(PDOException $e){
        echo "Connection failed: " . $e->getMessage();
    }
  }
  else if(isset($_GET["deleteButton"])){
    echo "was pressed delete" .  "button id: ";// . $SendedButtonID;
    $SendedButtonID = $_GET["ButtonID"];
    try{      
        $conn= new PDO("mysql:host=$servername;dbname=todolist", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql_question= "DELETE FROM todotable where id=$SendedButtonID";
        $conn->exec($sql_question);
        $conn = null;
    }
    catch(PDOException $e){
        echo "Connection failed: " . $e->getMessage();
    }
  }

//reading from Database, return array of arrays
function get_data()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    
    $data = [];
    $row = 0;
    try{
        $conn= new PDO("mysql:host=$servername;dbname=todolist", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("SELECT id, date, content, done FROM todotable"); 
        $stmt->execute();
        //Returns an array containing all of the result set rows
        $data = $stmt->fetchAll(PDO::FETCH_NUM);
        return $data;
        echo "Connected successfully"; 
        $conn = null; 
    }catch(PDOException $e){
        echo "Connection failed: " . $e->getMessage();
        return null;
    }     
}

function set_tableRow()
{
  if(get_data()!=NULL){
    $dataT=get_data();
    //for ($i = 0; $i < count($dataT); $i++) {
    //  $row = $dataT[$i];
    //}    
    $table_str = '';
    foreach ($dataT as $row) {
      $table_str.='<tr>';
        $table_str.='<th scope="row">' . $row[0]. '</th>';
        $table_str.='<td>' . $row[1] . '</td>';
        $table_str.='<td><a href="edit.php?id='. $row[0].'">' . $row[2] . '</a></td>';              
        //zkontroluji jestli je v posledním sloupci "done" příznak OK
        if($row[(count($row)-1)] == 'OK'){            
            $table_str.='<td>'. $row[3]. '</td>';  
            //musím si udržovat pořád index řádku, případě že je poklikano delte
            $table_str.='<td><form method="get">';
            $buttonID = $row[0];          
            $table_str.='<input type="hidden" name="ButtonID" value="'.$buttonID.'">';
            $table_str.='<input type="submit" name="deleteButton"  class="btn btn-primary" value="delete">';
            $table_str.= '</form></td>';  
            $table_str.='</tr>'; 
        }
        else{
          $table_str.='<td><a href="index.php?val=akci">aktualni</a></td>';
          $table_str.='<td><form method="get">';
          $buttonID = $row[0];
          $table_str.='<input type="hidden" name="ButtonID" value="'.$buttonID.'">';
          $table_str.='<input type="submit" name="deleteButton"  class="btn btn-primary" value="delete">';
          $table_str.='<input type="submit" name="ok"  class="btn btn-primary" value="confirmed">';          
          $table_str.= '</form></td>';               
          $table_str.='</tr>'; 
        }               
    }
    return $table_str;      
  }
  else
    echo "žádná data k zobrazení";   
}

if(isset($_GET["val"]))
{
  echo "poklikanim na položku aktualni jsi přidal pomocí metody get, do adresního řádku dvojici val=akci, nastaveni hodnoty val bylo vyhodnoceno v php skriptu a vyvolalo tuto " . $_GET["val"];
}
?>

<!doctype html>
<html class="no-js" lang="">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title></title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="manifest" href="site.webmanifest"> 
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
</head>
<body>
      <!--??? zakomponování externího php soubor<form action="obsluha.php" method="post">-->
      <form method="post">
      <h3 align="center">To do list</h3>
      <br/>
      <?php echo $error; ?>
      <div class = "form-group">
        <label>Enter date</label>        
        <input type="date" class="form-control" name="date" placeholder="enter date in format day-month-year"  >
      </div>    
      <div class = "form-group">
        <label>Enter note</label>
        <input type="note" class="form-control" name="note" placeholder="enter note" >
      </div>
      <div  align="center">
        <input type="submit" class="btn btn-info" name="submit" value="save note"> 
        <!--when button is clicked is created pair submit=>"save note" then php form notice existence of pair and execute code in script-->
      </div>      
    </form>

    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th scope="col">#</th>
          <th scope="col">date</th>
          <th scope="col">note</th>    
          <th scope="col">stav</th>      <!--vyřešeno-->  
        </tr>
      </thead>
      <tbody>
        <?php echo set_tableRow(); ?>        
      </tbody>
    </table>

  <script src="js/vendor/modernizr-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script>window.jQuery || document.write('<script src="js/vendor/jquery-3.3.1.min.js"><\/script>')</script>
  <script src="js/plugins.js"></script>
  <script src="js/main.js"></script>  
</body>

</html>

<!--Při té editaci místo do placeholderu bys to měl dat do value-->