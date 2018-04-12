php2gpio
========
Raspberry Pi 3: control gpio (e.g. piloting relais) from a simple
PHP-AJAX interface, with Python subcommands.

All is based through the file **principale.php** which is supposed to
stay under a password-protected area of your website (apache or lighttpd
with php on a Raspberry Pi 3), along with **config.php** and some
utilities in python under the *bin* directory either under the webpath
or (best for safety reasons) somewhere else.

If you're using raspbian, on *config.php* you should put, for each
external pilot utilty, the `sudo` command
```php
$accendi	= "sudo /home/www/bin/turnon.py";		# Turn ON
$spegni		= "sudo /home/www/bin/turnoff.py";		# Turn OFF
$commuta	= "sudo /home/www/bin/switch.py";		# Invert state
$stato		= "sudo /home/www/bin/status.py";		# Read state

```
Also you're supposed to add the www user (usually *www-data*) into
/etc/sudoers with NOPASS request, in order to pilot GPIO pins, e.g.:
```bash
www-data	ALL:NOPASSWD ALL
```
I've tried it under ubuntu-mate for Raspberry with no big problems

programma.py
------------
Also there is the utility `bin/programma.py`: it is a pseudo-programmed
automatic system involving on and off based on an external file.

Are included two example programs: DefPrg which is default, and NewPrg
which is a more complex one.
programma.py operates ALL relays in a single flavour: either all
momentary or all on-off

*Probably will fork programma.py into an external project*

Contacts
--------
 - grizzly.e90@g-sr.eu
 - http://youtube.com/uibbs2
