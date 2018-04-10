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

GPIO.setwarnings(False)
GPIO.setmode(GPIO.BCM)

for porta in porte:
	GPIO.setup(porta, GPIO.OUT)

if len(sys.argv) > 1:
	for parametro in sys.argv[1:] :
		if int(parametro) in porte :
			attuale = GPIO.input(int(parametro))
			nuovo = not attuale
			GPIO.output(int(parametro),nuovo)

