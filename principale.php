<?php (include 'config.php') or die("<h1>Configurazione non trovata</h1>");  # Modificare config!
#	$inizio = microtime(true);	# cronometra durata
/*************************************************************************
 *****                                                               *****
 ***** NESSUNA MODIFICA NECESSARIA OLTRE QUESTO PUNTO                *****
 *****                                                               *****
 *************************************************************************/

##
## Procedure principali
##

function pulsante($linea) {
# Attiva una linea relè come pulsante (accende, spegne dopo $interpasso microsec)
  global $accendi,$spegni,$interpasso;
  exec("$accendi $linea");
  usleep($interpasso);
  exec("$spegni $linea");
} #/pulsante



/*************************************************************************
 *************************************************************************/

# Se è stato inviato via form il numero di un rele, cambia il suo stato
if ( $_POST['quale'] != "" ) {
   pulsante($_POST['quale']);    # per ora solo pulsante
} #endif.quale

# Se la richiesta è arrivata in ajax, muore con sola risposta json, se no crea il form html
if ( $_POST['risorsa'] == "ajax" ) {
  die(json_encode(array("success"=>1, $_POST)));
}

# Altrimenti, costruisce il documento html
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
			<h1 class="display-4">Casa di Marco</h1><?php /*
			<p class="lead">Controllo rele via raspberry</p> */ ?>
		</div><!-- /div.page-header -->

		<form id="principale" class="comandi" action="principale.php" method="post">

			<table class="table table-sm"><!-- Bottoni in table -->
				<thead>
					<tr><!-- Descrizione delle tre colonne -->
						<th scope="col">Sopra</th>
						<th scope="col">Sotto</th>
						<th scope="col">Giardino</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><!-- prima colonna: sopra -->
<?php foreach ($quali1 as $key => $value) : ?>
							<p><button type="submit" name="quale" value="<?php echo $key; ?>" class="btn btn-secondary btn-sm"><?php echo $value[0]; ?></button></p>
<?php endforeach; ?>
						</td>
						<td><!-- seconda colonna: sotto -->
<?php foreach ($quali2 as $key => $value) : ?>
							<p><button type="submit" name="quale" value="<?php echo $key; ?>" class="btn btn-secondary btn-sm"><?php echo $value[0]; ?></button></p>
<?php endforeach; ?>
						</td>
						<td><!-- Terza colonna: esterno -->
<?php foreach ($quali3 as $key => $value) : ?>
							<p><button type="submit" name="quale" value="<?php echo $key; ?>" class="btn btn-secondary btn-sm"><?php echo $value[0]; ?></button></p>
<?php endforeach; ?>
						</td>
					</tr>
				</tbody>
			</table>
		</form><!-- /form.principale -->
	</div><!-- /container -->

	<!-- Funzionalità Javascript: obbligo primo jQuery, poi Popper, infine Bootstrap -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

	<!-- Javascript locale -->
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
#	$fine = microtime(true);	$time = $fine - $inizio;	print $time;	# durata
