<!DOCTYPE html>
<html lang="hu">
    <head>
        <meta charset="utf-8">
        <title>@yield('title') Fertőrákosi Lövészklub</title>
        <link rel="shortcut icon" type="image/png" href="{{ secure_asset('images/logo.png') }}">

        <!-- Mobile -->
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Style -->
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="/css/style_base.css">

        <!-- Scripts -->
        <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
        <script src="/js/main.js"></script>

        @yield('scripts')

        <!-- FB OpenGraph -->
        <meta property="og:image" content="{{secure_asset('images/logo.png')}}" />
        <meta property="og:locale" content="hu_HU" />
        <meta property="og:site_name" content="Fertőrákosi-Lövészklub.hu" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="<?php echo Request::url(); ?>" />
        <meta property="og:description" content="A Fertőrákosi Lövészklub hivatalos oldala" />


    @section('fb-og')
        <meta property="og:title" content="Fertőrákosi Lövészklub" />
    @show

    </head>

    <body>
        <!-- Load Facebook SDK asynchronously -->
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/hu_HU/sdk.js#xfbml=1&appId=228336310678604&version=v2.0";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>

        <!-- Popup windows -->
        <!-- Name change -->
        @if(Auth::check())
        <div class="modal fade" id="NameChange" tabindex="-1" role="dialog" aria-labelledby="NameChange" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Megjelenített név megváltoztatása</h4>
                    </div>
                    <div class="modal-body">
                        <form action="felhasznalo/uj-nev" method="post" id="NameChangeForm">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <label for="NewName">Név: </label>
                                <input type="text" class="form-control" name="NewName" id="NewName" value="{{ Session::get('user_full_name') }}" required>
                            </div>
                        </form>
                        <p id="NameChangeError">Ezzel csak az oldalon megjelenített nevedet változtatod meg, a Facebookon használt neved természetesen változatlan marad.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="SaveNewName">Mentés</button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Content -->
        <div class="container">
            <div class="row">
                <div class="hidden-xs hidden-sm col-md-4">
                    <a href="/" ><img src="/images/logo.png" alt="FLK" id="logo" /></a>
                </div>
                <div class="col-xs-12 col-md-8 text-center">
                    <h1 id="title">Fertőrákosi Lövészklub</h1>
                    <p id="subtitle">9421 Fertőrákos, Felsőszikla sor</p>
                </div>
            </div>

            <nav class="navbar navbar-default tmargin">
                <div class="container-fluid">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#mainmenu">
                        <span class="sr-only">Menü</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>

                <div class="collapse navbar-collapse" id="mainmenu">
                    <ul class="nav navbar-nav">
                        <li><a href="/hirek/">Hírek</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false">Rólunk <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="/rolunk/tortenet">A Klub története</a></li>
                                <li><a href="/rolunk/edzesek">Edzések</a></li>
                                <li><a href="/rolunk/arak">Árak</a></li>
                                <li><a href="/rolunk/elerhetosegek">Elérhetőségek</a></li>
                            </ul>
                        </li>
                        <li><a href="/galeria/">Galéria</a></li>
                        <li><a href="/rekordok/">Egyéni rekordok</a></li>
                        <li class="navbar-right">
                            @if(Auth::check())
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                   aria-expanded="false"><i class="fa fa-facebook"></i> <span id="UserFullName">{{ Session::get('user_full_name') }}</span> <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    @yield('adminmenu')
                                    @if(Session::has('member'))
                                        <li><a href="/rekordok/sajat"><i class="fa fa-trophy fa-fw"></i> Saját eredményeim</a></li>
                                        <li><a href="/rekordok/uj"><i class="fa fa-plus fa-fw"></i> Új eredmény feltöltése</a></li>
                                        <li class="nav-divider"></li>
                                    @endif
                                    @yield('membermenu')
                                    <li><a href="#" data-toggle="modal" data-target="#NameChange">Név megváltoztatása</a></li>
                                    <li><a href="/felhasznalo/kilepes">Kijelentkezés</a></li>
                                </ul>
                            @else
                                <a href="/felhasznalo/facebook"><i class="fa fa-facebook"></i> Facebook bejelentkezés</a>
                            @endif
                        </li>
                    </ul>
                </div>
            </nav>
            @if(Session::has('message'))
                <div class="alert alert-{{Session::get('message')['type']}} alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {!! Session::get('message')['message'] !!}
                </div>
            @endif

            @section('content')
            @show

            <hr/>

            <div id="footer">
                <div class="row">
                    <div class="hidden-xs hidden-sm col-md-6">
                        <a href="http://www.fertorakos.hu" target="_blank"><img src="/images/fertorakos.png" alt="Fertőrákos"/></a>
                        <a href="http://www.mttosz.hu/" target="_blank"><img src="/images/mttosz.png" alt="MTTOSZ"/></a>
                    </div>
                    <div class="col-xs-12 col-md-6 text-right">
                        <div id="fblike" class="fb-like" data-href="https://www.fertorakosi-loveszklub.hu" data-width="100" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
                        <p><strong>Fertőrákosi Lövészklub</strong><br/>
                            <i class="fa fa-home fa-fw"></i> 9421 Fertőrákos, Felsőszikla sor
                        </p>
                        <p>&copy; 2015 <a href="http://nxu.hu/" target="_blank">nXu</a> - <i class="fa fa-github fa-fw"></i>
                        <a href="https://github.com/fertorakosi-loveszklub/flk-honlap2/" target="_blank">
                            Src
                        </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
