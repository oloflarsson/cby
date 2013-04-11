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
1. Gå till mappen <b>/wp-content/plugins</b>
1. `git clone git@github.com:oloflarsson/cby.git cby`<br>
(Pluginen ligger nu i mappen /wp-content/plugins/cby)
1. Gå till mappen <b>/wp-content/plugins/cby</b> (`cd cby`)
1. Se till att getlibs.php är exekverbar.
1. `./getlibs.php`<br>
Detta kommer att ladda ner dependencies till mappen lib. Notera att du måste ha git installerat på datorn.
1. Skapa en databas för CBY entities och lägg connection-informationen i conf.php
1. `./doctrine orm:schema-tool:create`<br>
Detta kommer att skapa databas-strukturen.
1. `./loadfixtures.php`<br>
1. Gå aktivera pluginen genom adminpanelen i wordpress.

Att använda pluginen
----------
TODO