#!/usr/bin/python
#####################################################################
## Configurations
#interpasso_default=0.125	# if not interstep is given

#####################################################################
import RPi.GPIO as GPIO
import sys
from time import sleep		# for pauses

GPIO.setwarnings(False)
GPIO.setmode(GPIO.BCM)		# Change accordingly, if needed!
#####################################################################
def portinit(quale):		# Prepare the port to operate
	GPIO.setup(quale,GPIO.OUT)
	return
#####################################################################
def accendi(quale):
	attuale = GPIO.input(quale)		# stato del rele?
	if attuale:
		GPIO.output(quale,0)		# acceso: spegnilo
		return True
	else:
		return False

def spegni(quale):
	attuale = GPIO.input(quale)		# stato del rele?
	if not attuale:
		GPIO.output(quale,1)		# spento: accendilo
		return True
	else:
		return False

def stato(quale):
	if not GPIO.input(quale):
		return True			# acceso
	else:
		return False		# spento

def commuta(quale):
	attuale = GPIO.input(quale)
	nuovo = not attuale
	GPIO.output(quale,nuovo)
	return nuovo
#####################################################################
## Main cicle
if len(sys.argv)<2:
	print """usage: %s <mode> <port>

  <mode>  can be
    st  just return port status (1:on, 0:off)
    on  turn on
    of  turn off
    co  change port status

  <port> is GPIO port number
""" % sys.argv[0]
	sys.exit(255)
else:
	comando=sys.argv[1]		# required mode to operate
	porta=int(sys.argv[2])	# required port

## Debug
#print "Comando: %s - Porta: %d" % (comando,porta)
#####################################################################
portinit(porta)		# Mandatory

if comando=="st":	# Just report status
	print stato(porta)
elif comando=="on":
	print accendi(porta)
elif comando=="of":
	print spegni(porta)
elif comando=="co":
	print commuta(porta)
else:
	print "ERROR"
	sys.exit(255)

