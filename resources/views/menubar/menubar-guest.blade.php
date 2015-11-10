<?php
  function is_active($button) {
    // Decide whether to style the requested menu link with the "active" class
    // The $active_menubutton variable is set in the MenubarComposer
    return (strtolower($button) == strtolower($active_menubutton)) ? " class=\"active\"" : "";
  }
?>

<nav class="navbar navbar-default navbar-static-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Rescue Circle</a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li <?php is_active('map'); ?>><a href="/">Map</a></li>
        <li <?php is_active('status'); ?>><a href="crews/1/status">Status</a></li>
        <li <?php is_active('identity'); ?>><a href="crews/1">Identity</a></li>
        <li <?php is_active('accounts'); ?>><a href="crews/1/accounts">Accounts</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li <?php is_active('login'); ?>><a href="/login">Login</a></li>
      </ul>
    </div><!--/.nav-collapse -->
  </div>
</nav>