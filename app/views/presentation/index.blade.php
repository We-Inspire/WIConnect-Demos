<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<title>Interne Präsentation | We Inspire</title>
		<link rel="stylesheet" href="{{ URL::asset('css/reveal.min.css') }}">
		<link rel="stylesheet" href="{{ URL::asset('css/theme/weinspire.css') }}" id="theme">
		<link rel="stylesheet" href="{{ URL::asset('lib/css/zenburn.css') }}">
		<script>
			document.write( '<link rel="stylesheet" href="{{ URL::asset('css/print/') }}/' + ( window.location.search.match( /print-pdf/gi ) ? 'pdf' : 'paper' ) + '.css" type="text/css" media="print">' );
		</script>
		<!--[if lt IE 9]>
			<script src="lib/js/html5shiv.js"></script>
		<![endif]-->
	</head>
	<body>
		<div class="reveal">
			<div class="slides">
				<section>
					<h1 class="logotitle">We Inspire</h1>
				</section>
				<section>
					<section>
						<h1>Das Team</h1>
					</section>
					<section>
						<div style="position: absolute; left: 40px; right: 40px;">
							<ul style="float: left; width: 50%; position: relative; top: 100px;">
								<li data-fragment-index="1" class="fragment highlight-current-blue">Markus Nissl (PL)</li>
								<li data-fragment-index="2" class="fragment highlight-current-blue">Christoph Burger (PL-Stv.)</li>
								<li data-fragment-index="3" class="fragment highlight-current-blue">Linus Horvath (PMA)</li>
							</ul>
							<div style="position: relative; height: 400px; width: 50%;">
								<img data-fragment-index="1" style="position: absolute;" class="fragment current-visible" src="http://projekt.weinspire.at/images/markusnissl.jpg">
								<img data-fragment-index="2" style="position: absolute;" class="fragment current-visible" src="http://projekt.weinspire.at/images/christophburger.jpg">
								<img data-fragment-index="3" style="position: absolute;" class="fragment current-visible" src="http://projekt.weinspire.at/images/linushorvath.jpg">
							</div>
						</div>
					</section>
				</section>
				<section>
					<section>
						<h1>Projektidee</h1>
					</section>
					<section>
						<ul>
							<li>Research-Projekt</li>
							<li>Synchron/Asynchron Kombination?</li>
							<li>Laravel + Meteor</li>
						</ul>
					</section>
					<section>
						<img src="{{ URL::asset('lib/concept.png') }}" style="width: 65%; height: auto;">
					</section>
				</section>
				<section>
					<section>
						<h1>Marketing</h1>
					</section>
					<section>
						<ul>
							<li>Repräsentatives, konsistentes Design</li>
							<li>Marketingtexte</li>
							<li>Facebook-Auftritt</li>
							<li>Webauftritt</li>
							<li>Visitenkarten</li>
						</ul>
					</section>
				</section>
				<section>
					<section>
						<h1>Demo-Anwendung</h1>
					</section>
					<section>
						<iframe src="" width="1024" height="640"></iframe>
					</section>
				</section>
				<section>
					<section>
						<h1>Technischer Hintergrund</h1>
					</section>
					<section>
						<img src="{{ URL::asset('lib/communication.png') }}" style="width: 65%; height: auto;">
					</section>
				</section>
				<section>
					<section>
						<h1>Aktueller Status</h1>
					</section>
					<section>
						<ul>
							<li>Status: <span style="color: #17ff2e">Grün</span></li>
							<li>Prototyp funktionsfähig</li>
							<li>Testanwendungen</li>
						</ul>
					</section>
				</section>
				<section>
					<section>
						<h1>Nächste Schritte</h1>
					</section>
					<section>
						<ul>
							<li>Veröffentlichung des Release-Candidates</li>
							<li>Produktwebseite</li>
							<li>Sponsorensuche</li>
						</ul>
					</section>
				</section>
			</div>
		</div>
		<script src="{{ URL::asset('js/reveal.min.js') }}"></script>
		<script>
			Reveal.initialize();
			loadScript(window.location.origin+":4000/socket.io/socket.io.js",function(){
    			
				var socket = io.connect(window.location.origin+":4000");
				socket.on('sitechange',function(data) {
					console.log(data);
				});	
				socket.on('siteright',function() {
					Reveal.right();
				});	
				socket.on('siteleft',function() {
					Reveal.left();
				});	
				socket.on('siteup',function() {
					Reveal.up();
				});	
				socket.on('sitedown',function() {
					Reveal.down();
				});	
			});
			
			function loadScript(url, callback){

 			   var script = document.createElement("script")
			    script.type = "text/javascript";

			    if (script.readyState){  //IE
				script.onreadystatechange = function(){
				    if (script.readyState == "loaded" ||
					    script.readyState == "complete"){
					script.onreadystatechange = null;
					callback();
				    }
				};
			    } else {  //Others
				script.onload = function(){
				    callback();
				};
			    }

			    script.src = url;
			    document.getElementsByTagName("head")[0].appendChild(script);
			}	
		</script>
	</body>
</html>
