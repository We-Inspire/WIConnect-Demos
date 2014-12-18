@extends('collage.master')

@section('page_title')
WI - Collage - Upload
@stop

@section('page_style')

<style>
	.noSelect {
		-webkit-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
	}

	html, body {
		width: 100%;
		height: 100%;
		margin: 0;
		padding: 0;
		overflow: hidden;
		background-color: rgb(21, 21, 21);
	}

	#container {
		position: absolute;
		width: 100%;
		height: 100%;
		overflow: hidden;
		text-align: center;
	}

	#cameraview {
		position: relative;
		width: 100%;
		height: auto;
		z-index: 10;
		pointer-events: none;
	}

	@media (min-width: 60em) and (orientation: landscape) {
		#cameraview {
			width: auto;
			height: 100%;
		}
	}

	#info {
		position: absolute;
		top: 0;
		width: 100%;
		height: 100%;
		z-index: 20;
		background-color: rgba(0, 0, 0, 0.55);
	}

	span {
		position: relative;
		top: 20%;
		font-family: 'Open Sans', Arial;
		font-size: 2em;
		color: white;
		cursor: default;
	}

	#upload {
		position: absolute;
		bottom: -144px;
		width: 100%;
		height: 144px;
		z-index: 30;
		background-color: rgba(30, 132, 210, 0.89);
		line-height: 144px;
		color: white;
		font-family: 'Open Sans', Arial;
		font-size: 5em;
	}
	#upload:hover {
		cursor: pointer;
		background-color: rgba(30, 132, 210, 1);
	}

	video {
		display: none;
	}
</style>

@stop

@section('page_content')

<div id="container">
	<div id="info" class="noSelect">
		<span>
			Please allow us to use your webcam.
			<br />
			<br />
			<br />
			Click/Touch to take a picture.
		</span>
	</div>
	<div id="upload" class="noSelect">UPLOAD</div>
</div>

@stop

@section('page_scripts')
	<script src="{{URL::to('packages/weinspire/weinspire/js/WIconnect/sockjs-0.3.min.js')}}"></script>
	<script src="{{URL::to('packages/weinspire/weinspire/js/WIconnect/connection.js')}}"></script>
	<script src="{{URL::to('packages/weinspire/weinspire/js/underscore-min.js')}}"></script>
	<script src="{{URL::to('js/lz-string-1.3.3-min.js')}}"></script>
	<script>
		var frameDate = 0,
			lastFrameDate = 0,
			video = null,
			image = null,
			canvasSource = null,
			contextSource = null,
			picTaken = false,
			frameLength = 32, // 64 = ~15FPS ; 32 = ~30FPS ; 16 = ~60FPS
			constraints = {
				video: true,
				audio: false
			};

		window.onload = function () {
			$("body").append("<video autoplay muted id='video' width='100%' height='100%'></video>");

			/*if(navigator.appVersion.match("iPhone") || navigator.appVersion.match("Android")) {
				$("body").append("<input type='file' capture='camera' accept='image/*' id='cameraInput' style='display: none; position: absolute; z-index: 100;'>");
				var container = document.getElementById('container');

				

				
				var input = document.getElementById("cameraInput");

				$("#info").on("click", function(event) {
					event.stopPropagation();
					$(this).hide();
					$('#cameraInput').trigger("click");
				});

				$("#container").on("click", function(event) {
					$('#cameraInput').trigger("click");
				});

				$("#cameraInput").on("change", function(event) {
					if(event.target.files.length == 1 && event.target.files[0].type.indexOf("image/") == 0) {
						$("#cameraview").remove();
						$("#container").prepend("<canvas id='cameraview' width='100%' height='100%'></canvas>");
						canvasSource = $("#cameraview")[0];
						canvasSource.width = $("#container").width();
						canvasSource.height = $("#container").height();
						contextSource = canvasSource.getContext('2d');
						$("#image").remove();
						$("body").append("<img src='' width='600' id='image' style='display: none; height: auto; width: 100%;'>");
						var img = document.getElementById("image");
						var fr = new FileReader();
						var f = input.files[0];
						fr.onload = function() {
							console.log(fr.result);
							img.src = fr.result;
						};
						fr.readAsDataURL(f);
						$(img).on("load", function(e) {
							console.log($(canvasSource).width(), $(canvasSource).height());
							contextSource.drawImage(img, 0, 0, $(canvasSource).width(), $(canvasSource).height());
							image = canvasSource.toDataURL('image/jpeg', 0.7);
							connection.sendBroadcast("images", {image: LZString.compress(image)});
						});
						
					}
				});
			} else {*/

				video = document.getElementById('video');
				video.addEventListener('loadedmetadata', function() {
					$("#container").prepend("<canvas id='cameraview' width='"+video.videoWidth+"' height='"+video.videoHeight+"'></canvas>");
					
					canvasSource = $("#cameraview")[0];
					contextSource = canvasSource.getContext('2d');

					requestAnimFrame(render);
				}, false);

				start();
			//}
		};

		function render() {
			frameDate = Date.now();
			if (frameDate - lastFrameDate >= frameLength) {
				lastFrameDate = frameDate; 
				if (!picTaken)
					contextSource.drawImage(video, 0, 0, video.videoWidth, video.videoHeight);
			}
			requestAnimFrame(render);
		}

		function start() {
			if (!!window.stream) {
				video.src = null;
				window.stream.stop();
			}

			navigator.getUserMedia(constraints, successCallback);
		}

		function successCallback(stream) {
			window.stream = stream;
			video.src = window.URL.createObjectURL(stream);
			video.play();

			$('#info span').html('<br /><br /><br />Click/Touch to take a picture.');
			$("#info").on("click", function(event) {
				event.stopPropagation();
				$(this).hide();
			});
			$("#container").on("click", function() {
				image = canvasSource.toDataURL('image/jpeg', 0.7);
				connection.sendBroadcast("images", {image: LZString.compress(image)});
				/*if (picTaken) {
					picTaken = false;
					$("#upload").stop().animate({'bottom': '-144px'}, 100);
				} else {
					picTaken = true;
					image = canvasSource.toDataURL('image/jpeg', 0.7);
					connection.sendBroadcast("images", {image: LZString.compress(image)});
					$("#upload").stop().animate({'bottom': '0px'}, 100);
				}*/
			});
			$("#upload").on("click", function() {
				connection.sendBroadcast("images", {image: LZString.compress(image)});
			});
		}

		navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
		window.requestAnimFrame = (function() {
			return  window.requestAnimationFrame       ||
					window.webkitRequestAnimationFrame ||
					window.mozRequestAnimationFrame    ||
					function(callback) {
						window.setTimeout(callback, 1000 / 30);
					};
		})();
	</script>
@stop