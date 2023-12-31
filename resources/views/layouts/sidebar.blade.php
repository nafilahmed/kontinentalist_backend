<div class="main-menu menu-fixed menu-light menu-accordion    menu-shadow " data-scroll-to-active="true" data-img="theme-assets/images/backgrounds/02.jpg">
  <div class="navbar-header">
    <ul class="nav navbar-nav flex-row">       
      <li class="nav-item mr-auto"><a class="navbar-brand" href="index.html"><img class="brand-logo" alt="Chameleon admin logo" src="theme-assets/images/logo/logo.png"/>
      <h3 class="brand-text">Chameleon</h3></a></li>
      <li class="nav-item d-md-none"><a class="nav-link close-navbar"><i class="ft-x"></i></a></li>
    </ul>
  </div>
  <div class="main-menu-content">
    <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation"> 
      <li class="@if (Request::path() == 'users') active @endif nav-item"><a href="/users"><i class="ft-pie-chart"></i><span class="menu-title" data-i18n="">Users</span></a>
      </li>
      <li class="@if (Request::path() == 'websites') active @endif nav-item"><a href="/websites"><i class="ft-droplet"></i><span class="menu-title" data-i18n="">WebSites</span></a>
    </ul>
  </div>
</div>