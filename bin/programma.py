#!/usr/bin/python
#####################################################################
##### CONFIGURAZIONE DEL SISTEMA
# elenco delle porte GPIO a cui sono collegati i rele
porte = (
	 2,  3,  4, 17, 27, 22, 10,  9,		# primo banco: da uno a otto
	11,  5,  6, 13, 19, 26, 21, 20,		# secondo banco: da uno a otto
	12, 16,  7,  8, 25, 24, 23, 18,		# terzo banco: da uno a otto
)

corrispondenza = [
	'(nulla)','',
	'Terrazza',	'Bagno',	'Camera',	'Lavanderia',	'Cucina',	'Prato',
	'Piscina',	'Cameretta',	'Balcone',	'Bagno',	'Patio',	'Salone',
	'','',
	'Cancelletto',	'Armadio',	'Tombe',	'Sottoscala',	'Ripostiglio',
	'Scala',	'Veranda',	'Gazebo',	'Idromassaggi',	'Orto',	'Porta',
	'Corridoio',
]

# pausa fra l'azione su una porta e la successiva
interpasso = 0.25

# pausa fra gli stati
attesa = 1

##### FINE CONFIGURAZIONE
#####################################################################
import RPi.GPIO as GPIO
import sys
from time import sleep

GPIO.setwarnings(False)
GPIO.setmode(GPIO.BCM)

for porta in porte:
	GPIO.setup(porta, GPIO.OUT)
#####################################################################
def accendi(quale):
	"Accende un rele, se questo non e' gia' acceso"
	attuale = GPIO.input(quale)		# stato del rele?
	if attuale:
		GPIO.output(quale,0)		# acceso: spegnilo
	return

def spegni(quale):
	"Spegne un rele, se questo non e' gia' spento"
	attuale = GPIO.input(quale)		# stato del rele?
	if not attuale:
		GPIO.output(quale,1)		# spento: accendilo
	return

#####################################################################
def pulsante(quale):
	"Effettua procedura pulsante: accendi i rele richiesti e spegnili dopo $interpasso secondi"
	if quale in porte :
		accendi(quale)
	sleep(interpasso)
	if quale in porte :
		spegni(quale)
	sleep(interpasso/2)		# Per forzare il cambio di stato del rele
	return

def commuta(quale):
	"Cambia lo stato di un rele, quando sono interruttori"
	attuale = GPIO.input(quale)
	nuovo = not attuale
	GPIO.output(quale,nuovo)
	sleep(interpasso)
	return

#####################################################################
##### main

## obbligatorio indicare azione dei rele
if len(sys.argv)>1:
	cosafare = sys.argv[1]
	if cosafare == "c":
		print "Commutazione di stato (interruttori)"
	elif cosafare == "p":
		print "Accensione temporanea (pulsanti)"
	else:
		print "INDICARE c per commuta oppure p per pulsa"
		sys.exit(255)
else:
	print "INDICARE OBBLIGATORIAMENTE c per commuta oppure p per pulsa"
	sys.exit(255)

## facoltativo indicare il percorso del programma
if len(sys.argv)>2:		# Indicato come secondo parametro il file
	qualeprogramma = sys.argv[2]
	print ("Programma usato: %s" % (qualeprogramma))
else:					# Non indicato, assumi quello di default
	qualeprogramma = "DefPrg"

# Prova a leggere il programma, o fallisce
try:
	prg = __import__(qualeprogramma)
except ImportError as error:
	print "Impossibile caricare il programma: {0}".format(error.message[16:])
	sys.exit(255)

# prepara il programma assiemando gli array
print prg.programma

for rele,pausa in prg.programma:
	print ("Tocco %s, poi attendo %d" % (corrispondenza[rele], pausa))
	if cosafare == "p":
		pulsante(rele)
	elif cosafare == "c":
		commuta(rele)

	if pausa>0:
		sleep(pausa)

