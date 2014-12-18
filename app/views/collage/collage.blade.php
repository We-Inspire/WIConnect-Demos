@extends('collage.master')

@section('page_title')
WI - Collage
@stop

@section('page_style')
<style>
	.background-link {
		width: 100%;
		height: 100%;
		z-index: 0;
		position: absolute;
		top: 0;
		left: 0;

	}

	.background-link span {
		display: block;
		text-align: center;
		position: absolute;
		top: 50%;
		left: 50%;
		font-size: 3em;
		margin-left: -234px;
		margin-top: -27.5px;
		color: hsla(0, 0%, 0%, 0.6);
		vertical-align: middle;
	}
	html, body {
		width: 100%;
		height: 100%;
		padding: 0;
		overflow: hidden;
		font-family: Arial;
	}

	#collage {
		position: absolute;
		top: 0;
		height: 100%;
		width: 100%;
		overflow: hidden;
	}

	.item {
		position: absolute;
		border: 13px solid white;
		box-shadow: 0px 0px 34px 0 rgba(0,0,0,0.75);
		-webkit-transform: scale(0.01);
		-webkit-transition: -webkit-transform 1s;

		top: 100%;
	}
</style>
@stop

@section('page_content')
<div class="background-link">
	<span>collage.we-inspire.net</span>
</div>
<div id="collage"></div>
@stop

@section('page_scripts')
<script src="{{URL::to('packages/weinspire/weinspire/js/WIconnect/sockjs-0.3.min.js')}}"></script>
<script src="{{URL::to('packages/weinspire/weinspire/js/WIconnect/connection.js')}}"></script>
<script src="{{URL::to('packages/weinspire/weinspire/js/underscore-min.js')}}"></script>
<script src="{{URL::to('js/lz-string-1.3.3-min.js')}}"></script>
<script>
	connection.subscribeBroadcast('images', function() {});

	var n = 50;

	connection.on("broadcast", function(data) {
		var img = new Image();
		$("#collage").append(img);
		img.className = "item";
		img.src = LZString.decompress(data.data.image);
		img.onload = function() {
			move(this);
		}
	});

	function move(img) {
		var rotation = Math.round(random(-33, 33)) + "deg",
			scale = random(0.3, 0.7).toFixed(2);
		var scaledWidth = img.width * scale,
			scaledHeight = img.height * scale;
		var top = Math.round(random(((img.height - scaledHeight) / 2 / window.innerHeight) * -100 + n, 100 - n - ((img.height / window.innerHeight) * 100) + ((img.height - scaledHeight) / 2 / window.innerHeight) * 100)) + "%",
			left = Math.round(random(((img.width - scaledWidth) / 2 / window.innerWidth) * -100 + n, 100 - n - ((img.width / window.innerWidth) * 100) + ((img.width - scaledWidth) / 2 / window.innerWidth) * 100)) + "%";
		img.style.top = top;
		img.style.left = left;
		img.style.webkitTransform = "scale3d("+scale+", "+scale+", 1) rotate("+rotation+")";
		img.addEventListener("webkitTransitionEnd", function() {
			img.style.webkitTransform = "scale("+scale+") rotate("+rotation+")";
		}, false);

		if (n > 0)
			n -= 10;
	}

	function random(min, max) {
		return min + (Math.random() * (max - min));
	}
</script>
@stop