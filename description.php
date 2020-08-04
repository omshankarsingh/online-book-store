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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Online Book Store">
    <meta name="author" content="Om Shankar">
    <title> Book Description </title>
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
                      <form class="form" role="form" method="post" action="Author.php" accept-charset="UTF-8">
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
                      <form class="form" role="form" method="post" action="description.php" accept-charset="UTF-8">
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
    $PID=$_GET['ID'];
    $query = "SELECT * FROM products WHERE PID='$PID'";
    $result = mysqli_query ($con,$query)or die(mysql_error());
    if(mysqli_num_rows($result) > 0) 
    {   
      while($row = mysqli_fetch_assoc($result)) 
      {
        $path="img/books/".$row['PID'].".jpg";
        $target="cart.php?ID=".$PID."&";
        echo '
        <div class="container-fluid" id="books">
          <div class="row">
            <div class="col-sm-10 col-md-6">
              <div class="tag">'.$row["Discount"].'% off</div>
                <div class="tag-side"><img src="img/tag.png">
                </div>
                <img class="center-block img-responsive" src="'.$path.'" height="550px" style="padding:20px;">
              </div>
              <div class="col-sm-10 col-md-4 col-md-offset-1">
              <h3>'. $row["Title"] . '</h3> <br> by
              <span style="color: #4285F4; font-size: 17px;">
              '.$row["Author"].'
              </span>
              <hr>            
              <span style="font-weight:bold;"> Quantity: </span>';
              echo'<select id="quantity">';
              for($i=1;$i<=$row['Available'];$i++)
              echo '<option value="'.$i.'">'.$i.'</option>';
              echo ' </select>';
              echo'
              <br><br><br>
              <a id="buyLink" href="'.$target.'" class="btn btn-lg btn-danger" style="padding:15px;color:white;text-decoration:none;"> 
                Add to Cart for <br> <h2> ₹ '.$row["Price"] .'</h2><br>
                <span style="text-decoration:line-through;"> ₹ '.$row["MRP"].'</span> 
                <br> '.$row["Discount"].'% discount <br> Inclusive of all taxes
              </a>
            </div>
          </div>
        </div>
        ';
        echo '
        <div class="container-fluid" id="description">
          <div class="row">
            <h4 style="font-weight: 1000;"> Product description </h4> 
            <p>'.$row['Description'] .'</p>
            <hr><h4 style="font-weight: 1000;"> Product details </h4>
            <span style="font-weight: 1000;">Book Code:&nbsp&nbsp</span>'.$row["PID"].' <br>
            <span style="font-weight: 1000;">Publisher:&nbsp&nbsp</span> '.$row["Publisher"].' <br> 
            <span style="font-weight: 1000;">Edition:&nbsp&nbsp</span> '.$row["Edition"].' <br>
            <span style="font-weight: 1000;">Language:&nbsp&nbsp</span> '.$row["Language"].' <br>
            <span style="font-weight: 1000;">Pages:&nbsp&nbsp</span> '.$row["page"].' <br>
            <span style="font-weight: 1000;">Books in Stock:&nbsp&nbsp</span> '.$row["Available"].' <br> 
          </div>
        </div>
        ';
      }
    }
    echo '</div>';
    ?>

    <div class="container-fluid" id="service">
      <div class="row">
        <div class="col-sm-6 col-md-3 text-center">
          <i class="material-icons">favorite</i> <br>
          <span style="font-weight: 1000;"> 24×7 Care </span> <br>
        </div>
        <div class="col-sm-6 col-md-3 text-center">
          <i class="material-icons">verified_user</i><br>
          <span style="font-weight: 1000;"> Trust </span> <br>
        </div>
        <div class="col-sm-6 col-md-3 text-center">
		  <i class="material-icons">stars </i> <br>
          <span style="font-weight: 1000;"> 100% Assurance </span> <br>
        </div>
        <div class="col-sm-6 col-md-3 text-center">
           <i class="material-icons">check_circle</i> <br>
           <span style="font-weight: 1000;">Quality Checked </span> <br>
        </div>
      </div>
    </div>
 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script>
      $(function () {
        var link = $('#buyLink').attr('href');
        $('#buyLink').attr('href', link + 'quantity=' + $('#quantity option:selected').val());
        $('#quantity').on('change', function () {
          $('#buyLink').attr('href', link + 'quantity=' + $('#quantity option:selected').val());
        });
      });
    </script>
  </body>
</html>