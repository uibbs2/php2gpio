#!/usr/bin/python
#####################################################################
# elenco delle porte GPIO a cui sono collegati i rele
porte = (
	 2,  3,  4, 17, 27, 22, 10,  9,		# primo banco: da uno a otto
	11,  5,  6, 13, 19, 26, 21, 20,		# secondo banco: da uno a otto
	16, 12,  7,  8, 25, 24, 23, 18,		# terzo banco: da uno a otto
)
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
	"Accende un rele, se questo non è già acceso"
	attuale = GPIO.input(quale)		# stato del rele?
	if attuale:
		GPIO.output(quale,0)		# acceso: spegnilo
	return

def spegni(quale):
	"Spegne un rele, se questo non è già spento"
	attuale = GPIO.input(quale)		# stato del rele?
	if not attuale:
		GPIO.output(quale,1)		# spento: accendilo
	return

#####################################################################

if len(sys.argv) > 2:
	interpasso = float(sys.argv[1])

	for parametro in sys.argv[2:] :
		questa = int(parametro)
		if questa in porte :
			accendi(questa)
	sleep(interpasso)
	for parametro in sys.argv[2:] :
		questa = int(parametro)
		if questa in porte :
			spegni(questa)


