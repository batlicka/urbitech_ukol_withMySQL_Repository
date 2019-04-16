$error = '';
$date = '';
$note='';

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
        $sql_question = "INSERT INTO todotable (date, content, done) VALUES ('2019-03-20', '$note', 'aktualni')";
        $conn->exec($sql_question);
        echo 
        $conn = null;
    }
    catch(PDOException $e){
        echo "Connection failed: " . $e->getMessage();
    }          
}

//update při splnění úkolu
if (isset($_GET["ok"])){  
  //předám si číslo řádku na kterém se vyskytovalo poklikané tlačítko OK
  $SendedButtonID = $_GET["ButtonID"];   
  try{
      $conn= new PDO("mysql:host=$servername;dbname=todolist", $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql_question= "UPDATE todotable SET done='OK' where id=$SendedButtonID";
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
//<a href="edit.php">POZVÁNKY NA AKCE</a>
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
        //<td><a href="edit.php">ostrihat vetve</a></td>
        //zkontroluji jestli je v posledním sloupci "done" příznak OK
        if($row[(count($row)-1)] == 'OK'){            
            $table_str.='<td>'. $row[3]. '</td>';
            $table_str.='<td></td>';
            $table_str.='</tr>'; 
        }
        else{
          $table_str.='<td>aktualni</td>';
          $table_str.='<td><form method="get">';
          $buttonID = $row[0];
          $table_str.='<input type="hidden" name="ButtonID" value="'.$buttonID.'">';
          $table_str.='<input type="submit" name="ok"  class="btn btn-primary" value="ok">';
          $table_str.= '</form></td>';               
          $table_str.='</tr>'; 
        }               
    }
    return $table_str;      
  }
  else
    echo "žádná data k zobrazení";   
}