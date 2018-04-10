<?php if(count(get_included_files()) ==1) die("<h1>You can't access directly</h1>");
/*************************************************************************
 **                                                                     **
 ** SYSTEM CONFIGURATION - Kindly verify values in this area            **
 **                                                                     **
 *************************************************************************/

## Relais settings: three banks of eight relais
# each is an array of ID => [ "name", is_momentary ]
# ID is the GPIO channel unique ID
# "name" is just the identification of the single relay
# is_momentary is TRUE if operation is "turn on, wait, turn off"
#                 FALSE if operation "turn on" or "turn off"
##### RIGHT NOW ALL ARE MOMENTARY
$quali1 = [		# First bank
	2 => [ "Terrace", TRUE],
	3 => [ "Bathroom", TRUE],
	4 => [ "Master BR", TRUE],
	17 => [ "Closet", TRUE],
	27 => [ "Hallway", TRUE],
	22 => [ "Porch", TRUE],
	10 => [ "Balcony", TRUE],
	9 => [ "Kids BR", TRUE],
  ];

$quali2 = [		# Second bank
	11 => [ "2nd Bath", TRUE],
	5 => [ "Launderette", TRUE],
	6 => [ "Kitchen", TRUE],
	13 => [ "Living", TRUE],
	19 => [ "Under stairs", TRUE],
	26 => [ "Entrance door", TRUE],
	21 => [ "Stairs", TRUE],
	20 => [ "Pantry", TRUE],
];


$quali3 = [		# third bank
	12 => [ "Patio", TRUE],
    16 => [ "Gate", TRUE],
    7 => [ "Lawn", TRUE],
    8 => [ "Pool", TRUE],
    25 => [ "Garden", TRUE],
    24 => [ "Crypt", TRUE],
    23 => [ "Gazebo", TRUE],
    18 => [ "Tombs", TRUE],
  ];

/*************************************************************************
 *****                                                               *****
 ***** FROM THIS POINT ONLY MINOR MODIFICATIONS - OR LEAVE THEM      *****
 *****                                                               *****
 *************************************************************************/

# External pilots: them have to be executable
# for Raspbian each will be "sudo /path/to/bin/command.py"
$accendi	= "/home/www/bin/turnon.py";		# Turn ON
$spegni		= "/home/www/bin/turnoff.py";		# Turn OFF
$commuta	= "/home/www/bin/switch.py";		# Invert state
$stato		= "/home/www/bin/status.py";		# Read state

# some local vars
$interpasso	=	200000;		# (uSec) momentary pause between on and off

/* # Excluded at the moment

$modalita = [		# button class for switches, different for ON and OFF
	0 => "danger",
	1 => "secondary",
];
*/
