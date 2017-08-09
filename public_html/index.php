<!DOCTYPE html>
<html>
	<head>
		<title>Net-O-Matic!</title>
		<link href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.6/darkly/bootstrap.min.css" rel="stylesheet">
		<!--<link href="/css/style.css" rel="stylesheet">-->
		<link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
		<link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
		<link rel="manifest" href="/manifest.json">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
		<meta name="theme-color" content="#ffffff">
		<meta name=viewport content="width=device-width, initial-scale=1">
		<style>
			textarea {
				border:1px solid #999999;
				width:100%;
				margin:5px 0;
				padding:3px;
			}
			h1.title {
				font-family: "Lobster", cursive;
			}
		</style>
	</head>
	<body>
		<div class="jumbotron">
			<div class="container">
				<h1 class="title">Net-O-Matic</h1>
				<p>Net-O-Matic summarizes and supernets networks in a bulk operation, to the largest common network of only explicitly entered networks. Use this for network advertisements!</p>
			</div>
		</div>
		<div class="container">
			<form id="netomatic">
				<div class="row">
					<div class="alert alert-danger" role="alert"><strong>Hey!</strong> Netomatic has been having a series of issues recently and is temporarily disabled. Once corrections have been made, it will be made accessible again.</alert>
				</div>
			</form>
		</div>
		<!-- Now that we got all the front-end out of the way, BEGIN CORE JS -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="/js/jquery.min.js"><\/script>')</script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		<script>
		$(document).ready(function(){
			var request;
			$("#netomatic").submit(function(event){
				if (request) {
					request.abort();
				}
				$("#output").html("Loading...");
				var $form = $(this);
				var $inputs = $form.find("input, select, button, option, textarea, checkbox, text");
				var serializedData = $form.serialize();
				$inputs.prop("disabled",true);
				$.post('submit.php', serializedData, function(data){
					$('#output').html(data);
					$inputs.prop("disabled",false);
				}).fail(function() {
					console.error("Posting Error");
					$inputs.prop("disabled",false);
				});
				event.preventDefault();
			});
		});
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
		ga('create', 'UA-64903913-3', 'auto');
		ga('send', 'pageview');
		</script>
		<!-- END OF CORE JS -->
	</body>
</html>
