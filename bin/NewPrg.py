programma = ( ##### PROGRAMMAZIONE RELE
##### SINTASSI:
## Ogni riga nella sintassi OBBLIGATORIA:
## <tab>(rele[,rele,rele], pausa),
## Usare "#" per i commenti
## Attiva il rele, poi aspetta per pausa; usare 0 per saltare la pausa
## Cominciare il programma con (0,pausa), per forzare un'attesa iniziale
##
######################################################################

	1,		# (MANDATORY) Number of repetitions: 1-X or -1 for infinite loop

	(0, 5)	# Port 0: won't operate any relais, will just wait for X seconds

	(7,8,24,25,16, 2),	# multiple relays, then 2 seconds pause

	(24,3),	(24,0),	(25,3),	(16,1),
	(25,1),	(25,0),	(16,0),	(23,3),
	(8,0),	(18,4),	(16,4),	(24,1),
	(12,3),	(8,4),	(7,2),	(12,0),
	(18,0),	(12,2),	(8,1),	(16,3),
	(25,2),	(24,4),	(23,2),	(7,3),
	(12,1),	(8,3),	(7,0),	(18,3),
	(23,1),	(7,1),	(18,1),	(23,0),

	(7,8,24,25,16, 2),	# multiple relays, then 2 seconds pause

)#####################################################################

