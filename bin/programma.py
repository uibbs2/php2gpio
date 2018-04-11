#!/usr/bin/python
#####################################################################
traccia = True	# Change to False to disable debug verbose output
#####################################################################
##### CONFIGURATION #####
porte = (	# tuple of GPIO ports connected to relay bank(s)
	 2,  3,  4, 17, 27, 22, 10,  9,		# first bank
	11,  5,  6, 13, 19, 26, 21, 20,		# second
	12, 16,  7,  8, 25, 24, 23, 18,		# third
)	# Please modify accordingly

interpasso = 0.175	# pause between on and off in momentary operations

#corrispondenza = [	# Name of the item connected to each relay #DISABLED
#	'(nulla)','',
#	'Terrazza',	'Bagno',	'Camera',	'Lavanderia',	'Cucina',	'Prato',
#	'Piscina',	'Cameretta',	'Balcone',	'Bagno',	'Patio',	'Salone',
#	'','',
#	'Cancelletto',	'Armadio',	'Tombe',	'Sottoscala',	'Ripostiglio',
#	'Scala',	'Veranda',	'Gazebo',	'Idromassaggi',	'Orto',	'Porta',
#	'Corridoio',
#]

##### END CONFIGURATION ## NO MODIFICATIONS NEEDED BELOW THIS POINT #
#####################################################################
import RPi.GPIO as GPIO
import sys
from time import sleep

# Setup GPIO operations
GPIO.setwarnings(False)
GPIO.setmode(GPIO.BCM)

# and prepare each port to receive commands
for porta in porte:
	GPIO.setup(porta, GPIO.OUT)
#####################################################################
def dprint(messaggio):		# verbose print debug informations
	"Give debug informations about the status or where the program is"
	if traccia:		# only if verbose debug is enabled
		print messaggio
	return

def die(errore="Error", uscita=255):	# error message and exit
	"Will die (exit from script) in case of error"
	print errore
	sys.exit(uscita)
#####################################################################
def aspetta(quanto = 1):	# better sleep
	dprint("(waiting for %d)" % quanto)
	sleep(quanto)
	return
#####################################################################
def accendi(quali):
	"Turn on relay, if not on already"
	# quali is an array or tuple of ports, might also be just one element
	for quale in quali:
		if quale in porte:	# is it a valid port?
			attuale = GPIO.input(quale)		# its status?
			if attuale:		# is off, turn it on
				dprint("	accendi(): canale %s" % str(quale))
				GPIO.output(quale,0)		# off: switch it on
	return

def spegni(quali):
	"Turn off relay, if not off already"
	# same as accendi: array or tuple of ports, even one element
	for quale in quali:
		if quale in porte:	# is it a valid port?
			attuale = GPIO.input(quale)		# status?
			if not attuale:	# is on, turn it off
				dprint("	spegni(): canale %s" % str(quale))
				GPIO.output(quale,1)		# on: switch it off
	return
#####################################################################
def pulsante(quali):
	"Momentary switch: operates a relay on, then off after $interpasso usec"
	# accept tuple or array, give it to accendi() and spegni()
	accendi(quali)		# Turn on
	aspetta(interpasso)	# wait
	spegni(quali)		# Turn off
	aspetta(interpasso/2)		# forced to wait for status change
	return

def commuta(quali):
	"Change relay status from on to off or viceversa"
	# accept tuple or array, operate with each one
	for quale in quali:
		if quale in porte:	# if quale is not a valid port, will just ignore
			attuale = GPIO.input(quale)	# status
			nuovo = not attuale			# inverted status
			GPIO.output(quale,nuovo)	# go ahead
			aspetta(interpasso/2)			# forced to wait for status acknowledge
	return
#####################################################################
##### main

## action is mandatory
if len(sys.argv)>1:
	cosafare = sys.argv[1]
	if cosafare == "c":		# Switch
		dprint("Working as SWITCH")
	elif cosafare == "p":	# Momentary
		dprint("Working as MOMENTARY")
	elif cosafare == "s":	# Simulation
		dprint("Working as SIMULATION (NO ACTUAL PILOTING)")
	else:					# Unknown command
		die("Please use 'c' for Switch, 'p' for Momentary or 's' for Simulation",127)
else:	# no command at all
	die("Missing mandatory parameter 'c', 'p' or 's'",63)

## program file is optional (AFTER p, c or s), otherwise will assume DefPrg.py
if len(sys.argv)>2:
	qualeprogramma = sys.argv[2]
else:					# Non indicato, assumi quello di default
	qualeprogramma = "DefPrg"
dprint("Read program from file: %s" % qualeprogramma)

try:	## if program not exists, dies
	prg = __import__(qualeprogramma)
	dprint("Imported program: %s" % str(prg.programma))
except ImportError as error:
	die("Unable to load program file: {0}".format(error.message[16:]))

#####
# main foreach cicle

ripeti = int(prg.programma[0])	# first instruction: number of repetitions
programma = prg.programma[1:]	# rest of tuple is the program

j = len(prg.programma) - 1		# total number of instructions, except repeat
k = ripeti						# total number of repetitions, for counting

# let us know which is the program and its lenght
dprint("Program length: %d" % j)
dprint(" Actual program: %s" % str(programma))

# Repeat this cicle
while ripeti != 0:		# if is -1, will be infinite
	if ripeti>0:	# where are we?
		ora = k - ripeti + 1	# gives actual cycle in ascending order
		dprint("Repetition no: %d of %d" % (ora,k))
	else:	# if we're in an infinte loop
		dprint("Infinite loop (re)starting")
	try:		# catch ctrl-c on infinite loops
		i = 0	# reset instruction number
		for riga in programma:	# reading single line of program
			i += 1		# doing instruction X of the program
			attendi = int(riga[-1])	# last value is the pause after commands
			dprint("    Instruction %d of %d: %s - Wait: %s" % (i,j,str(riga[:-1]),attendi))
			if cosafare == "p":
				pulsante(riga[:-1])
			elif cosafare == "c":
				commuta(riga[:-1])
			elif cosafare == "s":
				dprint("    #Simulating on %s" % str(riga[:-1]))
			if attendi>0:	# pause is always passed
				aspetta(attendi)

		if ripeti > 0:	# if neither zero nor negative, one cicle is done
			ripeti -= 1
	except KeyboardInterrupt:
		die("Uscita su Ctrl-C",31)
