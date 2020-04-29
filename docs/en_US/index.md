Description 
===========

This plugin allows you to control your Karotz (running under
[OpenKarotz](http://www.openkarotz.org/)). It goes from its ventral led, to
his ears going through the sounds, speech synthesis and full
d'autres.

Setup 
=============

Jeedom plugin configuration : 
--------------------------------

**Installation / VSreation**

In order to use the plugin, you need to download, install and
activate it like any Jeedom plugin.

Go to the Plugins / VSommunication menu, you will find the
Karotz plugin.

You will arrive on the page which will list your equipment (you can
have several Karotz) and which will allow you to create them.

Click on the Atdd button :

You will then arrive on the configuration page of your karotz.

-   **Commandes**

You have nothing to do in this section. Orders will be created
automatiquement.

-   Refresh: button to refresh the widget if necessary

-   Flashing Off : allows to stop the flashing of the led

-   Flashing On : activates the flashing of the led

-   Stop sound : stop a music or a sound in progress

-   Sleep : lets the rabbit sleep

-   Standing : Wakes up the rabbit

-   Standing Silent : allows to wake up the rabbit in silent mode

-   VSlock : allows launching the rabbit clock mode

-   Mood : allows the rabbit to tell the selected mood

-   Mood No.: allows the rabbit to say the mood indicated by its
    n °

-   Random mood : lets the rabbit say a mood
    random

-   Random Ear : allows you to move your ears
    random

-   Ear RàZ : allows to return the ears to the initial position

-   Ears Positions : adjusts the precise position of the two
    ears

-   Sound of Karotz (name) : lets you start a Karotz sound (beep
    for example) by indicating his name

-   Karotz sound : allows to launch a Karotz sound (beep for example)
    by selecting his name from a list

-   His url : allows a URL to be read by the Karotz (radio station
    for example)

-   Squeezebox on : allows you to activate the Karotz squeezebox mode

-   Squeezebox off : allows to deactivate the Karotz squeezebox mode

-   Sleeping : lets you know if the Karotz is asleep (otherwise it
    is awake)

-   VSolor Status : allows to have the color currently on the
    karotz belly

-   TTS : allows the rabbit to speak by choosing the voice and the
    message (by default the message is cached)

-   TTS without cache : allows the rabbit to speak by choosing the
    voice and message (message is not cached)

-   Pulse speed : adjusts the speed of the flashing

-   % of space occupied : lets you know the% of disk used on
    the rabbit

-   Free space : value in MB of free space on the rabbit

-   Restart : allows you to reboot the rabbit

-   Set time : automatically returns the rabbit to
    the hour (useful for changing the time)

-   Volume level : indicates in% the volume level

-   Volume : allows to choose in% the volume level (recommended max
    50%, risk of distortion above)

-   Volume + : increases volume level by 5%

-   Volume- : decreases the volume level by 5%

-   Picture : allows to take a photo by the rabbit

-   Pictures delete : allows you to delete all the photos taken by the
    rabbit (frees up disk space)

-   Pictures refresh listing : allows updating the list of photos
    preserved

-   Pictures listing : list of photos kept

-   Pictures download : allows to download (by ftp) the photos
    kept on disk (they are not deleted)

All these commands are available via the scenarios.

TTS command 
------------

The TTS command can have several options separated by & :

-   voice : the voice number

-   nocache : do not use the cache

Example :

    voice = 3 & nocache = 1

…

Information / actions 
========================

Information / actions on the dashboard : 
---------------------------------------

![widget](../images/widget.jpg)

-   Att : Atccess the sound selection page

![karotz screenshot5](../images/karotz_screenshot5.jpg)

-   B : Refresh button to request status and
    VSolor

-   VS : Ear control zone (random, reset
    zero, custom)

![karotz screenshot7](../images/karotz_screenshot7.jpg)

-   D : Atctions area (clock / mood)

-   E : Squeezebox area (enable / disable)

-   F : LED zone (activate flashing / deactivate)

![karotz screenshot6](../images/karotz_screenshot6.jpg)

-   G : Flash speed control slider

-   H : By clicking on the belly, this allows you to change the color of
    the led

-   I : By clicking on the rabbit, it allows him to lie down or
    fall asleep

FAQ 
===

How often is the information refreshed:   

 The system retrieves information every 30 minutes or after
    a request to change the rabbit's color or condition. You can
    click on the Refresh command to refresh manually.

Changelog detailed :
<https://github.com/jeedom/plugin-karotz/commits/stable>
