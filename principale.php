<?php (include 'config.php') or die("<h1>Configuration not found!</h1>");
#$inizio = microtime(true);		# DEBUG: measure execution time
#######################################################################
## main functions
##

function accendi($linea){
	global $accendi;
	if (rtrim(shell_exec("$accendi $linea"))=="True"){
		return True;
	} else {
		return False;
	}
}

function spegni($linea){
	global $spegni;
	if (rtrim(shell_exec("$spegni $linea"))=="True"){
		return True;
	} else {
		return False;
	}
}

function pulsante($linea){
	global $interpasso,$singolo;
	if (!$singolo[$linea][0]) {		# fail if not momentary
		return False;
	} else {	##### Right now no test whatsoever
		accendi($linea);
		usleep($interpasso);
		spegni($linea);
		return True;
	}
} #/pulsante

function interruttore($linea){
	global $singolo,$commuta;
	if ($singolo[$linea][0]) {		# fail if momentary
		return False;
	} else {	##### Right now no test whatsoever
		if (rtrim(shell_exec("$commuta $linea"))=="True"){
			return True;
		} else {
			return False;
		}
	}

}

function stato($linea) {		# read relay status when not momentary
# Tries to understand if a line is on or off
	global $stato;
	# rtrim is mandatory to remove trailing newline
	if (rtrim(shell_exec("$stato $linea"))=="True"){
		return True;
	} else {
		return False;
	}
}

##########################################################################
function agisci($linea){
	global $singolo;
	if($singolo[$linea][0]){	# Momentary
		return pulsante($linea);
	} else {
		return interruttore($linea);
	}
}

##########################################################################

## Now will prepare an array, defining which class should have every button
##
$singolo = array();
foreach ($disponibili as $quali) {
	foreach ($quali as $key => $value) {
		$singolo[$key] = $value;
		if (!$value[0]) {		# if is a switch
			if (stato($key)) {	# its status ON?
				$singolo[$key]['class'] = "btn-info";
			} else {
				$singolo[$key]['class'] = "btn-outline-dark";
			}
		} else {				# is a momentary button
			$singolo[$key]['class'] = "btn-secondary";
		}
	}
}

/*************************************************************************
 * RIGHT NOW WE HAVE JUST THIS
 *************************************************************************/

## has been requested a change via Post? Operate it!
if ($_POST['quale'] != ""){
	global $risultato;
	$risultato = agisci($_POST['quale']);    # per ora solo pulsante
} #endif.quale

## has been request made via ajax? Stop here
if ($_POST['risorsa']=="ajax") die(json_encode(array("success"=>$risultato,"quale"=>$_POST['quale'])));

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
							<p><button type="submit" name="quale" id="B<?=$key ?>" value="<?=$key ?>" class="btn <?=$singolo[$key]['class'] ?> btn-sm"><?=$value[1] ?></button></p>
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
					console.log(data.success);
					console.log(data.quale);
					// $("#B").removeClass("btn").addClass("btn-info");
					//else
						//console.log("ko");
				}); // $.post
			}); // $('#principale')
		}); // $(document)

	</script>
</body>
</html>
<?php	#$fine = microtime(true);$time = $fine - $inizio;print $time;	# durata
