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
	return shell_exec("$spegni $linea");		# turn off
} #/pulsante

function stato($linea) {
# Tries to understand if a line is on or off
	global $stato;
	# rtrim is mandatory to remove trailing newline
	if (rtrim(shell_exec("$stato $linea"))=="True"){
		return True;
	} else {
		return False;
	}
}
/*************************************************************************
 * RIGHT NOW WE HAVE JUST THIS
 *************************************************************************/

## has been requested a change via Post? Operate it!
if ($_POST['quale'] != ""){
	global $risultato;
	$risultato = pulsante($_POST['quale']);    # per ora solo pulsante
} #endif.quale

## has been request made via ajax? Stop here
if ($_POST['risorsa'] == "ajax") die(json_encode(array("success"=>$risultato)));

# Otherwise, give a HTML5 interface
?><!doctype html>
<html lang="it">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<title><?=$pagina_titolo ?></title>
</head>
<body>
	<div class="container">
		<div class="page-header">
			<h1 class="display-4"><?=$pagina_titolo ?></h1>
<?php if($pagina_subtit): ?>
			<p class="lead"><?=$pagina_subtit ?></p>
<?php endif ?>
		</div><!-- /div.page-header -->

		<form id="principale" class="comandi" action="<?=$_SERVER['PHP_SELF'] ?>" method="post">
			<table class="table table-sm">
				<thead>
					<tr>
<?php foreach($disponibili as $posizione=>$nul): ?>
						<th scope="col"><?=$posizione ?></th>
<?php endforeach ?>
					</tr>
				</thead>
				<tbody>
					<tr>
<?php foreach($disponibili as $nul=>$banco): ?>
						<td><!-- column <?=$nul ?> -->
<?php foreach ($banco as $key=>$value): ?>
							<p><button type="submit" name="quale" value="<?=$key ?>" class="btn btn-secondary btn-sm"><?php echo $value[1] ?></button></p>
<?php endforeach /* $banco as $key=>$value */ ?>
						</td>
<?php endforeach /* $disponibili as $nul=>$banco */ ?>
					</tr>
				</tbody>
			</table>
		</form><!-- /form.principale -->
	</div><!-- /container -->

	<!-- Javascript: first jQuery, then Popper, finally Bootstrap -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

	<!-- local javascript -->
	<script>

		$(document).ready(function(){
			$('#principale').on('click', 'button', function(e){
				e.preventDefault(); // Blocca esecuzione form

				valore=$(this).attr("value"); // quale bottone è stato premuto?

				$.post("<?=$_SERVER['PHP_SELF'] ?>",
				  {"quale":valore,"risorsa":"ajax"}, // Lo script capirà se ha ricevuto dati da risorsa ajax o html
				  function(data) {	// #NON FARÀ NULLA # TUTTO COMMENTATO AL MOMENTO!
				  	// dovrebbe cambiare classe del bottone premuto
					// console.log(data); // per vedere il risultato risposto dalla pagina
					data=$.parseJSON(data); // per trasformarlo in json
					console.log(data.success)
					//else
						//console.log("ko");
				}); // $.post
			}); // $('#principale')
		}); // $(document)

	</script>
</body>
</html>
<?php	#$fine = microtime(true);$time = $fine - $inizio;print $time;	# durata
