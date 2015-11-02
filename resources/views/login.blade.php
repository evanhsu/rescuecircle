<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>RescueCircle</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/css/navbar-static-top.css" rel="stylesheet">
    

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

  </head>

  <body>
    <?php include("includes/menubar.php"); ?>
    <div id="container-fluid" class="container-fluid" style="background: url('assets/images/map-dim.jpg'); background-size:cover;">
      <div id="loginWindow" style="width:350px;height:350px;border:4px dashed #99dd99;margin:50px auto 0 auto;padding-top:100px;padding-left:0px; background:url('assets/images/crew-login-heli.png') no-repeat white;background-position:right bottom;border-radius:175px;text-align:center;">
        <form class="form-vertical" role="form" action="/login" method="post" style="margin:0 auto 0 auto;width:75%;text-align:left;">
            <div class="form-group" style="margin-bottom:5px;">
                <label class="control-label sr-only" for="email">Email:</label>
                <input type="email" class="form-control" id="email" placeholder="Email" />
            </div>

            <div class="form-group">
                <label class="control-label sr-only" for="password">Password:</label>
                <input type="password" class="form-control" id="password" placeholder="Password" />
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-default">Login</button>
            </div>

      </div> <!-- /loginWindow -->
    </div> <!-- /container-fluid -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
