twitter-bot
===========
Simple twitter bot which searchs for a word and replies and gives fav.

Thanks to GeekyTheory author Alex Esquiva Twitter API PHP tutorials: http://geekytheory.com/como-usar-la-api-de-twitter-en-php/

Setup
=====
Best way for having it working all day is with a Raspberry Pi, in my case I setup two crons with a python file which makes a http request for executing the .php file

* * * * * python /home/pi/bot.py
* * * * * sleep 30 ; python /home/pi/bot.py

