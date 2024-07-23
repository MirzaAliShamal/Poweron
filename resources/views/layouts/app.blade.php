<!DOCTYPE html>
<html lang="en">
	<head>
        <meta name="baseUrl" content="{{ url('/') }}">
        <meta name="csrfToken" content="{{ csrf_token() }}">
		<title>@yield('title') | Poweron</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta charset="utf-8" />

        <link rel="icon" href="{{ asset('assets/media/logos/power-on-logo-32x32.png') }}" sizes="32x32" />
        <link rel="icon" href="{{ asset('assets/media/logos/power-on-logo-192x192.png') }}" sizes="192x192" />
        <link rel="apple-touch-icon" href="{{ asset('assets/media/logos/power-on-logo-180x180.png') }}" />
        <meta name="msapplication-TileImage" content="{{ asset('assets/media/logos/power-on-logo-270x270.png') }}" />

		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
        <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('assets/css/custom.css?v='.rand()) }}" rel="stylesheet" type="text/css" />
        @yield('css')
	</head>

	<body class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed aside-enabled aside-fixed" style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">
		<div class="d-flex flex-column flex-root">
			<div class="page d-flex flex-row flex-column-fluid">
				@include('components.sidebar')

				<div class="wrapper d-flex flex-column flex-row-fluid">
					@include('components.header')

					<div class="content d-flex flex-column flex-column-fluid">
						<div class="toolbar">
							<div class="container-fluid d-flex flex-stack">
								<div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
									<h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">@yield('page-title')</h1>
								</div>
								@yield('breadcrumb')
							</div>
						</div>

						<div class="post d-flex flex-column-fluid">
							<div class="container">
								@yield('content')
							</div>
						</div>
					</div>

                    <div class="footer py-4 d-flex flex-lg-column">
						<div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-end">
							<div class="text-dark">
								<span class="text-muted fw-bold me-1">Powered By</span>
								<a href="/" target="_blank" class="text-gray-800 text-hover-primary fw-bold">Rubitcube Information Technology, LLC</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
        <form action="{{ route('logout') }}" method="post" id="logout-form">@csrf</form>


		<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
		<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
        <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
        <script src="https://cdn.tiny.cloud/1/s4xlzvcfnmcrzij2w4u2vsufurglsf39yxcjjj49iv312dk8/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
        <script src="{{ asset('assets/js/admin.js?v='.rand()) }}"></script>

        @if (Session::has('success'))
            <script>
                Toast.fire({
                    icon: 'success',
                    title: '{{ session("success") }}'
                })
            </script>
        @endif

        @if (Session::has('error'))
            <script>
                Toast.fire({
                    icon: 'error',
                    title: '{{ session("error") }}'
                })
            </script>
        @endif

        @yield('js')
	</body>
</html>
