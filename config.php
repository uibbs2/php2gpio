<?php if(count(get_included_files()) ==1) die("<h1>You can't access directly</h1>");
/*************************************************************************
 **                                                                     **
 ** SYSTEM CONFIGURATION - Kindly verify values in this area            **
 **                                                                     **
 *************************************************************************/

$pagina_titolo = "Controllo pulsanti";				# H1 header
#$pagina_subtit = "Controllo rele via raspberry";	# lead paragraph
$pagina_subtit = FALSE;	# lead paragraph disabled

## Relais settings: three banks of eight relais
# each is an array of banks, in the form "Title" => array(bank)
#  each title will appear on the top of the button columns
# each bank is an array of port => [ is_momentary, "name" ]
#  port is the GPIO channel unique ID
#  "name" is just the identification of the single relay
#  is_momentary is TRUE if operation is "turn on, wait, turn off"
#                 FALSE if operation "turn on" or "turn off"

$disponibili=[	##### Banks of arrays, each will be in a separate <tr>
	"Sopra"	=>	[
		2	=>	[FALSE,	"Terrace"],
		3	=>	[FALSE,	"Bathroom"],
		4	=>	[TRUE,	"Master BR"],
		17	=>	[TRUE,	"Closet"],
		27	=>	[FALSE,	"Hallway"],
		22	=>	[FALSE,	"Porch"],
		10	=>	[TRUE,	"Balcony"],
		9	=>	[TRUE,	"Kids BR"],
	],
	"Sotto" =>	[	# second bank
		11	=>	[TRUE,	"2nd Bath"],
		5	=>	[TRUE,	"Launderette"],
		6	=>	[TRUE,	"Kitchen"],
		13	=>	[TRUE,	"Living"],
		19	=>	[TRUE,	"Under stairs"],
		26	=>	[TRUE,	"Entrance door"],
		21	=>	[TRUE,	"Stairs"],
		20	=>	[TRUE,	"Pantry"],
	],
	"Giardino"	=>	[	# third bank
		12	=>	[TRUE,	"Patio"],
	    16	=>	[TRUE,	"Gate"],
	    7	=>	[TRUE,	"Lawn"],
	    8	=>	[TRUE,	"Pool"],
	    25	=>	[TRUE,	"Garden"],
	    24	=>	[TRUE,	"Crypt"],
	    23	=>	[TRUE,	"Gazebo"],
	    18	=>	[TRUE,	"Tombs"],
	],
];	# end banks

/*************************************************************************
 *****                                                               *****
 ***** FROM THIS POINT ONLY MINOR MODIFICATIONS - OR LEAVE THEM      *****
 *****                                                               *****
 *************************************************************************/

# External pilots: them have to be executable
# for Raspbian each will be "sudo /path/to/bin/command.py"
$accendi	= "/home/www/bin/gpcomm.py on";		# Turn ON
$spegni		= "/home/www/bin/gpcomm.py of";		# Turn OFF
$commuta	= "/home/www/bin/gpcomm.py co";		# Invert state
$stato		= "/home/www/bin/gpcomm.py st";		# Read state
# All will write as output True if command had success, False if not
#  status will report True if relay is on, False if off

# some local vars
$interpasso	=	200000;		# (uSec) momentary pause between on and off
$risultato	=	"";			# Will store result from command

/* # Excluded at the moment

$modalita = [		# button class for switches, different for ON and OFF
	0 => "danger",
	1 => "secondary",
];
*/
