# php2gpio
Raspberry Pi 3: control gpio (e.g. piloting relais) from a simple PHP-AJAX interface, with Python subcommands

All is based through the file principale.php which is supposed to stay under a
password-protected area of your website (apache or lighttpd with php on a
Raspberry Pi 3), along with config.php and some utilities in python under the
"bin" directory either under the webpath or (best for safety reasons) somewhere
else.

If you're using raspbian, on config.php you should put
"sudo /path/to/bin/command.py"
into config.php for it to works, also you should add the www users into
/etc/sudoers with NOPASS request, in order to pilot GPIO pins as www user

I've tried it under ubuntu-mate for Raspberry with no big problems
