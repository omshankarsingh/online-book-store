<?php
	session_start();
  if(!isset($_SESSION['user']))
    header("location: index.php?Message=Login to continue!");
	include "dbconnect.php";
    $customer=$_SESSION['user'];
?>

<?php
if(isset($_GET['place']))
{  
  $query="DELETE FROM cart where Customer='$customer'";
  $result=mysqli_query($con,$query);
  ?>
  <script type="text/javascript">
    alert("Order successfully placed! Kindly keep the cash ready it will be collected on delivery.");
  </script>
  <?php                  
}
if(isset($_GET['remove']))
{
  $product=$_GET['remove'];
  $query="DELETE FROM cart where Customer='$customer' AND Product='$product'";
  $result=mysqli_query($con,$query);
  ?>
  <script type="text/javascript">
    alert("Item successfully removed.");
  </script>
  <?php                  
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
    <title>Shopping Cart</title>
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
                      <form class="form" role="form" method="post" action="cart.php" accept-charset="UTF-8">
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
            <li> <a href="destroy.php" class="button bttn"> Logout </a> </li>'; 
          }
          ?>
          </ul>
        </div>
      </div>
    </nav>
	  <?php
    echo '<div class="container-fluid" id="cart">
    <div class="row">
      <div class="col-xs-12 text-center" id="heading">
        <h2 style="color: #000; padding-top: 70px;"> Your Shopping Cart </h2> 
        <br><br>
      </div>
    </div>';
	  if(isset($_SESSION['user']))
	  {   
      if(isset($_GET['ID']))
	    {   
        $product=$_GET['ID'];
        $quantity=$_GET['quantity'];
        $query="SELECT * from cart where Customer='$customer' AND Product='$product'";
        $result=mysqli_query($con,$query);
        $row = mysqli_fetch_assoc($result);
        if(mysqli_num_rows($result)==0)
	      { 
          $query="INSERT INTO cart values('$customer','$product','$quantity')"; 
          $result=mysqli_query($con,$query);
        }
        else
        { 
          $new=$_GET['quantity'];
          $query="UPDATE `cart` SET Quantity=$new WHERE Customer='$customer' AND Product='$product'";
	        $result=mysqli_query($con,$query);
        }
      }
      $query="SELECT PID,Title,Author,Edition,Quantity,Price FROM cart INNER JOIN products ON cart.Product=products.PID WHERE Customer='$customer'";
	    $result=mysqli_query($con,$query); 
      $total=0;
      if(mysqli_num_rows($result)>0)
      {
        $i=1;
        $j=0;
        while($row = mysqli_fetch_assoc($result))
        { 
          $path = "img/books/".$row['PID'].".jpg";
          $Stotal = $row['Quantity'] * $row['Price'];
          if($i % 2 == 1)  $offset= 1;
          if($i % 2 == 0)  $offset= 2;                                                
          if($j%2==0)
          echo '<div class="row">'; 
          echo '
          <div class="panel col-xs-12 col-sm-4 col-sm-offset-'.$offset.' col-md-4 col-md-offset-'.$offset.' col-lg-4 col-lg-offset-'.$offset.' text-center" style="color: #111111; font-size: 15px;">
            <div class="panel-heading" style="color: #4285F4!important; font-size: 20px"> '.$row['Title'].' </div> by '.$row['Author'].'
            <div class="panel-body">
              <img class="image-responsive block-center" src="'.$path.'" style="height :125px;"> <br>           	      
              Edition:&nbsp '.$row['Edition'].' <br>
              Quantity:&nbsp '.$row['Quantity'].' <br>
              Unit Price:&nbsp ₹'.$row['Price'].' <br>
              Subtotal:&nbsp ₹'.$Stotal.' <br><br>
              <a href="cart.php?remove='.$row['PID'].'" class="button bttn" style="font-size: 15px;"> Remove </a>
            </div>
          </div>';
          if($j % 2==1)
          echo '</div>';                                 
          $total=$total + $Stotal; 
          $i++;
          $j++;                                                 
        }
        echo '
        <div class="container">
          <div class="row">  
            <div class="panel col-xs-8 col-xs-offset-2 col-sm-4 col-sm-offset-4 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4 text-center" style="color: #111111; font-size: 20px">
              <div class="panel-heading" style="color: #4285F4!important; font-size: 25px"> Grand total </div>
              <div class="panel-body">₹'.$total.'</div>
            </div>
          </div>
        </div>
        ';
        echo '
        <div class="row">
          <div class="col-xs-8 col-xs-offset-2  col-sm-4 col-sm-offset-2 col-md-4 col-md-offset-3 col-lg-4 col-lg-offset-3">
            <a href="index.php" class="button bttn" style="font-size: 15px;"> Continue Shopping </a>
          </div>
          <div class="col-xs-6 col-xs-offset-3 col-sm-4 col-sm-offset-2 col-md-4 col-md-offset-1 col-lg-4" style="margin-left: 45px;">
            <a href="cart.php?place=true" class="button bttn" style="font-size: 15px;"> Proceed to Buy </a>
          </div>
        </div>
        ';
      } 
      else
      {  
        echo ' 
        <div class="row">
          <div>
            <br><br><br><br><br><br><br><br>
            <h3 align="center" style="color: #000; font-weight:1000; ">Shopping Cart is empty. </h3>
          </div>
        </div>
        <p align="center"> <i>Shopping Cart lives to serve. Give it purpose, fill it with books. <i class="material-icons"> shopping_cart </i> </i> </p>
        <div class="row">
          <div class="col-xs-9 col-xs-offset-3 col-sm-2 col-sm-offset-5 col-md-2 col-md-offset-5">
            <br><br>
            <a href="index.php" class="button bttn"> Continue shopping </a>
          </div>
        </div>';
      }               
	  }
    echo '</div>';
    ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.js"></script>
  </body>
<html>