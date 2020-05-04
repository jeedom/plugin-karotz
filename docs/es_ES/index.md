Descripción 
===========

Este complemento le permite controlar su Karotz (ejecutándose bajo
[OpenKarotz](http://www.openkarotz.org/)) Va desde su led ventral, a
sus oídos pasando por los sonidos, síntesis de voz y lleno
d'autres.

Configuración 
=============

Configuración del complemento Jeedom : 
--------------------------------

**Instalación / Creación**

Para usar el complemento, debe descargar, instalar y
activarlo como cualquier complemento de Jeedom.

Vaya al menú Complementos / Comunicación, encontrará el
Complemento Karotz.

Llegará a la página que enumerará su equipo (puede
tener varios Karotz) y que te permitirán crearlos.

Haga clic en el botón Agregar :

Luego llegará a la página de configuración de su karotz.

-   **Commandes**

No tienes nada que hacer en esta sección.. Se crearán pedidos
automatiquement.

-   Refrescar: botón para actualizar el widget si es necesario

-   Parpadeando : permite detener el parpadeo del led

-   Parpadeando : activa el parpadeo del led

-   Detener el sonido : detener una música o un sonido en progreso

-   Hora de acostarse : deja dormir al conejo

-   De pie : Despierta el conejo

-   De pie en silencio : permite despertar al conejo en modo silencioso

-   Reloj : permite iniciar el modo de reloj de conejo

-   Humor : permite que el conejo diga el estado de ánimo seleccionado

-   Estado de ánimo: permite al conejo decir el estado de ánimo indicado por su
    n°

-   Estado de ánimo aleatorio : deja que el conejo diga un humor
    al azar

-   Oreja al azar : te permite mover las orejas
    al azar

-   Ear RàZ : permite devolver las orejas a la posición inicial

-   Posiciones de orejas : ajusta la posición precisa de los dos
    oreilles

-   Sonido de Karotz (nombre) : le permite iniciar un sonido de Karotz (pitido
    por ejemplo) indicando su nombre

-   Sonido Karotz : permite lanzar un sonido Karotz (pitido por ejemplo)
    seleccionando su nombre de una lista

-   Su url : permite que un Karotz lea una URL (estación de radio
    por ejemplo)

-   Squeezebox en : le permite activar el modo Karotz squeezebox

-   Squeezebox off : permite desactivar el modo Karotz squeezebox

-   Dormido : le permite saber si el Karotz está dormido (de lo contrario
    está despierto)

-   Estado de color : permite tener el color actualmente en el
    vientre de karotz

-   TTS : permite que el conejo hable eligiendo la voz y el
    mensaje (de forma predeterminada, el mensaje se almacena en caché)

-   TTS sin caché : permite que el conejo hable eligiendo el
    voz y mensaje (el mensaje no está en caché)

-   Velocidad de pulso : ajusta la velocidad del parpadeo

-   % del espacio ocupado : le permite saber el% de disco utilizado en
    el conejo

-   Espacio libre : valor en MB de espacio libre en el conejo

-   Reiniciar : te permite reiniciar el conejo

-   Establecer hora : devuelve automáticamente el conejo a
    la hora (útil para cambiar la hora)

-   Nivel de volumen : indica en% el nivel de volumen

-   Volumen : permite elegir en% el nivel de volumen (máximo recomendado
    50%, riesgo de distorsión arriba)

-   Volumen + : aumenta el nivel de volumen en un 5%

-   Volume- : disminuye el nivel de volumen en un 5%

-   Foto : permite tomar una foto por el conejo

-   Fotos borradas : le permite eliminar todas las fotos tomadas por el
    conejo (libera espacio en disco)

-   Fotos actualizar listado : permite actualizar la lista de fotos
    conservado

-   Listado de fotos : lista de fotos mantenidas

-   Descarga de fotos : permite descargar (por ftp) las fotos
    guardado en el disco (no se eliminan)

Todos estos comandos están disponibles a través de los escenarios..

Comando TTS 
------------

El comando TTS puede tener varias opciones separadas por & :

-   voz : el número de voz

-   nocaché : no use el caché

Ejemplo :

    voz = 3 y no caché = 1

...

Información / acciones 
========================

Información / acciones en el tablero : 
---------------------------------------

![widget](../images/widget.jpg)

-   A las : Acceda a la página de selección de sonido

![karotz screenshot5](../images/karotz_screenshot5.jpg)

-   B : Botón Actualizar para solicitar estado y
    couleur

-   C : Zona de control del oído (aleatorio, reinicio
    cero, personalizado)

![karotz screenshot7](../images/karotz_screenshot7.jpg)

-   D : Área de acciones (reloj / estado de ánimo)

-   E : Área de Squeezebox (activar / desactivar)

-   F : Zona LED (activar parpadeo / desactivar)

![karotz screenshot6](../images/karotz_screenshot6.jpg)

-   G : Control deslizante de control de velocidad del flash

-   H : Al hacer clic en el vientre, esto le permite cambiar el color de
    el led

-   Yo : Al hacer clic en el conejo, le permite acostarse o
    s'endormir

Preguntas frecuentes 
===

¿Con qué frecuencia se actualiza la información?:   

 El sistema recupera información cada 30 minutos o después
    una solicitud para cambiar el color o la condición del conejo. Usted puede
    haga clic en el comando Actualizar para actualizar manualmente.

Registro de cambios detallado :
<https://github.com/jeedom/plugin-karotz/commits/stable>
