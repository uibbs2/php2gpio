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

	(0,2), # Port 0: won't operate any relais, will just wait for X seconds

	(16,1),
	(12,3),
	(12,2),
	(16,0),

)#####################################################################

