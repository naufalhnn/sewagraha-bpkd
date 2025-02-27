<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

		<head>
				<meta charset="utf-8">
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<meta name="csrf-token" content="{{ csrf_token() }}">

				<title>{{ $title ?? config('app.name', 'Laravel') }}</title>

				<!-- Fonts -->
				<link rel="preconnect" href="https://fonts.googleapis.com">
				<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
				<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

				<!-- Styles (tailwind untuk Laravel 11) -->
				@vite(['resources/css/app.css', 'resources/js/app.js'])
		</head>

		<body class="font-sans antialiased">
				<div class="flex min-h-screen items-center justify-center bg-gray-100">

						<!-- Page Content -->
						<main class="w-full">
								{{ $slot }}
						</main>
				</div>
		</body>

</html>
