<!DOCTYPE HTML>
<html ng-app="WIApp" class="no-js">
	<head>
		<meta charset="utf-8">
		<title>@include('weinspire.title')</title>
    		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="{{URL::asset('css/bootstrap.min.css')}}" rel="stylesheet">
		@yield('styles')
	</head>
	<body ng-controller="WICollectionCtrl">
		@include('weinspire.template')
		<script src="{{URL::asset('js/jquery.js')}}"></script>
		<script src="{{URL::asset('js/bootstrap.min.js')}}"></script>
		<script src="{{URL::asset('js/underscore.js')}}"></script>
		<script src="{{URL::asset('js/angular.min.js')}}"></script>
		<script src="{{URL::asset('js/weinspire/sockjs-0.3.min.js')}}"></script>
		<script src="{{URL::asset('js/weinspire/connection.js')}}"></script>
		
		<script>
			@if(isset($subscriptions))
			@foreach ($subscriptions as $key => $subscription)
				@if($subscription === null)
					connection.subscribe("{{$key}}");
				@else
					connection.subscribe("{{$key}}",{{json_encode($subscription)}});
				@endif
			@endforeach
			@endif

			var WIApp = angular.module('WIApp', [], function($interpolateProvider) {
			    $interpolateProvider.startSymbol('[[');
			    $interpolateProvider.endSymbol(']]');
			});

			WIApp.filter('reverse', function() {
 				 return function(items) {
					var items_new = $.map(items, function(value, index) {
   						 return [value];
					});
	
					items_new = items_new.slice().reverse();
					items_new.forEach(function(value, index) {
						if(value.created_at.length > 11)
							value.created_at = value.created_at.substring(11);
					});
					//console.log(items_new);
					return items_new;
				  };
			});	
			WIApp.controller('WICollectionCtrl',function($scope,$http){
				$scope.collections = connection.collections;
			
				/*$scope.create = function(url){
					$http.get(url);
				}*/

				$scope.create = function(url){
					var obj = JSON.parse(JSON.stringify($scope.chat));
					$http.post(url,obj);
					$scope.chat.message = "";
				}

				$scope.update = function(url,id){
					$http.get(url+id);
				}

				$scope.delete = function(url,id){
					$http.get(""+url+""+id);
				}
				
				connection.on('chats', function(){
					console.log("Documents updated");
					console.log($scope.collections);
					$scope.$apply();
					//$scope.collections = connection.collections;
					//console.log($scope.collections);
				});
			});

			connection.on("connected", function() {
				console.log("connected");
			});



		</script>

		@yield('scripts')
	</body>
</html>
