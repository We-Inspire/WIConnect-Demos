@extends('weinspire.default')

@section("styles")
<style>

	html, body {
		height: 100%;
		background: rgba(242,242,242,1);
		background: -moz-radial-gradient(center, ellipse cover, rgba(242,242,242,1) 0%, rgba(210,210,210,1) 100%);
		background: -webkit-gradient(radial, center center, 0px, center center, 100%, , color-stop(0%, rgba(242,242,242,1)), color-stop(100%, rgba(210,210,210,1)));
		background: -webkit-radial-gradient(center, ellipse cover, rgba(242,242,242,1) 0%, rgba(210,210,210,1) 100%);
		background: -o-radial-gradient(center, ellipse cover, rgba(242,242,242,1) 0%, rgba(210,210,210,1) 100%);
		background: -ms-radial-gradient(center, ellipse cover, rgba(242,242,242,1) 0%, rgba(210,210,210,1) 100%);
		background: radial-gradient(ellipse at center, rgba(242,242,242,1) 0%, rgba(210,210,210,1) 100%);
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f2f2f2', endColorstr='#d2d2d2', GradientType=1 );
		overflow-x: hidden;
	}

	.carouselcont {
		position: relative;
		*top: 50%;
		top: 100px;
		*margin-top: -105px;
		left: 50%;
		margin-left: -332px;
		width: 664px;
		height: 384px;
		perspective: 1000px;
		-webkit-perspective: 1000px;
		
	}

	.room {
		width: 100%;
		height: 100%;
		position: absolute;
		-webkit-transform-style: preserve-3d;
		-webkit-transition: all 1s ease-in-out;
	}

	.room div {
		display: block;
		position: absolute;
		width: 640px;
		height: 360px;
		left: 10px;
		top: 10px;
		*border: 2px solid black;
		text-align: center;
		-webkit-transform: none;
		-webkit-transition: all 1s ease-in-out;

	}

	.room div img {
		margin: 2.5px auto;
		height: 360px;
		width: auto;
		box-shadow: 0px 0px 10px 5px #d3d3d3;
	}


	#content{
			position: relative;
			width: 100%;
			height: auto;
			margin: 0;
			padding: 0;
		}

	.control{
		width: 30px;
		height: 360px;
		background-color: rgba(0,0,0,0.7);
		position: absolute;
		top: 0;
	}
	

	.control.left{
		left: 50%;
		margin-left: -320px;
	}

	.control.right{
		right: 50%;
		margin-right: -320px;
	}

</style>
@stop

@section("scripts")
	<script src="{{URL::asset('js/filters.js')}}"></script>
	<script src="{{URL::asset('js/modernizr.min.js')}}"></script>
	<script src="{{URL::asset('js/3DCarousel.js')}}"></script>
	<script>

	//$(document).ready(function() {

		var devices = 1;
		var carousel = null;

		connection.subscribeBroadcast('images', function() {});

		connection.on("broadcast", function(data) {

			if($("#userpic"+data.user)[0]){
				$("#userpic"+data.user)[0].src = data.data.image;
				var pos = $("#userpic"+data.user).parent().index();
				carousel.goTo(pos);
			}else{
				$(".room").append("<div> <img id='userpic"+data.user+"' src='"+data.data.image+"'></div>");
				carousel.addPanel();
			}
			
			
			
			
		});

		connection.on("connected", function(data) {
			
			carousel = new Carousel3D( document.getElementById('vidcarousel') );
			carousel.panelCount = 0;
     		carousel.modify();
     		setTimeout( function(){
	        $("body").addClass('ready');
	      }, 0);
		});

		$(window).on("keydown", function(e) {

			var position;

			switch(e.keyCode) {
				case 39:
					position = 1;
					break;
				case 37:
					position = -1;
					break;
			}

			carousel.rotate(position);

		});

	//});

	</script>

	<script type="text/javascript">
		//var audioSelect = document.querySelector("select#audioSource");
        var videoSelect = document.querySelector("select#videoSource");
        navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
        var video = document.getElementById('video');
        var lastFrame = 0;
        var loop = false;
        var frameLength = 66;
        var lastImageData;
        var page_lock = 0;
        var control = false;

        window.requestAnimFrame = (function(){
	      	return  window.requestAnimationFrame       ||
	                window.webkitRequestAnimationFrame ||
	                window.mozRequestAnimationFrame    ||
	                function( callback ){
	                	window.setTimeout(callback, 1000 / 30);
	                };
	    })();

	    function gotSources(sourceInfos) {
			for (var i = 0; i != sourceInfos.length; ++i) {
			    var sourceInfo = sourceInfos[i];
			    var option = document.createElement("option");
			    option.value = sourceInfo.id;
			    /*if (sourceInfo.kind === 'audio') {
			      option.text = sourceInfo.label || 'microphone ' + (audioSelect.length + 1);
			      audioSelect.appendChild(option);
			    } else */if (sourceInfo.kind === 'video') {
			      option.text = sourceInfo.label || 'camera ' + (videoSelect.length + 1);
			      videoSelect.appendChild(option);
			    } else {
			      console.log('Some other kind of source: ', sourceInfo);
			    }
			}
        }

        if (typeof MediaStreamTrack === 'undefined'){
                alert('This browser does not support MediaStreamTrack.\n\nTry Chrome Canary.');
        } else {
                MediaStreamTrack.getSources(gotSources);
        }

        function successCallback(stream) {
                window.stream = stream; // make stream available to console
                video.src = window.URL.createObjectURL(stream);
                var data = {src: video.src};
                video.play();
        }


        function errorCallback(error){
                console.log("navigator.getUserMedia error: ", error);
        }

        function start(){
                if (!!window.stream) {
                video.src = null;
                window.stream.stop();
                }
                //var audioSource = audioSelect.value;
                var videoSource = videoSelect.value;
                var constraints = {
                /*audio: {
                  optional: [{sourceId: audioSource}]
                },*/
                video: {
                   mandatory: {
                          maxWidth: 640,
                          maxHeight: 360
                        },
                  optional: [{sourceId: videoSource}]
                }
                };
                navigator.getUserMedia(constraints, successCallback, errorCallback);
        }

        start();

        window.onload = function (){

	        video.addEventListener('loadedmetadata', function(){
	                loop = true;
	                requestAnimFrame(render);
	        }, false);

	        $(".control").hide();

        };

        function render() {
        	drawVideo();
        	blend();
   			if(Date.now() - lastFrame > frameLength && control){
        		var direction = checkAreas();
        		if(Date.now() - page_lock > 1000 && direction != 0){
        			page_lock = Date.now();
        			carousel.rotate(direction);
        		}
   			} 	
        	requestAnimFrame(render);
        }

        var canvasSource = $("#canvas-source")[0];
        var canvasFilter = $("#canvas-filter")[0];
		var canvasBlended = $("#canvas-blended")[0];

		var contextSource = canvasSource.getContext('2d');
		var contextFilter = canvasFilter.getContext('2d');
		var contextBlended = canvasBlended.getContext('2d');

		//Mirror the picture
		contextSource.translate(canvasSource.width, 0);
		contextSource.scale(-1, 1);

        function drawVideo() {
			contextSource.drawImage(video, 0, 0, video.width, video.height);

			var filterid = parseInt($("#filters option:selected").val());

        	var pixels = Filters.getPixels(canvasSource);

		    if(filterid != 0){
		      	var res = Filters[filterlist[filterid].name].apply(Filters, [pixels].concat(filterlist[filterid].param));

		    	canvasFilter = Filters.toCanvas(res);
		    }else{
		    	canvasFilter = Filters.toCanvas(pixels);
		    }

		    contextFilter.putImageData(Filters.getPixels(canvasFilter),0,0);
		}


		function blend() {
			var width = canvasSource.width;
			var height = canvasSource.height;
			// get webcam image data
			var sourceData = contextSource.getImageData(0, 0, width, height);
			// create an image if the previous image doesn’t exist
			if (!lastImageData) lastImageData = contextSource.getImageData(0, 0, width, height);
			// create a ImageData instance to receive the blended result
			var blendedData = contextSource.createImageData(width, height);
			// blend the 2 images
			differenceAccuracy(blendedData.data, sourceData.data, lastImageData.data);
			// draw the result in a canvas
			contextBlended.putImageData(blendedData, 0, 0);
			// store the current webcam image
			lastImageData = sourceData;
		}

		function differenceAccuracy(target, data1, data2) {
			if (data1.length != data2.length) return null;
			var i = 0;
			while (i < (data1.length * 0.25)) {
				var average1 = (data1[4*i] + data1[4*i+1] + data1[4*i+2]) / 3;
				var average2 = (data2[4*i] + data2[4*i+1] + data2[4*i+2]) / 3;
				var diff = threshold(fastAbs(average1 - average2));
				target[4*i] = diff;
				target[4*i+1] = diff;
				target[4*i+2] = diff;
				target[4*i+3] = 0xFF;
				++i;
			}
		}

		function fastAbs(value) {
			// funky bitwise, equal Math.abs
			return (value ^ (value >> 31)) - (value >> 31);
		}

		function threshold(value) {
			return (value > 0x15) ? 0xFF : 0;
		}


		function checkAreas() {

			var leftdata = contextBlended.getImageData(0,0,30,video.height);
			var rightdata = contextBlended.getImageData(video.width-30,0,30,video.height);

			var averageleft = 0;
			var averageright = 0;

			var ileft = 0;
			var iright = 0;

			while (ileft < (leftdata.data.length * 0.25)) {
				// make an average between the color channel
				averageleft += (leftdata.data[ileft*4] + leftdata.data[ileft*4+1] + leftdata.data[ileft*4+2]) / 3;
				++ileft;
			}

			while (iright < (rightdata.data.length * 0.25)) {
				// make an average between the color channel
				averageright += (rightdata.data[iright*4] + rightdata.data[iright*4+1] + rightdata.data[iright*4+2]) / 3;
				++iright;
			}

			averageleft = Math.round(averageleft / (leftdata.data.length * 0.25));
			averageright = Math.round(averageright / (rightdata.data.length * 0.25));

			var page = 0;
			if(averageleft > 10){
				page -= 1;
			}

			if(averageright > 10){
				page += 1;
			}

			return page;

		}

		var filterlist = [
			{name: 'none', param: []},
			{name: 'grayscale', param: []},
			{name: 'grayscaleAvg', param: []},
			{name: 'threshold', param: [128]},
			{name: 'invert', param: []},
			{name: 'gaussianBlur', param: [4]},
			{name: 'brightnessContrast', param: [-0.25,1.5]},
			{name: 'distortSine', param: [0.4,-0.3]},
			{name: 'invert', param: []},
			{name: 'sobel', param: []},
			{name: 'laplace', param: []},
			{name: 'luminance', param: []},
			{name: 'verticalFlip', param: [] },
			{name: 'horizontalFlip', param: []}
		];

		filterlist.forEach(function(filter,id){
		  $("#filters").append("<option value="+id+">"+filter.name+"</option>");
		});

		$("#canvas-filter").on('click',function(){
			var src = canvasFilter.toDataURL('image/jpeg', 0.8);
			if($("#mypic")[0]){
				$("#mypic")[0].src = src;
				var pos = $("#mypic").parent().index();
				carousel.goTo(pos);
			}else{
				$(".room").append("<div> <img id='mypic' src='"+src+"'></div>");
				carousel.addPanel();
			}

			connection.sendBroadcast("images", {image: src});
		});

		$("#control").on("change", function() {
			if($(this).prop("checked")) {
				control = true;
				$(".control").show();
			} else {
				control = false;
				$(".control").hide();
			}
		});

		//audioSelect.onchange = start;
		videoSelect.onchange = start;


	</script>
@stop

@section("content")

<div class="row">
<div id="content" style="text-align: center;">
		<video muted autoplay id="video" width="640" height="360" style="display: none;"></video>
		<canvas id="canvas-source" width="640" height="360" style="display: none;"></canvas>
		<canvas id="canvas-blended" width="640" height="360" style="display: none;"></canvas>
		<canvas id="canvas-filter" width="640" height="360"></canvas>
	
		<div class="control left"></div>
		<div class="control right"></div>
	</div>
</div>

<div class="row">
	 <div class="col-md-5 col-sm-4 col-xs-4">
            <select id="videoSource" class="form-control"></select>
    </div>
    <div class="col-md-5 col-sm-4 col-xs-4">
            <select id="filters" class="form-control"></select>
    </div>
	<div class="col-md-2 col-sm-4 col-xs-4">
		<label for="control">
			<input type="checkbox" id="control">
			Visuelle Kontrolle
		</label>
	</div>
</div>
<div class="carouselcont">
	<div class="room" id="vidcarousel"></div>
</div>
@stop
