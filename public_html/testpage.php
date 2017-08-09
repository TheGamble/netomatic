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
					<div class="alert alert-danger alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
						<strong>Warning!</strong> Net-O-Matic has some issues with its code that we need to work out. Please understand that all results are suspect and should be subject to review before use in live networks!
					</div>
					<div class="col-md-4">
						<div class="panel panel-info">
							<div class="panel-body">
								<h3>Input</h3>
								<textarea class="form-control" name="input" id="input" rows="20" cols="30" placeholder="172.16.0.0/23&#013;&#010;172.18.0.0/22&#013;&#010;172.24.0.0/26..."></textarea>
								<p>Note: Any addresses not in CIDR notation will be treated as host addresses (/32)</p>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="panel panel-info">
							<div class="panel-body">
								<h3>Options</h3>
								<p>Mask Notation</p>
								<div class="radio">
									<label>
										<input type="radio" name="notation" id="notationCIDR" value="1" aria-label="CIDR" checked>
										CIDR (169.254.254.128/24)
									</label>
								</div>
								<div class="radio">
									<label>
										<input type="radio" name="notation" id="notationNetmask" value="2" aria-label="Netmask">
										Netmask (169.254.254.128 255.255.255.0)
									</label>
								</div>
								<div class="radio">
									<label>
										<input type="radio" name="notation" id="notationWildcard" value="3" aria-label="Wildcard">
										Wildcard (169.254.254.128 0.0.0.255)
									</label>
								</div>
								<div class="input-group">
									<span class="input-group-addon" id="basic-addon3">169.254.254.128/</span>
									<select name="minNetwork" id="minNetwork" class="form-control">
										<option>0</option>
										<option>1</option>
										<option>2</option>
										<option>3</option>
										<option>4</option>
										<option>5</option>
										<option>6</option>
										<option>7</option>
										<option>8</option>
										<option>9</option>
										<option>10</option>
										<option>11</option>
										<option>12</option>
										<option>13</option>
										<option>14</option>
										<option>15</option>
										<option>16</option>
										<option>17</option>
										<option>18</option>
										<option>19</option>
										<option>20</option>
										<option>21</option>
										<option>22</option>
										<option>23</option>
										<option>24</option>
										<option>25</option>
										<option>26</option>
										<option>27</option>
										<option>28</option>
										<option>29</option>
										<option>30</option>
										<option>31</option>
										<option selected>32</option>
									</select>
								</div>
								<hr>
								<p>Prefix and Suffix<p>
								<div class="input-group">
									<span class="input-group-addon">
										<input type="checkbox" name="outputPrefix" id="outputPrefix">
									</span>
									<input type="text" class="form-control" name="outputPrefixEntry" id="outputPrefixEntry" aria-label="Output Prefix" placeholder="Prefix...">
								</div>
								<br>
								<div class="input-group">
									<span class="input-group-addon">
										<input type="checkbox" name="outputSuffix" id="outputSuffix">
									</span>
									<input type="text" class="form-control" name="outputSuffixEntry" id="outputSuffixEntry" aria-label="Output Suffix" placeholder="Suffix...">
								</div>
								<hr>
								<button class="btn btn-primary btn-lg btn-block" type="submit">Calculate</button>
							</div>
						</div>
						<p>Note: Spearheaded by Brian.</p>
					</div>
					<div class="col-md-4">
						<div class="panel panel-success">
							<div class="panel-body">
								<h3>Output</h3>
								<textarea class="form-control" name="output" id="output" rows="20" cols="30"></textarea>
							</div>
						</div>
					</div>
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
