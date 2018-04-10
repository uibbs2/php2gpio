<?php if(count(get_included_files()) ==1) exit("<h1>Impossibile accedere</h1>"); # Impedisce accesso diretto
/*************************************************************************
 **                                                                     **
 ** CONFIGURAZIONE DEL SISTEMA - Verificare attentamente quest'area     **
 **                                                                     **
 *************************************************************************/

  # Banchi di rele
  # per ognuno abbiamo un array che comprende ID=>(Etichetta,Pulsante)
  # se lo stato Pulsante è True sarà attivato per un delay e disattivato
  #  altrimenti è un interruttore e sarà commutato
  ##### PER ORA SONO TUTTI PULSANTI
  $quali1 = [
    2 => [ "Terrazza", TRUE],
    3 => [ "Bagno", TRUE],
    4 => [ "Camera", TRUE],
	17 => [ "Armadio", TRUE],
    27 => [ "Corridoio", TRUE],
    22 => [ "Veranda", TRUE],
    10 => [ "Balcone", TRUE],
    9 => [ "Cameretta", TRUE],
  ];

  $quali2 = [
    11 => [ "Bagno", TRUE],
    5 => [ "Lavanderia", TRUE],
    6 => [ "Cucina", TRUE],
	13 => [ "Salone", TRUE],
    19 => [ "Sottoscala", TRUE],
    26 => [ "Porta", TRUE],
    21 => [ "Scala", TRUE],
    20 => [ "Ripostiglio", TRUE],
  ];


  $quali3 = [
	# terzo banco: da uno a otto
	12 => [ "Patio", TRUE],
    16 => [ "Cancelletto", TRUE],
    7 => [ "Prato", TRUE],
    8 => [ "Piscina", TRUE],
    25 => [ "Orto", TRUE],
    24 => [ "Idromassaggi", TRUE],
    23 => [ "Gazebo", TRUE],
    18 => [ "Tombe", TRUE],
  ];

/*************************************************************************
 *****                                                               *****
 ***** OLTRE QUESTO PUNTO SOLO MODIFICHE FACOLTATIVE E SE NECESSARIE *****
 *****                                                               *****
 *************************************************************************/

  # Variabili ad uso locale
  $interpasso = 200000;		# (uSec) per quanto lasciare acceso il relè pulsante

  # Pilotaggio rele: verificare il percorso e che siano eseguibili!
  $accendi	= "/home/www/bin/turnon.py";		# comando di accensione rele
  $spegni	= "/home/www/bin/turnoff.py";		# comando di spegnimento rele
  $commuta	= "/home/www/bin/switch.py";		# comando per commutare rele
  $stato	= "/home/www/bin/status.py";		# comando lettura stato rele

/*************************************************************************
 *****                                                               *****
 ***** NESSUNA MODIFICA NECESSARIA OLTRE QUESTO PUNTO                *****
 *****                                                               *****
 *************************************************************************/


  # Cambia il colore del bottone in base allo stato del rele
/*  $modalita = [
    0 => "danger",
    1 => "secondary",
  ];
*/

