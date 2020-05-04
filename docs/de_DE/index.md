Beschreibung 
===========

Mit diesem Plugin können Sie Ihren Karotz steuern (läuft unter
[OpenKarotz](http://www.openkarotz.org/)). Es geht von seiner ventralen Führung zu
seine Ohren gehen durch die Geräusche, Sprachsynthese und voll
d'autres.

Konfiguration 
=============

Jeedom Plugin Konfiguration : 
--------------------------------

**Installation / Erstellung**

Um das Plugin nutzen zu können, müssen Sie herunterladen und installieren
aktiviere es wie jedes Jeedom Plugin.

Gehen Sie zum Menü Plugins / Kommunikation, dort finden Sie die
Karotz Plugin.

Sie gelangen auf die Seite, auf der Ihre Ausrüstung aufgelistet ist (Sie können
habe mehrere Karotz) und mit denen du sie erstellen kannst.

Klicken Sie auf die Schaltfläche Hinzufügen :

Sie gelangen dann auf die Konfigurationsseite Ihres Karotz.

-   **Commandes**

In diesem Abschnitt haben Sie nichts zu tun. Bestellungen werden erstellt
automatiquement.

-   Aktualisieren: Schaltfläche, um das Widget bei Bedarf zu aktualisieren

-   Blinkt aus : Ermöglicht das Stoppen des Blinkens der LED

-   Blinkt auf : Aktiviert das Blinken der LED

-   Stoppen Sie den Ton : Stoppen Sie eine Musik oder einen laufenden Sound

-   Schlafenszeit : lässt das Kaninchen schlafen

-   Stehend : Weckt das Kaninchen auf

-   Still stehen : Ermöglicht das Aufwecken des Kaninchens im lautlosen Modus

-   Uhr : Ermöglicht das Starten des Kaninchenuhrmodus

-   Stimmung : ermöglicht es dem Kaninchen, die ausgewählte Stimmung zu erkennen

-   Stimmung Nr.: erlaubt dem Kaninchen, die Stimmung zu sagen, die durch seine angezeigt wird
    n°

-   Zufällige Stimmung : Lass das Kaninchen eine Stimmung sagen
    zufällig

-   Zufälliges Ohr : ermöglicht es Ihnen, Ihre Ohren zu bewegen
    zufällig

-   Ohr RàZ : ermöglicht es, die Ohren in die Ausgangsposition zurückzubringen

-   Ohrenpositionen : passt die genaue Position der beiden an
    oreilles

-   Sound of Karotz (Name) : Mit dieser Option können Sie einen Karotz-Sound starten (Piepton)
    zum Beispiel) durch Angabe seines Namens

-   Karotz-Sound : ermöglicht das Starten eines Karotz-Sounds (z. B.. Piepton)
    indem Sie seinen Namen aus einer Liste auswählen

-   Seine URL : ermöglicht das Lesen einer URL durch den Radiosender Karotz
    zum Beispiel)

-   SqueezeBox Ein : Mit dieser Option können Sie den Karotz-Squeezebox-Modus aktivieren

-   SqueezeBox aus : Ermöglicht das Deaktivieren des Karotz-Squeezebox-Modus

-   Schlafend : lässt Sie wissen, ob der Karotz schläft (sonst ist es
    ist wach)

-   Farbstatus : ermöglicht es, die Farbe aktuell auf dem zu haben
    Karotz Bauch

-   TTS : ermöglicht dem Kaninchen das Sprechen durch Auswahl der Stimme und der
    Nachricht (standardmäßig wird die Nachricht zwischengespeichert)

-   TTS ohne Cache : ermöglicht dem Kaninchen zu sprechen, indem es die
    Sprache und Nachricht (Nachricht wird nicht zwischengespeichert)

-   Pulsgeschwindigkeit : Passt die Geschwindigkeit des Blinkens an

-   % des Platzes belegt : informiert Sie über den Prozentsatz der verwendeten Festplatte
    das Kaninchen

-   Freier Speicherplatz : Wert in MB. freien Speicherplatz auf dem Kaninchen

-   Neu starten : ermöglicht es Ihnen, das Kaninchen neu zu starten

-   Zeit einstellen : gibt das Kaninchen automatisch an zurück
    die Stunde (nützlich zum Ändern der Zeit)

-   Lautstärke : gibt in% den Lautstärkepegel an

-   Lautstärke : ermöglicht die Auswahl des Lautstärkepegels in% (empfohlene max
    50%, Verzerrungsrisiko oben)

-   Lautstärke + : erhöht die Lautstärke um 5%

-   Volume- : verringert die Lautstärke um 5%

-   Foto : erlaubt ein Foto vom Kaninchen zu machen

-   Fotos löschen : Mit dieser Option können Sie alle vom
    Kaninchen (macht Speicherplatz frei)

-   Fotos aktualisieren Liste : ermöglicht das Aktualisieren der Liste der Fotos
    erhalten

-   Auflistung der Fotos : Liste der Fotos aufbewahrt

-   Fotos herunterladen : ermöglicht das Herunterladen (per FTP) der Fotos
    auf der Festplatte gespeichert (sie werden nicht gelöscht)

Alle diese Befehle sind über die Szenarien verfügbar.

TTS-Befehl 
------------

Der TTS-Befehl kann mehrere Optionen haben, die durch & getrennt sind :

-   Stimme : die Sprachnummer

-   Nocache : Verwenden Sie den Cache nicht

Beispiel :

    Stimme = 3 & Nocache = 1

…

Informationen / Aktionen 
========================

Informationen / Aktionen im Dashboard : 
---------------------------------------

![widget](../images/widget.jpg)

-   Bis : Rufen Sie die Soundauswahlseite auf

![karotz screenshot5](../images/karotz_screenshot5.jpg)

-   B. : Schaltfläche "Aktualisieren", um den Status anzufordern und
    couleur

-   C. : Ohrkontrollzone (zufällig, zurückgesetzt
    Null, benutzerdefiniert)

![karotz screenshot7](../images/karotz_screenshot7.jpg)

-   D. : Aktionsbereich (Uhr / Stimmung)

-   E. : Squeezebox-Bereich (aktivieren / deaktivieren)

-   F. : LED-Zone (Blinken aktivieren / deaktivieren)

![karotz screenshot6](../images/karotz_screenshot6.jpg)

-   G. : Schieberegler für die Blitzgeschwindigkeitsregelung

-   H. : Durch Klicken auf den Bauch können Sie die Farbe von ändern
    das führte

-   Ich : Durch Klicken auf das Kaninchen kann es sich hinlegen oder
    s'endormir

Faq 
===

Wie oft werden die Informationen aktualisiert?:   

 Das System ruft alle 30 Minuten oder danach Informationen ab
    eine Aufforderung, die Farbe oder den Zustand des Kaninchens zu ändern. Du kannst
    Klicken Sie auf den Befehl Aktualisieren, um manuell zu aktualisieren.

Changelog detailliert :
<https://github.com/jeedom/plugin-karotz/commits/stable>
