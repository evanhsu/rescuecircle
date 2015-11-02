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
    <link rel="stylesheet" href="http://js.arcgis.com/3.14/esri/css/esri.css">
    
    <script>
        // Tell Dojo where to find custom modules for the ArcGIS API
        var dojoConfig = { 
            locale: "en",
            packages: [{
                name: "assets",
                //location: location.pathname.replace(/\/[^/]+$/, "") + "/assets/js"
                location: "/assets"
            }]
        };
    </script>
    <script src="http://js.arcgis.com/3.14/"></script>  <?php /* ArcGIS API - must load before other classes */?>
    <script src="assets/js/arcmap.js"></script>         <?php /* Render the map and all layers - waits for the DOM to load so dependencies will always load first */?>
    <!--<script src="assets/js/helicopterClass.js"></script>-->
    

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
    <div id="container-fluid" class="container-fluid">
      <div id="mapDiv">
        <!-- ArcMap gets placed here -->
      </div> <!-- /mapDiv -->
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
