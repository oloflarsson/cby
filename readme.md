CBY - Wordpress plugin för CampBurnYourself.se
====================
Funktioner
----------
* Ett formulär där personer kan event-anmäla sig.
* Ett workshop-schema där event-anmälda kan workshop-anmäla sig.
* En widget som visar de event-anmälda som har betalat.
* En adminpanel där man kan hantera event-anmälda och workshops.

Har använts dessa år
----------
* 2012

Teknologi
----------
* Detta är en wordpress plugin.
* Den är skriven i PHP.
* [Doctrine 2 ORM](http://www.doctrine-project.org/projects/orm)

Att installera pluginen
----------
1.Se till att du befinner dig i mappen /wp-content/plugins
1.`git clone --recursive git@github.com:oloflarsson/cby.git cby`<br>
Notera att `--recursive` är viktigt för att initiera submodulerna (doctrine2 ORM).
1. Pluginen ligger nu i mappen /wp-content/plugins/cby