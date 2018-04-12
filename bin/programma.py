#!/usr/bin/python
#####################################################################
traccia = True	# Change to False to disable debug verbose output
#####################################################################
# This program is free software: you can redistribute it and/or
# modify it under the terms of the GNU General Public License as
# published by the Free Software Foundation, either version 3 of the
# License, or (at your option) any later version.
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>
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
## Part 1: init
import RPi.GPIO as GPIO		# port control
import sys
from time import sleep

# Setup GPIO operations
GPIO.setwarnings(False)
GPIO.setmode(GPIO.BCM)		# at you opinion

for porta in porte:		# prepare each port to receive commands
	GPIO.setup(porta, GPIO.OUT)
#####################################################################
## part 2: functions
def dprint(messaggio):		# verbose print debug informations
	"""This will print out a status message, unless verbose debug is
	   disabled (traccia = False)
	   Useful during debugging
	"""
	if traccia:		# only if verbose is enabled
		print messaggio
	return	#/dprint

def die(errore, uscita=255):	# error message and exit
	"""This will terminate the program and exit to system with an
	   error code.
	   first parameter (errore) is mandatory: a string describing
	   the error.
	   second parameter is the exit code to be passed to sys.exit
	   (default is 255)
	"""
	print errore
	sys.exit(uscita)	#/die	# no need for return

def aspetta(quanto = 1):	# better sleep
	dprint("(waiting for %d)" % quanto)
	sleep(quanto)
	return	#/aspetta
#####################################################################
## part 3: GPIO commands
def accendi(quali):
	"Turn on relay, if not on already"
	for quale in quali:	# array or tuple of ports, might be just one
		if quale in porte:	# is it a valid port?
			attuale = GPIO.input(quale)		# read its status
			if attuale:		# is off, turn it on
				dprint("accendi(): port %s" % str(quale))
				GPIO.output(quale,0)		# off: switch it on
	return	#/accendi

def spegni(quali):
	"Turn off relay, if not off already"
	for quale in quali:	# array or tuple of ports, even one element
		if quale in porte:	# is it a valid port?
			attuale = GPIO.input(quale)		# read its status
			if not attuale:	# is on, turn it off
				dprint("spegni(): port %s" % str(quale))
				GPIO.output(quale,1)		# on: switch it off
	return	#/spegni

def pulsante(quali):
	"""Momentary switch: operates a relay on, then off after
       $interpasso usec
	   will also wait half the $interpasso time after switching off,
	   to ensure the system has really turned it off
	"""
	# accept tuple or array, give it to accendi() and spegni()
	accendi(quali)		# Turn on
	aspetta(interpasso)	# wait
	spegni(quali)		# Turn off
	aspetta(interpasso/2)		# forced to wait for status change
	return	#/pulsante

def commuta(quali):
	"""Fixed switch, change the status of one or more ports
	"""
	for quale in quali:		# accept tuple or array, operate with each one
		if quale in porte:	# if one is not a valid port, will just ignore
			attuale = GPIO.input(quale)	# status
			nuovo = not attuale			# inverted status
			GPIO.output(quale,nuovo)	# go ahead
			aspetta(interpasso/2)		# forced for acknowledgement
		else:	# won't die, just report it
			dprint("Not a valid port: %s" % str(quale))
	return	#/commuta
#####################################################################
## part 4: set mode and read program
## You should call the program as programma <mode> <file>
## <mode> (mandatory) can either be:
##	c	operate as switch (every time is called, change state)
##	p	operate as momentary/pulse (turn on, then off immediately)
##	s	simulate operation (debug: will do everything except GPIO)
## <file> (default? DefPrg) is the program file
##	is a .pyc module with a tuple describing the operations

if len(sys.argv)>1:	# <mode> is mandatory
	cosafare = sys.argv[1]
	if cosafare == "c":		# Switch
		dprint("Working as SWITCH")
	elif cosafare == "p":	# Momentary
		dprint("Working as MOMENTARY")
	elif cosafare == "s":	# Simulation
		dprint("Working as SIMULATION (NO ACTUAL PILOTING)")
	else:					# Unknown mode
		die("Please use 'c' for Switch, 'p' for Momentary or 's' for Simulation",127)
else:	# no mode at all /* can be changed to assume a default mode */
	die("Missing mandatory parameter 'c', 'p' or 's'",63)

if len(sys.argv)>2:	# <file> is optional (default: DefPrg)
	qualeprogramma = sys.argv[2]
else:
	qualeprogramma = "DefPrg"	# indeed
dprint("Program file: %s" % qualeprogramma)		# after determining which is

try:	# if program not exists, dies
	prg = __import__(qualeprogramma)
	dprint("Imported sequence: %s" % str(prg.programma))
except ImportError as error:
	die("Unable to load program file: {0}".format(error.message[16:]))
#####################################################################
## part 5: main loop
ripeti = int(prg.programma[0])	# first instruction: number of repetitions
if ripeti>1:	# Repeat more times
	dprint("Number or repetitions: %d" % ripeti)
elif ripeti==1:	# Just one execution
	dprint("Execute program one time")
elif ripeti==0:
	dprint("Don't execute the program at all")
else			# is -1
	dprint("Infinite loop")

programma = prg.programma[1:]	# rest of tuple is the program
dprint("Program sequence: %s" % str(programma))

totali = len(prg.programma)-1	# total number of instructions (except repeat)
dprint("Program length: %d" % totali)

rptz = ripeti					# total number of repetitions, for counting

## loop
while ripeti != 0:		# if is -1, will be infinite
	if ripeti>0:	# where are we?
		ora=rptz-ripeti+1	# gives actual cycle in ascending order
		dprint("Repetition no: %d of %d" % (ora,rptz))
	else:	# if we're in an infinte loop
		dprint("Infinite loop (re)starting")
	try:	# catch ctrl-c on infinite loops
		i=0	# reset instruction number
		for riga in programma:	# reading single line of program
			i+=1	# doing instruction X of the program
			attendi=int(riga[-1])	# last value is the pause after commands
			dprint("Row %d of %d: %s (then wait: %s)" % (i,totali,str(riga[:-1]),attendi))
			if cosafare=="p":
				pulsante(riga[:-1])
			elif cosafare=="c":
				commuta(riga[:-1])
			elif cosafare=="s":
				dprint(" #Simulating on %s" % str(riga[:-1]))
			if attendi>0:	# always pause
				aspetta(attendi)

		if ripeti > 0:	# if neither zero nor negative, one cicle is done
			ripeti-=1
	except KeyboardInterrupt:
		die("Done: Exiting on Ctrl-C",31)
