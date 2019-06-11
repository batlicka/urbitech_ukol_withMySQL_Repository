<?php
//tipy pro zlepšení uložit do externího php souboru

$error = '';
$date = '';
$note='';

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

$clikedID = $_GET['id'];
if(isset($_POST["submit"])) {  
  $conn= new PDO("mysql:host=$servername;dbname=todolist", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //update of note
  if(empty($_POST["update_note"])){} //vrací true pokud je proměnná prázdná, pokud v sobě něco obsahuje vrací false 
  else{
    $EditedNote=clean_text($_POST["update_note"]);  
    try{      
      $sql_question= "UPDATE todotable SET content= '$EditedNote' where id=$clikedID";//proč nele z id = $_GET['id'] nacitat opakovane?
      $conn->exec($sql_question);       
    }
    catch(PDOException $e){
        echo "Connection failed: " . $e->getMessage();
    } 
  } 
  //update of date 
  if(empty($_POST["update_date"])){}
  else{
      $EditedDate=clean_text($_POST["update_date"]);  
      try{      
        $sql_question= "UPDATE todotable SET date= '$EditedDate' where id=$clikedID";//proč nele z id = $_GET['id'] nacitat opakovane?
        $conn->exec($sql_question);        
        $conn = null;
      }
      catch(PDOException $e){
          echo "Connection failed: " . $e->getMessage();
      } 
  }          
}

function returnData(){
    $clikedID = $_GET['id'];
    $servername = "localhost";
    $username = "root";
    $password = "";    
    $data = [];
    $row = 0;
    try{
        $conn= new PDO("mysql:host=$servername;dbname=todolist", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("SELECT id, date, content, done FROM todotable WHERE id=$clikedID"); 
        $stmt->execute();
        //Returns an array containing all of the result set rows
        $data = $stmt->fetch(PDO::FETCH_NUM);                      
        return $data;                
        $conn = null; 
    }catch(PDOException $e){
        echo "Connection failed: " . $e->getMessage();
        return null;
    }
}
function getEditedRow(){
  $array = returnData();
  if($array !=null){
    $table_str="";    
      $table_str.='<th scope="row">' . $array[0]. '</th>';
      $table_str.='<td>' . $array[1] . '</td>';
      $table_str.='<td>' . $array[2] . '</td>';
      $table_str.='<td>' . $array[3] . '</td>';    
  }
  return $table_str;  
}

function VratDate(){
  if(returnData()!=NULL){
    $arr = returnData();
    $dateV =$arr[1];
    return $dateV;
  }
}
?>