<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Driving Theory Test</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">

  @vite('resources/css/app.css')
</head>
<body>

  <header class="shadow">
    <nav class="flex items-center gap-5 max-w-screen-lg px-12 py-4 mx-auto">
      <a href="/" class="mr-auto text-2xl font-bold text-blue-500">Driving Theory Test</a>
      <a href="">Link 1</a>
      <a href="">Link 2</a>
      <a href="">Link 3</a>
    </nav>
  </header>

  <main class="container max-w-screen-lg mx-auto px-4 md:px-12 py-8">
    {{ $slot }}
  </main>

</body>
</html>