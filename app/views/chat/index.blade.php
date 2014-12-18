@extends('weinspire.default')

@section("content")
	<div class="card" style="background-color: white;">
		{{Form::open(array('action' => 'ChatController@postMessage', 'ng-submit' => "create('".URL::action('ChatController@postMessage')."')",'class'=>'form-horizontal', 'onsubmit' => 'return false;'))}}
			<div class="form-group">
				<label class="col-sm-2 control-label">Spitzname</label>
				<div class="col-sm-10">
					@if($user != null)
						<input id="author" type="text" class="form-control" required placeholder="Name" value="{{$user->name}}" ng-model="chat.author">
					@else
						<input id="author" type="text" class="form-control" required placeholder="Name" ng-model="chat.author"></input>
					@endif
				</div>
				
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Nachricht</label>
				<div class="col-sm-10">
					<textarea id="message-text-box" class="form-control" required style="max-width: 100%; min-width: 100%;" placeholder="Nachricht..." ng-model="chat.message" rows="3"></textarea>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-primary">Senden</button>
				</div>
			</div>
		{{Form::close()}}
	</div>
	<div ng-repeat="(key,collection) in collections" class="col-sm-12" style="margin-top: 233px;">
		<div ng-show="key == 'chats'">
			<div ng-repeat="chat in collection|reverse" class="item card" style="background-color: [[chat.chat_user.color]]">
				<div class="row">
					<div class="col-md-6 col-xs-6 col-sm-6 author">
						<strong>[[chat.chat_user.name]]</strong>
					</div>
					<div class="col-md-6 col-xs-6 col-sm-6 date text-right">
							[[chat.created_at]]
					</div>
				</div>
				<div class="postcontent">[[chat.message]]</div>
			</div>
		</div>
	</div>
@stop

@section('scripts')
<script type="text/javascript">
$('#message-text-box').keyup(function (event) {
    var keypressed = event.keyCode || event.which;
    if (keypressed == 13) {
        $(this).closest('form').submit();
    }
});
</script>
@stop


@section('styles')
	<style>
		input, textarea, button {
			border-radius: 0 !important;
			width: 100%;
			resize: none;
			outline: none;
			box-shadow: none;
			border: 1px solid gray;
		}

		button {
			margin-top: 3px;
		}

		div.item {
			margin: 8px 55px;
			padding: 8px;
			position: relative;
			width: auto;
			z-index: 500;
			border: 3px solid rgba(0, 0, 0, 0.21);
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

		.card {
			position: fixed;
			top: 0;
			left: 0;
			z-index: 1000;
			width: 100%;
			margin-bottom: 20px;
			padding: 5px;
			border-bottom: 3px solid rgba(0, 0, 0, 0.21);
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

		div.container {
			padding: 0;
		}

		.form-group {
			margin: 0;
		}

		label.control-label {
			padding-top: 0;
			text-align: center;
		}

		html, body {
			padding: 0;
			margin: 0;
			background-color: transparent;
			overflow-x: hidden;
		}

		@media (orientation: portrait) {
			div.item {
				margin: 8px 0;
			}
		}
	</style>
@stop