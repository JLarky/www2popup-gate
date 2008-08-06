#!/bin/bash
/usr/bin/links -dump http://jlarky/utilites/for_bot | /usr/bin/tr -d " " > /var/www/gate/bin/tosend.add
/usr/bin/links -dump http://jlarky/utilites/for_bot
chown nobody.nobody tosend.add 2>/dev/null
