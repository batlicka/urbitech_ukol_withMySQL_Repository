


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
      <!--??? otázka na Marka, jak zakomponovat externí php soubor<form action="obsluha.php" method="post">-->
      <form action="obsluha.php"  method="post">
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
        <input type="submit" class="btn btn-info" name="submit" value="save note"> <!--is the button that when clicked submits the form to the server for processing-->
      </div>      
    </form>

    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th scope="col">#</th>
          <th scope="col">date</th>
          <th scope="col">note</th>    
          <th scope="col">StaflikStafklik</th>      <!--vyřešeno-->  
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
