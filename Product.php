<?php
session_start();
include "dbconnect.php";

if (isset($_GET['response'])) {
  print '
  <script type="text/javascript">
    alert("' . $_GET['response'] . '");
  </script>';
}

if(isset($_POST['submit']))
{
  if($_POST['submit']=="login")
  { 
    $username=$_POST['login_username'];
    $password=$_POST['login_password'];
    $query = "SELECT * from users where UserName ='$username' AND Password='$password'";
    $result = mysqli_query($con,$query)or die(mysql_error());
    if(mysqli_num_rows($result) > 0)
    {
      $row = mysqli_fetch_assoc($result);
      $_SESSION['user']=$row['UserName'];
      print'
      <script type="text/javascript">alert("Successfully logged in.");</script>
      ';
    }
    else
    {    
      print'
      <script type="text/javascript">alert("Incorrect username or password!");</script>
      ';
    }
  }
  else if($_POST['submit']=="register")
  {
    $username=$_POST['register_username'];
    $password=$_POST['register_password'];
    $query="select * from users where UserName = '$username'";
    $result=mysqli_query($con,$query) or die(mysql_error);
    if(mysqli_num_rows($result)>0)
    {   
      print'
      <script type="text/javascript">alert("Username is taken!");</script>
      ';
    }
    else
    {
      $query ="INSERT INTO users VALUES ('$username','$password')";
      $result=mysqli_query($con,$query);
      print'
        <script type="text/javascript">
        alert("Successfully registered.");
        </script>
        ';
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Online Book Store">
    <meta name="author" content="Om Shankar">
    <title>Genre</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/my.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Exo+2&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  </head>
  <body>
    <nav class="navbar navbar-default navbar-fixed-top navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="index.php" style="padding: 10px;"><img class="img-responsive" alt="Brand" src="img/logo.jpg"  style="width: 150px;margin: 0px;"></a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav navbar-right">
          <?php
          if(!isset($_SESSION['user']))
          {
            echo'
            <li>
              <button type="button" class="button bttn" data-toggle="modal" data-target="#login"> Login </button>
              <div id="login" class="modal fade" role="dialog">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h1 class="modal-title text-center">Login</h1>
                    </div>
                    <div class="modal-body">
                      <form class="form" role="form" method="post" action="Product.php" accept-charset="UTF-8">
                        <div class="form-group">
                          <label for="username"><b>Username</b></label>
                          <input type="text" name="login_username" class="form-control" placeholder="Enter Username" required>
                        </div>
                        <div class="form-group">
                          <label for="password"><b>Password</b></label>
                          <input type="password" name="login_password" class="form-control"  placeholder="Enter Password" required>
                        </div>
                        <div class="form-group">
                          <button type="submit" name="submit" value="login" class="btn btn-block"> Login </button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </li>
            <li>
              <button type="button" class="button bttn" data-toggle="modal" data-target="#register">Sign Up</button>
              <div id="register" class="modal fade" role="dialog">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h1 class="modal-title text-center"> Create your account </h1>
                    </div>
                    <div class="modal-body">
                      <form class="form" role="form" method="post" action="index.php" accept-charset="UTF-8">
                        <div class="form-group">
                          <label for="username"><b>Username</b></label>
                          <input type="text" name="register_username" class="form-control" placeholder="Username" required>
                        </div>
                        <div class="form-group">
                          <label for="password"><b>Password</b></label>
                          <input type="password" name="register_password" class="form-control"  placeholder="Password" required>
                        </div>
                        <div class="form-group">
                          <button type="submit" name="submit" value="register" class="btn btn-block"> Sign up </button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </li>';
          }
          else
          {
            echo'
            <li> <a href="#" class="button bttn"> Hello ' .$_SESSION['user']. '</a></li>
            <li> <a href="cart.php" class="button bttn"> Cart </a> </li>
            <li> <a href="destroy.php" class="button bttn"> Logout </a> </li>'; 
          }
          ?>
          </ul>
        </div>
      </div>
    </nav>
    <div id="top" >
      <div id="searchbox" class="container-fluid" style="width:112%;margin-left:-6%;margin-right:-6%;">
        <div>
          <form role="search" method="POST" action="Result.php">
            <input type="text" class="form-control" name="keyword" style="width:80%;margin:20px 10% 20px 10%;" placeholder="Search for a book, author or category">
          </form>
        </div>
      </div>
    <?php
    if(isset($_GET['value']))
    {  
      $_SESSION['category']=$_GET['value'];
    }
    $category=$_SESSION['category'];
    if(isset($_POST['sort']))
    {
      if($_POST['sort']=="price")
      {
        $query = "SELECT * FROM products WHERE Category='$category' ORDER BY Price";
        $result = mysqli_query ($con,$query)or die(mysqli_error($con));
        ?>
          <script type="text/javascript">
          document.getElementById('select').Selected=$_POST['sort'];
          </script>
        <?php
      }
      elseif($_POST['sort']=="priceh")
      {
        $query = "SELECT * FROM products WHERE Category='$category' ORDER BY Price DESC";
        $result = mysqli_query ($con,$query)or die(mysqli_error($con));
      }
      elseif($_POST['sort']=="discount")
      {
        $query = "SELECT * FROM products WHERE Category='$category' ORDER BY Discount DESC";
        $result = mysqli_query ($con,$query)or die(mysqli_error($con));
      }
      elseif($_POST['sort']=="discountl")
      {
        $query = "SELECT * FROM products WHERE Category='$category' ORDER BY Discount";
        $result = mysqli_query ($con,$query)or die(mysqli_error($con));
      }
    } 
    else   
    {
      $query = "SELECT * FROM products WHERE Category='$category'";
      $result = mysqli_query ($con,$query)or die(mysqli_error($con));
    } 
    $i=0;
    echo'
    <div class="container-fluid" id="books">
    <div class="row">
      <div class="col-xs-12 text-center" id="heading">
        <h1 style="color: #4285F4; margin-bottom: 0px;"> '. $category .' </h1>
      </div>
    </div>      
    <div class="container fluid">
      <div class="row">
        <div class="col-sm-5 col-sm-offset-6 col-md-5 col-md-offset-7 col-lg-4 col-lg-offset-8">
          <form action="';echo $_SERVER['PHP_SELF'];echo'" method="post" class="pull-right">
            <label for="sort">Sort by</label>
            <select name="sort" id="select" onchange="form.submit()">
              <option value="default" name="default"  selected="selected">Select</option>
              <option value="price" name="price"> Price: Low to High </option>
              <option value="priceh" name="priceh"> Price: High to Low </option>
              <option value="discountl" name="discountl"> Discount: Low To High </option>
              <option value="discount" name="discount"> Discount: High To Low </option>
            </select>
          </form>
        </div>
      </div>
    </div>';
    if(mysqli_num_rows($result) > 0) 
    {   
      while($row = mysqli_fetch_assoc($result)) 
      {
        $path="img/books/" .$row['PID'].".jpg";
        $description="description.php?ID=".$row["PID"];
        if($i%4==0)
        echo '<div class="row">';
        echo'
        <a href="'.$description.'">
          <div class="col-sm-6 col-md-3 col-lg-3 text-center">
            <div class="book-block" style="border :3px solid #DEEAEE;">
              <img class="book block-center img-responsive" src="'.$path.'">
              <span style="color:#000;">' . $row["Title"] . '</span> <br> 
              ₹ ' . $row["Price"] .'  &nbsp
              <span style="text-decoration:line-through;color:#000;"> ₹ ' . $row["MRP"] .' </span>
              <span class="label label-warning" style="color:#B12704;">'. $row["Discount"] .'%</span>
            </div>
          </div>
        </a>';
        $i++;
        if($i%4==0)
        echo '</div>';
      }
    }
    echo '</div>';
    ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.js"></script>
  </body>
</html>