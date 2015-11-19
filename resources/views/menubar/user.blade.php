<?php
  $a = strtolower($active_menubutton);

  function is_active($button,$active_menubutton) {
    // Decide whether to style the requested menu link with the "active" class
    // The $active_menubutton variable is set in the MenubarComposer
    echo ($button == $active_menubutton) ? " class=\"active\"" : "";
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
        <li<?php is_active('map',$a); ?>><a href="/">Map</a></li>
        <li<?php is_active('status',$a); ?>><a href="/crews/{{ $user_crew_id }}/status">Status</a></li>
        <li<?php is_active('identity',$a); ?>><a href="/crews/{{ $user_crew_id }}/identity">Identity</a></li>
        <li<?php is_active('accounts',$a); ?>><a href="/crews/{{ $user_crew_id }}/accounts">Accounts</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="active"><a href="{{ route('edit_user', Auth::user()->id) }}">{{ Auth::user()->email }}</a></li>
        <li><a href="/logout">Logout</a></li>
      </ul>
    </div><!--/.nav-collapse -->
  </div>
</nav>