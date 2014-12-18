@extends('weinspire.default')



@section("content")
	<div class="background-link">
		<span>chat.we-inspire.net</span>
	</div>
	<div ng-repeat="(key,collection) in collections" class="col-sm-12">
		<div ng-show="key == 'chats'">
			<div ng-repeat="chat in collection|reverse" class="item card" style="background-color: [[chat.chat_user.color]]">
				<div class="row">
					<div class="col-md-6 author">
						<strong>[[chat.chat_user.name]]</strong>
					</div>
					<div class="col-md-6 date text-right">
							[[chat.created_at]]
					</div>
				</div>
				<div class="postcontent">[[chat.message]]</div>
			</div>
		</div>
	</div>
@stop

@section('styles')
	<style>

		html, body, .container {
			height: 100%;
		}

		.background-link {
			width: 100%;
			height: 100%;
			top: 0px;
			left: 0px;
			z-index: 0;
			position: absolute;
		}

		.background-link span {
			display: block;
			text-align: center;
			position: absolute;
			top: 50%;
			left: 50%;
			font-size: 3em;
			margin-left: -180px;
			margin-top: -30px;
			color: hsla(0, 0%, 0%, 0.6);
			vertical-align: middle;
		}

		input, textarea, button {
			border-radius: 0 !important;
			width: 100%;
		}

		div.item {
			margin: 8px 55px;
			padding: 13px;
			position: relative;
			box-sizing: content-box;
		}

		div.col-sm-12 {
			padding: 0;
		}

		div.item .id {
			float: left;
			font-size: 2.5em;
			color: rgba(128, 128, 128, .5);
			position: relative;
			top: -7px;
			left: -5px;
		}

		h3 {		
			color: rgb(30,132,210);
			margin-top: 9px;

		}

		.card{
			margin-top: 20px;
			margin-bottom: 20px;
			padding: 20px;
			box-sizing: content-box;
			border: 3px solid rgba(0, 0, 0, 0.21);
		}

		div.date, div.author {
			color: rgb(50, 50, 50);
		}
		div.date {	
			font-size: 1em;
		}

		div.postcontent {
			border-top: 2px solid rgba(0, 0, 0, 0.13);
			margin-top: 5px;
			padding-top: 13px;
			padding-bottom: 8px;
			word-wrap: break-word;
		}

		html, body {
			padding: 0;
			margin: 0;
			background-color: transparent;
		}
	</style>
@stop