<?php
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

$clikedID = $_GET['id'];//proč nele z $_GET['id'] nacitat opakovane?

if(isset($_POST["submit"])) {
  if(empty($_POST["update_note"])){
      $error .='<p><label class="text-danger">please enter date</label></p>';  
  }else{
      $EditedNote=clean_text($_POST["update_note"]);  
      try{
        $conn= new PDO("mysql:host=$servername;dbname=todolist", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql_question= "UPDATE todotable SET content= '$EditedNote' where id=$clikedID";//proč nele z id = $_GET['id'] nacitat opakovane?

        $conn->exec($sql_question);        
        $conn = null;
      }
      catch(PDOException $e){
          echo "Connection failed: " . $e->getMessage();
      }    
  }  
}
else
{
  /*$table_str="";
  $table_str .='<table class="table">';
    $table_str .='<thead class="thead-dark">';
      $table_str .='<tr>';
        $table_str .='<th scope="col">#</th>';
        $table_str .='<th scope="col">date</th>';
        $table_str .='<th scope="col">note</th>' ;   
        $table_str .='<th scope="col">stav</th>' ;     
      $table_str .='</tr>';
    $table_str .='</thead>';
  $table_str .='<tbody>';
              
  $table_str .='</tbody>';
$table_str .='</table>';
echo $table_str;*/
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
        $data = $stmt->fetchAll(PDO::FETCH_NUM);
        return $data;
        echo "Connected successfully"; 
        $conn = null; 
    }catch(PDOException $e){
        echo "Connection failed: " . $e->getMessage();
        return null;
    }
}
print_r(returnData());
function getEditedRow(){
  ;
}

//doplnit odstranovani poznamek
//upravovani dataumu
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
      <!--??? otázka na Marka, jak zakomponovat externí php soubor<form action="obsluha.php" method="post">-->
      <form method="post">
      <h3 align="center">Edit note</h3>
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
        <td  contenteditable="true">Aurelia Vega</td>
        <td  contenteditable="true">30</td>
        </tr> 
      </tbody>
    </table>
    

      <div class = "form-group">
        <label>enter new note</label>        
        <input type="text" class="form-control" name="update_note" placeholder="enter new not for this date"  >
      </div>          
      <div  align="center">
        <input type="submit" class="btn btn-info" name="submit" value="save note" > <!--is the button that when clicked submits the form to the server for processing-->
        <a href="index.php" class="btn btn-info" role="button">go back</a>
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
2)proměnné definované v těle php souboru, vypadají jako globální, ale uvnitř funkcí nejsou viditelné, Jak je zviditelnit?
  -->