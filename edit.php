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
      <P>>edditing note number: <?php echo $clikedID ?></p>
      <!--externí php soubor<form action="obsluha.php" method="post">-->      
      <br/>
      <?php echo $error; ?>
    
      <table class="table">
      <thead class="thead-dark">
        <tr>
          <th scope="col">#</th>
          <th scope="col">date</th>
          <th scope="col">note</th>    
          <th scope="col">stav</th>      
        </tr>
      </thead>
      <tbody>
        <tr> 
          <?php echo getEditedRow(); ?>                                         
        </tr> 
      </tbody>
    </table>
    <h3 align="center">Edit note</h3>
    <form class = "form-inline" action= "obsluha_edit.php" method="post">

      <div class="form-group">
        <label class="sr-only" >id</label>
        <input type="text" class="form-control" placeholder=<?php $arr = returnData(); echo $arr[0]; ?> disabled="disabled" >
      </div>

      <div class="form-group">
        <label class="sr-only" >date</label>
        <input type="date" class="form-control" name= "update_date" value =<?php echo VratDate(); ?> >
      </div>

      <div class="form-group">
        <label class="sr-only" >note</label>
        <input type="text" class="form-control" name="update_note" value= "<?php $arr = returnData(); echo $arr[2]; ?>" >
      </div>     

      <div class="form-group">
        <label class="sr-only" >stav</label>
        <input type="text" class="form-control" name="stav" placeholder= <?php $arr = returnData(); echo $arr[3]; ?> disabled="disabled">
      </div>                                              
              
      <div >
        <input type="submit" class="btn btn-info" name="submit" value="save note" > <!--is the button that when clicked submits the form is sended to the server for processing-->
        <a href="index.php" class="btn btn-info" role="button">go on overwiev</a>
      </div> 
    </form>   

  <script src="js/vendor/modernizr-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script>window.jQuery || document.write('<script src="js/vendor/jquery-3.3.1.min.js"><\/script>')</script>
  <script src="js/plugins.js"></script>
  <script src="js/main.js"></script>  
</body>


</html>
<!--
otázky Tom:
1) práce s externíma .php souborama jak volat funkce uložené v externích php souborech
-->