<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Admin</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

</head>
<body>
    <header>
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h1><a href="/admin">Admin-Panel</a></h1>
                </div>
            </div>
        </div>
    </header>

    <section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2">
                <div class="list-group">
                    <a href="/admin" class="list-group-item list-group-item-action active">Home</a>
                    <a href="/users" class="list-group-item list-group-item-action">Users</a>
                </div>
            </div>
            <div class="col-sm-9 offset-sm-1">
                @yield('content')
            </div>
        </div>
    </div>
    </section>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
