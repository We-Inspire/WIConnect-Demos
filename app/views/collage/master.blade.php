<!DOCTYPE html>
<html>
	<head>
		<title>@yield('page_title')</title>
		<meta name="viewport" content="width=device-width, user-scalable=no">
		<script src="{{ URL::to('js/jquery.min.js') }}"></script>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>
		@yield('page_style')
	</head>
	<body>
		@yield('page_content')

		@yield('page_scripts')
	</body>
</html>