<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="/ico/favicon.ico">

    <title>Accesos - Feng</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/bootstrap-theme.css" rel="stylesheet">
    <link href="/css/dashboard.css" rel="stylesheet">
    @yield('css')

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/"><img src="/img/logo_feng_small.png" style="margin-top:-14px;"/></a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="/">Dashboard</a></li>
                    <li id="menu_report_io"><a href="/reports">Reportes de E/S</a></li>
                    @if (Auth::user() -> user_type == 1)
                    <li><a href="/users">Usuarios</a></li>
                    <li><a href="/family">Familias</a></li>
                    <li><a href="/products/list">Productos</a></li>
                    <li><a href="/journeyevents/report">Inventario por evento</a></li>
                    @endif
                    <li><a href="/report">Reporte General</a></li>
                    <li><a href="/logout">Cerrar Sesión</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3 col-md-2 sidebar">
                <h3 class="sub-header">Principal</h3>
                <ul class="nav nav-sidebar">
                    <li class="active" id="menu_dashboard"><a href="/">Dashboard</a></li>
                    <li id="menu_report_io"><a href="/reports">Reportes de E/S</a></li>
                </ul>
                @if (Auth::user() -> user_type == 1)
                <h3 class="sub-header">Catálogos</h3>
                <ul class="nav nav-sidebar">
                    <li id="menu_family"><a href="/family">Familias</a></li>
                    <li id="menu_products_list"><a href="/products/list">Productos</a></li>
                    <li id="menu_bars_list"><a href="/bars/list">Barras</a></li>
                    <!-- <li id="menu_labels_list"><a href="/tags">Etiquetas</a></li>-->
                    <li id="menu_journeyevents_list"><a href="/journeyevents">Eventos</a></li>
                </ul>
                @endif
                <h3 class="sub-header">Reportes</h3>
                <ul class="nav nav-sidebar">
                    @if (Auth::user() -> user_type == 1)
                    <li id="menu_report_eventinventory"><a href="/journeyevents/report">Inventario en Evento</a></li>
                    @endif
                    <li id="menu_report"><a href="/report">Reporte General</a></li>
                </ul>
                @if (Auth::user() -> user_type == 1)
                <h3 class="sub-header">Usuarios</h3>
                <ul class="nav nav-sidebar">
                    <li id="menu_users_list"><a href="/users">Listado de Usuarios</a></li>
                </ul>
                @endif
                <h3 class="sub-header">Usuario</h3>
                <ul class="nav nav-sidebar">
                    <!--<li><a href="/user">Perfil</a></li>-->
                    <!--<li><a href="/config">Configuración</a></li>-->
                    <li id="menu_logout"><a href="/logout">Cerrar Sesión</a></li>
                </ul>
            </div>
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                <div class="box">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <div class="row" style="margin: 0px;">
        <div class="footer">
            <div style="color: #01171c;">2014 © Grupo HQH - Smartware Soluciones</div>
        </div>
    </div>

    <div class="modal fade" id="smwModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="modalTitle">Modal Title</h4>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/docs.min.js"></script>
    <script src="/js/functions.js"></script>
    @yield('javascripts')

    @yield('scripts')
</body>
</html>
