<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ config('app.name') }} - Dashboard</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
  @stack('styles')
</head>
<body class="bg-gradient-to-br from-green-50 to-blue-50 min-h-screen">

  @include('components.header')

  <div class="flex flex-col md:flex-row min-h-screen relative">
    @include('components.sidebar')

    <div class="flex-1 p-6 mt-16 md:mt-0">
      @yield('content')
    </div>
  </div>

  @stack('scripts')
</body>
</html>
