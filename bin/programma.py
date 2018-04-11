#!/usr/bin/python
#####################################################################
##### CONFIGURATION
porte = (		# list of GPIO ports connected to a relay bank
	 2,  3,  4, 17, 27, 22, 10,  9,		# first bank
	11,  5,  6, 13, 19, 26, 21, 20,		# second
	12, 16,  7,  8, 25, 24, 23, 18,		# third
)

corrispondenza = [		# Name of the item connected to each relay
	'(nulla)','',
	'Terrazza',	'Bagno',	'Camera',	'Lavanderia',	'Cucina',	'Prato',
	'Piscina',	'Cameretta',	'Balcone',	'Bagno',	'Patio',	'Salone',
	'','',
	'Cancelletto',	'Armadio',	'Tombe',	'Sottoscala',	'Ripostiglio',
	'Scala',	'Veranda',	'Gazebo',	'Idromassaggi',	'Orto',	'Porta',
	'Corridoio',
]

# pause between on and off in momentary operation
interpasso = 0.25

# pause between status
attesa = 1

# DEBUG: write informations
traccia = True

##### END CONFIGURATION
#####################################################################
import RPi.GPIO as GPIO
import sys
from time import sleep

GPIO.setwarnings(False)
GPIO.setmode(GPIO.BCM)

# Prepare each port to receive commands
for porta in porte:
	GPIO.setup(porta, GPIO.OUT)

#####################################################################
def accendi(quale):
	"Turn on a relay, if not on already"
	attuale = GPIO.input(quale)		# status?
	if attuale:
		GPIO.output(quale,0)		# off: switch it on
	return

def spegni(quale):
	"Turn off a realy, if not off already"
	attuale = GPIO.input(quale)		# status?
	if not attuale:
		GPIO.output(quale,1)		# on: switch it off
	return
#####################################################################
def pulsante(quale):
	"Momentary switch: operates a relay on, then off after $interpasso usec"
	# if quale is not a valid port, will just return
	if quale in porte:
		accendi(quale)
		sleep(interpasso)
		spegni(quale)
		sleep(interpasso/2)		# forced to wait for status change
	return

def commuta(quale):
	"Change relay status from on to off or viceversa"
	# if quale is not a valid port, will just return
	if quale in porte:
		attuale = GPIO.input(quale)
		nuovo = not attuale
		GPIO.output(quale,nuovo)
		sleep(interpasso/2)		# forced to wait for status acknowledge
	return
#####################################################################
def dprint(messaggio):		# print a message if debug is true
	if traccia:
		print messaggio
	return
#####################################################################
##### main

## action is mandatory
if len(sys.argv)>1:
	cosafare = sys.argv[1]
	if cosafare == "c":		# Switch
		dprint("Commutazione di stato (interruttori)")
	elif cosafare == "p":	# Momentary
		dprint("Accensione temporanea (pulsanti)")
	else:					# Unknown command
		print "INDICARE c per commuta oppure p per pulsa"
		sys.exit(255)
else:	# no command at all
	print "INDICARE OBBLIGATORIAMENTE c per commuta oppure p per pulsa"
	sys.exit(255)

## program file is optional (AFTER p or c), otherwise will assume DefPrg.py
if len(sys.argv)>2:
	qualeprogramma = sys.argv[2]
else:					# Non indicato, assumi quello di default
	qualeprogramma = "DefPrg"
dprint("Programma usato: %s" % (qualeprogramma))

## if program not exists, dies
try:
	prg = __import__(qualeprogramma)
except ImportError as error:
	print "Impossibile caricare il programma: {0}".format(error.message[16:])
	sys.exit(255)

# prepara il programma assiemando gli array
dprint(prg.programma)		# debug
dprint(len(prg.programma))	# debug: size?

i = 0	# counter of instructions
j = len(prg.programma)		# total number of instructions
# main foreach cicle
for rele,pausa in prg.programma:
	i += 1		# doing instruction X
	dprint("%d di %d: Tocco %s, poi attendo %d" % (i,j,corrispondenza[rele], pausa))
	if cosafare == "p":
		pulsante(rele)
	elif cosafare == "c":
		commuta(rele)
	# pause is always passed, so "(0,xxx)," will just pause for xxx usec
	if pausa>0:
		sleep(pausa)

