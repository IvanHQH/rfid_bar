<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="ico/favicon.ico">

    <title>Inicio de Sesi&oacute;n</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/signin.css" rel="stylesheet">
    <style>
        body {
            display: block;
            padding: 20px;
            overflow-x: hidden;
            overflow-y: auto;
            background-color: #0d0d0d;
            border-right: 1px solid #eee;
            background: radial-gradient(black 15%, transparent 16%) 0 0,
            radial-gradient(black 15%, transparent 16%) 8px 8px,
            radial-gradient(rgba(255,255,255,.1) 15%, transparent 20%) 0 1px,
            radial-gradient(rgba(255,255,255,.1) 15%, transparent 20%) 8px 9px;
            background-color: #0d0d0d;
            background-size: 16px 16px;
        }
        .container {
            background-color: #363636;
            border: 1px solid #333;
            border-radius: 10px;
            display: block;
            width: 400px;
        }

        .logo {
            margin-left: auto;
            margin-right: auto;
            display: block;
            width: 150px;
            margin-bottom: 20px;
        }
    </style>


    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <div class="logo"><img src="/img/logo_feng.png"></div>
    <div class="container">
        <form class="form-signin" role="form" method="post" action="/login">
            <h2 class="form-signin-heading">Inicio de Sesión</h2>
            <input type="username" id="username" name="username" class="form-control" placeholder="Nombre de Usuario" required autofocus>
            <input type="password" id="password" name="password" class="form-control" placeholder="Contraseña" required>
            <!--<label class="checkbox">
                <input type="checkbox" value="remember-me"> Remember me
            </label>-->
            <button class="btn btn-lg btn-primary btn-block" type="submit">Iniciar Sesión</button>
        </form>

    </div>
</body>
</html>
