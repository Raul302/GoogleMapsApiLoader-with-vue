<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>GoogleMapsApiLoader with vue</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        
    </head>
    <body>
    <center>
    <div id="app"> 
      <gmap-component></gmap-component>
      </div>
    </center>
      <script src="{{mix('js/app.js')}}"></script>
    </body>
</html>
