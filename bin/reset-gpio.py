#!/usr/bin/python
#####################################################################
# elenco delle porte GPIO a cui sono collegati i rele
porte = (
	 2,  3,  4, 17, 27, 22, 10,  9,		# primo banco: da uno a otto
	11,  5,  6, 13, 19, 26, 21, 20,		# secondo banco: da uno a otto
	12, 16,  7,  8, 25, 24, 23, 18,		# terzo banco: da uno a otto
)
#####################################################################

import RPi.GPIO as GPIO
import sys

GPIO.setwarnings(False)
GPIO.setmode(GPIO.BCM)

for porta in porte:
	GPIO.setup(porta, GPIO.OUT, initial=1)
	GPIO.output(porta, GPIO.HIGH)

