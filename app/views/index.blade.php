<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Demoanwendungen - We Inspire</title>
		<link rel="stylesheet" href="{{ URL::asset('css/bootstrap.min.css') }}">
		<link rel="stylesheet" href="{{ URL::asset('css/style3.css') }}">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<style>

			html {
				height: 100%;
				-webkit-font-smoothing: antialiased;
			}

			body {
				min-height: 100%;
				height: auto;
				background: rgb(242,242,242); /* Old browsers */
background: -moz-radial-gradient(center, ellipse cover, rgba(242,242,242,1) 0%, rgba(255,255,255,1) 50%, rgba(242,242,242,1) 100%); /* FF3.6+ */
background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%,rgba(242,242,242,1)), color-stop(50%,rgba(255,255,255,1)), color-stop(100%,rgba(242,242,242,1))); /* Chrome,Safari4+ */
background: -webkit-radial-gradient(center, ellipse cover, rgba(242,242,242,1) 0%,rgba(255,255,255,1) 50%,rgba(242,242,242,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-radial-gradient(center, ellipse cover, rgba(242,242,242,1) 0%,rgba(255,255,255,1) 50%,rgba(242,242,242,1) 100%); /* Opera 12+ */
background: -ms-radial-gradient(center, ellipse cover, rgba(242,242,242,1) 0%,rgba(255,255,255,1) 50%,rgba(242,242,242,1) 100%); /* IE10+ */
background: radial-gradient(ellipse at center, rgba(242,242,242,1) 0%,rgba(255,255,255,1) 50%,rgba(242,242,242,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f2f2f2', endColorstr='#f2f2f2',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
			}

			.container .row img {
				
				*height: auto;
			}

			.container .row {
				-webkit-perspective: 1000px;
			}

			.container h1 {
				color: rgb(21, 112, 175);
				margin-bottom: 60px;
			}

			.container .row>div {
				-webkit-transition: all .5s ease-in-out;
			}

			.container .row>div {
				-webkit-transform: none;
			}

			@media(min-width: 992px) {
				.container .row>div.col-md-4:nth-child(1) {
					-webkit-transform: rotateY(45deg);
				}

				.container .row>div.col-md-4:nth-child(1):hover {
					-webkit-transform: rotateY(20deg);
				}

				.container .row>div.col-md-4:nth-child(2) {
					-webkit-transform: translateZ(-100px);
				}

				.container .row>div.col-md-4:nth-child(2):hover {
					-webkit-transform: translateZ(0px);
				}

				.container .row>div.col-md-4:nth-child(3) {
					-webkit-transform: rotateY(-45deg);
				}

				.container .row>div.col-md-4:nth-child(3):hover {
					-webkit-transform: rotateY(-20deg);
				}
			}

			@media(max-width: 992px) {
				.view img {
					position: static !IMPORTANT;
					height: 250px !IMPORTANT;
					width: auto !IMPORTANT;
					margin: 20px auto;
				}

				.view {
					text-align: center;
				}

			}

			

			.view {
				position: relative;
				overflow: hidden;
				width: 100%;
				height: 300px;
				box-shadow: 0px 0px 10px 2px #f2f2f2;
				-webkit-transition: all .5s ease-in-out;
				-webkit-perspective: 1000px;
			}

			.view:hover {
				box-shadow: 0px 0px 10px 2px rgba(0, 0, 0, .2);
			}

			.view img {
				position: absolute;
				height: 300px;
				width: 100%;
				
			}

			.view .mask {
				position: absolute;
				height: 100%;
				width: 100%;
				color: #fff;
				top: 0;
				left: 0;
				text-align: center;
			}

			.view .mask h2 {
				*border-bottom: 4px solid rgb(21, 112, 175);
				width: 100%;
				margin: 0 auto;
				height: 50px;
				padding-bottom: 10px;
				padding-top: 10px;
				background-color: rgba(0, 0, 0, .4);
			}

			.view .mask p {
				height: 150px;
				padding: 0 10px;
				text-align: center;
				box-sizing: border-box;
				margin-top: 20px;
				color: rgb(255, 255, 255);
			}

			.view .mask a {
				color: rgb(30, 132, 210);
				background-color: rgb(0, 0, 0, .3);
			}

			.view:hover img {
				*-webkit-transform: translateZ(150px);
			}

			.shape {
			  stroke-dasharray: 140 540;
			  stroke-dashoffset: -474;
			  stroke-width: 8px;
			  fill: transparent;
			  stroke: rgb(21, 112, 175);
			  border-bottom: 5px solid black;
			  transition: stroke-width 1s, stroke-dashoffset 1s, stroke-dasharray 1s, fill 1s;
			}
			.text {
			  line-height: 32px;
			  letter-spacing: 8px;
			  color: rgb(21, 112, 175);
			  top: -48px;
			  position: relative;
			}

			a:hover .shape {
			  stroke-width: 2px;
			  stroke-dashoffset: 0;
			  stroke-dasharray: 760;
			  fill: rgba(0, 0, 0, .4);
			}

			a svg {
				margin: 0 auto;
				width: 320px;
			}

		</style>
		<script src="{{ URL::asset('js/jquery.js') }}"></script>
		<script>	

			window.onload = init;

			function init() {


				$("#chat").on("click", function() {
					window.location.href = '{{ URL::action("ChatController@getIndex") }}';
				});

				$("#carousel").on("click", function() {
					window.location.href = '{{ URL::action("CarouselController@getIndex") }}';
				});

				$("#presentation").on("click", function() {
					window.location.href = '{{ URL::action("PresentationController@getIndex") }}';
				});

			}

		</script>
	</head>
	<body>
		<div class="container">
			<h1>Demoanwendungen</h1>
			<div class="row">
				<div class="col-md-4">
					<div class="view view-third" id="chat">
						<img src="{{ URL::asset('img/chat.jpg') }}">
						<div class="mask">
							<h2>Chat</h2>
							<p>Eine kleine einfache Chatanwendung</p>
							<a href="{{ URL::action('ChatController@getIndex') }}" class="info">
								<svg height="60" xmlns="http://www.w3.org/2000/svg">
									<rect class="shape" height="60" width="320" />
									<div class="text">Zur Anwendung</div>
								</svg>
							</a>
						</div>
					</div>
				</div>
			
				<div class="col-md-4">
					<div class="view view-third" id="carousel">
						<img src="{{ URL::asset('img/carousel.jpg') }}">
						<div class="mask">
							<h2>Carousel</h2>
							<p></p>
							<a href="{{ URL::action('CarouselController@getIndex') }}" class="info">
								<svg height="60" xmlns="http://www.w3.org/2000/svg">
									<rect class="shape" height="60" width="320" />
									<div class="text">Zur Anwendung</div>
								</svg>
							</a>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="view view-third" id="presentation">
						<img src="{{ URL::asset('img/presentation.jpg') }}">
						<div class="mask">
							<h2>Präsentation</h2>
							<p></p>
							<a href="{{ URL::action('PresentationController@getIndex') }}" class="info">
								<svg height="60" xmlns="http://www.w3.org/2000/svg">
									<rect class="shape" height="60" width="320" />
									<div class="text">Zur Anwendung</div>
								</svg>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>