<!DOCTYPE html>
<html lang="en">
	<head>
		<title>@yield('title') | Poweron</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta charset="utf-8" />

        <link rel="icon" href="{{ asset('assets/media/logos/power-on-logo-32x32.png') }}" sizes="32x32" />
        <link rel="icon" href="{{ asset('assets/media/logos/power-on-logo-192x192.png') }}" sizes="192x192" />
        <link rel="apple-touch-icon" href="{{ asset('assets/media/logos/power-on-logo-180x180.png') }}" />
        <meta name="msapplication-TileImage" content="{{ asset('assets/media/logos/power-on-logo-270x270.png') }}" />

		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
        @yield('css')
	</head>
	<body class="bg-body">
		<div class="d-flex flex-column flex-root">
			<div class="d-flex flex-column flex-lg-row flex-column-fluid">
				<div class="d-flex flex-column flex-lg-row-auto w-xl-600px positon-xl-relative" style="background-color: #FFF">
					<div class="d-flex flex-column position-xl-fixed top-0 bottom-0 w-xl-600px scroll-y">
						<div class="d-flex flex-row-fluid flex-column text-center p-10 pt-lg-20">
							<a href="" class="py-9">
								<img alt="Logo" src="{{ asset('assets/media/logos/power-on-logo.png') }}" class="h-70px" />
							</a>
							<h1 class="fw-bolder fs-2qx pb-5 pb-md-10" style="color: #be7bc3;">Welcome to Email Marketing Tool</h1>
						</div>
						<div class="d-flex flex-row-auto bgi-no-repeat bgi-position-x-center bgi-size-contain bgi-position-y-bottom min-h-100px min-h-lg-300px" style="background-image: url({{ asset('assets/media/illustrations/checkout.png') }})"></div>
					</div>
				</div>
				<div class="d-flex flex-column flex-lg-row-fluid py-10">
					<div class="d-flex flex-center flex-column flex-column-fluid">
						<div class="w-lg-500px p-10 p-lg-15 mx-auto">
							@yield('content')
						</div>
					</div>
				</div>
			</div>
		</div>

		<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
		<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
        @yield('js')
	</body>
</html>
