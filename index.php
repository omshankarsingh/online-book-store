<?php
session_start();
include "dbconnect.php";
if (isset($_GET['Message'])) {
  print '
  <script type="text/javascript">
    alert("' . $_GET['Message'] . '");
  </script>';
}


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
    <title>Book Store</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/my.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Exo+2&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  </head>
  <body>
    <nav class="navbar navbar-default navbar-fixed-top navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="#" style="padding: 10px;"><img class="img-responsive" alt="Brand" src="img/logo.jpg"  style="width: 150px;margin: 0px;"></a>
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
                      <form class="form" role="form" method="post" action="index.php" accept-charset="UTF-8">
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
      <div class="container-fluid" id="header">
        <div class="row">
          <div class="col-lg-2" id="category">
            <div style="background: #ffffff; color: #000!important; font-weight: 1000; border: 0px; padding-bottom: 10px; font-size: 17px;"> Shop by genre </div>
              <ul style="font-size: 15px;">
                <li> <a href="Product.php?value=Adventure"> Adventure </a> </li>
                <li> <a href="Product.php?value=Biographies"> Biographies </a> </li>
                <li> <a href="Product.php?value=Children%20Bookshelf"> Children </a> </li>
                <li> <a href="Product.php?value=Comics"> Comics </a> </li>
                <li> <a href="Product.php?value=Computing"> Computing </a> </li>
                <li> <a href="Product.php?value=Oswaal%20NCERT%20Exemplar"> Exam Preparation </a> </li>
                <li> <a href="Product.php?value=Fantasy"> Fantasy </a> </li>
                <li> <a href="Product.php?value=Fiction"> Fiction </a> </li>
                <li> <a href="Product.php?value=Humour"> Humour </a> </li>
                <li> <a href="Product.php?value=Reference"> Reference </a> </li>
                <li> <a href="Product.php?value=Romance"> Romance </a> </li>
                <li> <a href="Product.php?value=Self-Help"> Self-Help </a> </li>
              </ul>
            </div>
            <div class="col-lg-7">
              <div id="myCarousel" class="carousel slide carousel-fade" data-ride="carousel">
                <ol class="carousel-indicators">
                  <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                  <li data-target="#myCarousel" data-slide-to="1"></li>
                  <li data-target="#myCarousel" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner" role="listbox">
                  <div class="item active">
                    <img class="img-responsive" src="img/carousel/1.png">
                  </div>
                  <div class="item">
                    <img class="img-responsive "src="img/carousel/2.png">
                  </div>
                  <div class="item">
                    <img class="img-responsive "src="img/carousel/3.png">
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3" id="offer">
              <a href="Product.php?value=The%20New%20York%20Times%20Best%20Sellers">
                <img class="img-responsive center-block" src="img/offers/1.png">
              </a>
              <a href="Product.php?value=Oswaal%20NCERT%20Exemplar">
                <img class="img-responsive center-block" src="img/offers/2.png">
              </a>
              <a href="Product.php?value=Children%20Bookshelf">
                <img class="img-responsive center-block" src="img/offers/3.png"></a>
            </div>
          </div>
        </div>
      </div>
      <br><img style="width: 100%" src="img/head.jpg">
      <div class="container-fluid text-center" id="new">
        <div class="row">
       	  <div class="col-sm-6 col-md-3 col-lg-3">
            <a href="description.php?ID=F-21&category=new">
              <div class="book-block">
                <div class="tag">New</div>
                <div class="tag-side"><img src="img/tag.png"></div>
                <img class="block-center img-responsive" src="img/books/F-21.jpg">
                <span style="color:#000000;">The Institute</span> <br>
                ₹ 999 &nbsp
                <span style="text-decoration:line-through;color:#000000;">₹ 1999</span>
                <span class="label label-warning" style="color:#B12704;">50%</span>
              </div>
            </a>
          </div>
          <div class="col-sm-6 col-md-3 col-lg-3">
            <a href="description.php?ID=NYTBS-3&category=new">
              <div class="book-block">
                <div class="tag">New</div>
                <div class="tag-side"><img src="img/tag.png"></div>
                <img class="book block-center img-responsive" src="img/books/NYTBS-3.jpg">
                <span style="color:#000000;">A Warning</span> <br>
                ₹ 488  &nbsp
                <span style="text-decoration:line-through;color:#000000;">₹ 599</span>
                <span class="label label-warning" style="color:#B12704;">19%</span>
              </div>
            </a>
          </div>
          <div class="col-sm-6 col-md-3 col-lg-3">
            <a href="description.php?ID=R-5&category=new">
              <div class="book-block">
                <div class="tag">New</div>
                <div class="tag-side"><img src="img/tag.png"></div>
                <img class="block-center img-responsive" src="img/books/R-5.jpg">
                <span style="color:#000000;">The Malayala Manorama English Yearbook 2020</span> <br>
                ₹ 296 &nbsp
                <span style="text-decoration:line-through;color:#000000;">₹ 300</span>
                <span class="label label-warning" style="color:#B12704;">1%</span>
              </div>
            </a>
          </div>
          <div class="col-sm-6 col-md-3 col-lg-3">
            <a href="description.php?ID=SH-5&category=new">
              <div class="book-block">
                <div class="tag">New</div>
                <div class="tag-side"><img src="img/tag.png"></div>
                <img class="block-center img-responsive" src="img/books/SH-5.jpg">
                <span style="color:#000000;">Everything Is F*cked : A Book About Hope</span> <br>
                ₹ 305 &nbsp
                <span style="text-decoration:line-through;color:#000000;">₹ 499</span>
                <span class="label label-warning" style="color:#B12704;">39%</span>
              </div>
            </a>
          </div>
        </div>
      </div>
      <div class="container-fluid" id="author">
        <h3> Featured Authors </h3>
        <div class="row">
          <div class="col-sm-2 col-md-2 col-lg-2">
            <a href="Author.php?value=Chetan%20Bhagat"><img class="img-responsive center-block" src="img/popular-author/1.jpg"></a>
          </div>
          <div class="col-sm-2 col-md-2 col-lg-2">
            <a href="Author.php?value=Agatha%20Christie"><img class="img-responsive center-block" src="img/popular-author/2.jpg"></a>
          </div>
          <div class="col-sm-2 col-md-2 col-lg-2">
            <a href="Author.php?value=Paulo%20Coelho"><img class="img-responsive center-block" src="img/popular-author/3.jpg"></a>
          </div>
          <div class="col-sm-2 col-md-2 col-lg-2">
            <a href="Author.php?value=Jeffrey%20Archer"><img class="img-responsive center-block" src="img/popular-author/4.jpg"></a>
          </div>
          <div class="col-sm-2 col-md-2 col-lg-2">
            <a href="Author.php?value=Robin%20Sharma"><img class="img-responsive center-block" src="img/popular-author/5.jpg"><a>
          </div>
          <div class="col-sm-2 col-md-2 col-lg-2">
            <a href="Author.php?value=Dan%20Brown"><img class="img-responsive center-block" src="img/popular-author/6.jpg"></a>
          </div>
        </div>
      </div>
      <footer style="margin-left:-6%;margin-right:-6%;">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-12">
              <div class="row text-center">
                <h3> Contact </h3>
                <p> Any Problem? Give us a call or send us an email and we will get back to you as soon as possible! </p>
              </div>
              <div class="row">
                <div class="col-md-6 text-center">
                  <i class="material-icons"> call </i>
                  <p> 123 456 789 </p>
                </div>
                <div class="col-md-6 text-center">
                  <i class="material-icons">email</i>
                  <p> mail@bookstore.com </p>
                </div>
              </div>
            </div>
            <div class="col-sm-12 text-center">
              <div class="navFooterLine navFooterLinkLine navFooterPadItemLine navFooterCopyright">
   				     <span> © bookstore.com 2019, All rights reserved. </span>
			        </div>
            </div>
          </div>
        </div>
      </footer>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.js"></script>
  </body>
</html>