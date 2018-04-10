<?php (include 'config.php') or die("<h1>Configuration not found!</h1>");
#$inizio = microtime(true);		# DEBUG: measure execution time
#######################################################################
## main functions
##

function pulsante($linea) {
# Activate a relais as momentary
	global $accendi,$spegni,$interpasso;
	exec("$accendi $linea");	# turn on
	usleep($interpasso);		# wait
	exec("$spegni $linea");		# turn off
} #/pulsante


/*************************************************************************
 * RIGHT NOW WE HAVE JUST THIS
 *************************************************************************/

## has been requested a change via Post? Operate it!
if ($_POST['quale'] != ""){
	pulsante($_POST['quale']);    # per ora solo pulsante
} #endif.quale

## has been request made via ajax? Stop here
if ($_POST['risorsa'] == "ajax") die(json_encode(array("success"=>1,$_POST)));

# Otherwise, give a HTML5 interface
?><!doctype html>
<html lang="it">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<title>Controllo interruttori</title>
</head>
<!-- -->
<body>
	<div class="container">
		<div class="page-header">
			<h1 class="display-4">Pulsanti</h1><?php /*
			<p class="lead">Controllo rele via raspberry</p> */ ?>
		</div><!-- /div.page-header -->

		<form id="principale" class="comandi" action="principale.php" method="post">
			<table class="table table-sm">
				<thead>
					<tr><!-- Descriptions, should come from PHP itself -->
						<th scope="col">Sopra</th>
						<th scope="col">Sotto</th>
						<th scope="col">Giardino</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><!-- First column -->
<?php foreach ($quali1 as $key => $value): ?>
							<p><button type="submit" name="quale" value="<?php echo $key ?>" class="btn btn-secondary btn-sm"><?php echo $value[0] ?></button></p>
<?php endforeach ?>
						</td>
						<td><!-- Second column -->
<?php foreach ($quali2 as $key => $value): ?>
							<p><button type="submit" name="quale" value="<?php echo $key ?>" class="btn btn-secondary btn-sm"><?php echo $value[0] ?></button></p>
<?php endforeach ?>
						</td>
						<td><!-- Terza colonna: esterno -->
<?php foreach ($quali3 as $key => $value): ?>
							<p><button type="submit" name="quale" value="<?php echo $key ?>" class="btn btn-secondary btn-sm"><?php echo $value[0] ?></button></p>
<?php endforeach ?>
						</td>
					</tr>
				</tbody>
			</table>
		</form><!-- /form.principale -->
	</div><!-- /container -->

	<!-- Javascript: first primo jQuery, then Popper, finally Bootstrap -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

	<!-- local javascript -->
	<script>

		$(document).ready(function(){
			$('#principale').on('click', 'button', function(e){
				e.preventDefault(); // Blocca esecuzione form

				valore=$(this).attr("value"); // quale bottone è stato premuto?

				$.post( "principale.php",
				  {"quale":valore, "risorsa":"ajax"}, // Lo script capirà se ha ricevuto dati da risorsa ajax o html
				  function(data) {	// #NON FARÀ NULLA # TUTTO COMMENTATO AL MOMENTO!
					// console.log(data); // per vedere il risultato risposto dalla pagina
					data=$.parseJSON(data); // per trasformarlo in json
					//if (data.success) // lato server valorizzo la variabile success
					//	console.log("ok");
					//else
					//	console.log("ko");
				}); // $.post
			}); // $('#principale')
		}); // $(document)

	</script>
</body>
</html>
<?php
#$fine = microtime(true);$time = $fine - $inizio;print $time;	# durata
