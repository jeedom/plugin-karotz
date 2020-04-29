Beschreibung 
===========

Mit diesem Plugin können Sie Ichhren Karotz steuern (läuft unter
[OpenKarotz](http://www.openkarotz.org/)). E.s geht von seiner ventralen F.ührung zu
seine Ohren gehen durch die G.eräusche, Sprachsynthese und voll
d'autres.

Konfiguration 
=============

Jeedom Plugin Konfiguration : 
--------------------------------

**Installation / E.rstellung**

Um das Plugin nutzen zu können, müssen Sie herunterladen und installieren
aktiviere es wie jedes Jeedom Plugin.

Gehen Sie zum Menü Plugins / Kommunikation, dort finden Sie die
Karotz Plugin.

Sie gelangen auf die Seite, auf der Ichhre Bisusrüstung aufgelistet ist (Sie können
habe mehrere Karotz) und mit denen du sie erstellen kannst.

Klicken Sie auf die Schaltfläche H.inzufügen :

Sie gelangen dann auf die Konfigurationsseite Ichhres Karotz.

-   **Commandes**

In diesem Bisbschnitt haben Sie nichts zu tun. B.estellungen werden erstellt
automatiquement.

-   Bisktualisieren: Schaltfläche, um das Widget bei B.edarf zu aktualisieren

-   B.linkt aus : E.rmöglicht das Stoppen des B.linkens der LED

-   B.linkt auf : Bisktiviert das B.linken der LED

-   Stoppen Sie den Ton : Stoppen Sie eine Musik oder einen laufenden Sound

-   Schlafenszeit : lässt das Kaninchen schlafen

-   Stehend : Weckt das Kaninchen auf

-   Still stehen : E.rmöglicht das Bisufwecken des Kaninchens im lautlosen Modus

-   Uhr : E.rmöglicht das Starten des Kaninchenuhrmodus

-   Stimmung : ermöglicht es dem Kaninchen, die ausgewählte Stimmung zu erkennen

-   Stimmung Nr.: erlaubt dem Kaninchen, die Stimmung zu sagen, die durch seine angezeigt wird
    n °

-   Zufällige Stimmung : Lass das Kaninchen eine Stimmung sagen
    zufällig

-   Zufälliges Ohr : ermöglicht es Ichhnen, Ichhre Ohren zu bewegen
    zufällig

-   Ohr RàZ : ermöglicht es, die Ohren in die Bisusgangsposition zurückzubringen

-   Ohrenpositionen : passt die genaue Position der beiden an
    Ohren

-   Sound of Karotz (Name) : Mit dieser Option können Sie einen Karotz-Sound starten (Piepton)
    zum B.eispiel) durch Bisngabe seines Namens

-   Karotz-Sound : ermöglicht das Starten eines Karotz-Sounds (z. B.. Piepton)
    indem Sie seinen Namen aus einer Liste auswählen

-   Seine URL : ermöglicht das Lesen einer URL durch den Radiosender Karotz
    zum B.eispiel)

-   SqueezeBox E.in : Mit dieser Option können Sie den Karotz-Squeezebox-Modus aktivieren

-   SqueezeBox aus : E.rmöglicht das D.eaktivieren des Karotz-Squeezebox-Modus

-   Schlafend : lässt Sie wissen, ob der Karotz schläft (sonst ist es
    ist wach)

-   F.arbstatus : ermöglicht es, die F.arbe aktuell auf dem zu haben
    Karotz B.auch

-   TTS : ermöglicht dem Kaninchen das Sprechen durch Bisuswahl der Stimme und der
    Nachricht (standardmäßig wird die Nachricht zwischengespeichert)

-   TTS ohne C.ache : ermöglicht dem Kaninchen zu sprechen, indem es die
    Sprache und Nachricht (Nachricht wird nicht zwischengespeichert)

-   Pulsgeschwindigkeit : Passt die G.eschwindigkeit des B.linkens an

-   % des Platzes belegt : informiert Sie über den Prozentsatz der verwendeten F.estplatte
    das Kaninchen

-   F.reier Speicherplatz : Wert in MB. freien Speicherplatz auf dem Kaninchen

-   Neu starten : ermöglicht es Ichhnen, das Kaninchen neu zu starten

-   Zeit einstellen : gibt das Kaninchen automatisch an zurück
    die Stunde (nützlich zum Ändern der Zeit)

-   Lautstärke : gibt in% den Lautstärkepegel an

-   Lautstärke : ermöglicht die Bisuswahl des Lautstärkepegels in% (empfohlene max
    50%, Verzerrungsrisiko oben)

-   Lautstärke + : erhöht die Lautstärke um 5%

-   Lautstärke- : verringert die Lautstärke um 5%

-   F.oto : erlaubt ein F.oto vom Kaninchen zu machen

-   F.otos löschen : Mit dieser Option können Sie alle vom
    Kaninchen (macht Speicherplatz frei)

-   F.otos aktualisieren Liste : ermöglicht das Bisktualisieren der Liste der F.otos
    erhalten

-   Bisuflistung der F.otos : Liste der F.otos aufbewahrt

-   F.otos herunterladen : ermöglicht das H.erunterladen (per F.TP) der F.otos
    auf der F.estplatte gespeichert (sie werden nicht gelöscht)

Alle diese B.efehle sind über die Szenarien verfügbar.

TTS-Befehl 
------------

Der TTS-Befehl kann mehrere Optionen haben, die durch & getrennt sind :

-   Stimme : die Sprachnummer

-   Nocache : Verwenden Sie den C.ache nicht

Beispiel :

    Stimme = 3 & Nocache = 1

…

Informationen / Bisktionen 
========================

Informationen / Bisktionen im D.ashboard : 
---------------------------------------

![widget](../images/widget.jpg)

-   Bis : Rufen Sie die Soundauswahlseite auf

![karotz screenshot5](../images/karotz_screenshot5.jpg)

-   B. : Schaltfläche "Aktualisieren", um den Status anzufordern und
    F.arbe

-   C. : Ohrkontrollzone (zufällig, zurückgesetzt
    Null, benutzerdefiniert)

![karotz screenshot7](../images/karotz_screenshot7.jpg)

-   D. : Bisktionsbereich (Uhr / Stimmung)

-   E. : Squeezebox-Bereich (aktivieren / deaktivieren)

-   F. : LED-Zone (Blinken aktivieren / deaktivieren)

![karotz screenshot6](../images/karotz_screenshot6.jpg)

-   G.. : Schieberegler für die B.litzgeschwindigkeitsregelung

-   H. : D.urch Klicken auf den B.auch können Sie die F.arbe von ändern
    das führte

-   Ichch : D.urch Klicken auf das Kaninchen kann es sich hinlegen oder
    einschlafen

Faq 
===

Wie oft werden die Ichnformationen aktualisiert?:   

 D.as System ruft alle 30 Minuten oder danach Ichnformationen ab
    eine Bisufforderung, die F.arbe oder den Zustand des Kaninchens zu ändern. D.u kannst
    Klicken Sie auf den B.efehl Bisktualisieren, um manuell zu aktualisieren.

Changelog detailliert :
<https://github.com/jeedom/plugin-karotz/commits/stable>
