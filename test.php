<?php
 if($_GET['button1']){fun1();}
 if($_GET['button2']){fun2();}

 function fun1()
 {
   echo "pressed fun1";
 }
 function fun2()
 {
   echo "pressed fun2";
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
        <button id="btnfun1" name="btnfun1" onClick='location.href="?button1=1"'>Update to 1</button>
        <button id="btnfun2" name="btnfun2" onClick='location.href="?button2=1"'>Update to 2</button>
        
</body>