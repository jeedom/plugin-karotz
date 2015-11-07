<?php

/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

if (init('id') == '') {
    throw new Exception('{{L\'id de l\'équipement ne peut etre vide : }}' . init('op_id'));
}

$id = init('id');
$karotz = karotz::byId($id);
	if (!is_object($karotz)) { 
			  
	 throw new Exception(__('Aucun equipement ne  correspond : Il faut (re)-enregistrer l\'équipement ', __FILE__) . init('action'));
	 }
?>
<div class="alert alert-info">
            Choisissez ce que vous voulez faire
</div>
<div id="micro">
   TTS &nbsp &nbsp Voix <input id="ttsvoice" type='text' name='<?php echo $id; ?>'/> &nbsp &nbsp &nbsp &nbsp Message <input id="ttstext" type='text' name='<?php echo $id; ?>'/></br></br>
   Son &nbsp &nbsp Id  <input id="soundid" type='text' name='<?php echo $id; ?>'/> </br></br>
   Url &nbsp &nbsp <input id="soundurl" type='text' name='<?php echo $id; ?>' size="55"/> </br></br>
</div>
<br/>
<a class="btn btn-success tts"><i class="icon loisir-microphone52"></i> Faire parler</a>
<a class="btn btn-success sound"><i class="icon loisir-musical"></i> Jouer un son</a>
<a class="btn btn-success url"><i class="icon loisir-musical81"></i> Jouer une url</a>
<a class="btn btn-danger stop"><i class="fa fa-stop"></i> Arrêter le son</a>
<script>

$('.tts').on('click', function() {
	tts($('#ttsvoice').attr('name'),$('input[id=ttsvoice]').val(),$('input[id=ttstext]').val());
})
$('.sound').on('click', function() {
	sound($('#soundid').attr('name'),$('input[id=soundid]').val());
})
$('.url').on('click', function() {
	url($('#soundurl').attr('name'),$('input[id=soundurl]').val());
})
$('.stop').on('click', function() {
	stop($('#soundurl').attr('name'));
})
	function tts(_id,_voice,_message) {
		
		$.ajax({// fonction permettant de faire de l'ajax
			type: "POST", // methode de transmission des données au fichier php
			url: "plugins/karotz/core/ajax/karotz.ajax.php", // url du fichier php
			data: {
				action: "tts",
				id: _id,
				voice: _voice,
				message: _message
				
	
			},
			dataType: 'json',
			error: function(request, status, error) {
				handleAjaxError(request, status, error);
			},
        success: function(data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
            	$('#div_alert').showAlert({message:  data.result,level: 'danger'});
                return;
            }
            modifyWithoutSave=false;
             window.location.reload();
        }
    });
}

function sound(_id,_sound) {
		
		$.ajax({// fonction permettant de faire de l'ajax
			type: "POST", // methode de transmission des données au fichier php
			url: "plugins/karotz/core/ajax/karotz.ajax.php", // url du fichier php
			data: {
				action: "sound",
				id: _id,
				sound: _sound,
				
	
			},
			dataType: 'json',
			error: function(request, status, error) {
				handleAjaxError(request, status, error);
			},
        success: function(data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
            	$('#div_alert').showAlert({message:  data.result,level: 'danger'});
                return;
            }
            modifyWithoutSave=false;
             window.location.reload();
        }
    });
}

function url(_id,_url) {
		
		$.ajax({// fonction permettant de faire de l'ajax
			type: "POST", // methode de transmission des données au fichier php
			url: "plugins/karotz/core/ajax/karotz.ajax.php", // url du fichier php
			data: {
				action: "url",
				id: _id,
				url: _url
				
	
			},
			dataType: 'json',
			error: function(request, status, error) {
				handleAjaxError(request, status, error);
			},
        success: function(data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
            	$('#div_alert').showAlert({message:  data.result,level: 'danger'});
                return;
            }
            modifyWithoutSave=false;
             window.location.reload();
        }
    });
}
function stop(_id) {
		
		$.ajax({// fonction permettant de faire de l'ajax
			type: "POST", // methode de transmission des données au fichier php
			url: "plugins/karotz/core/ajax/karotz.ajax.php", // url du fichier php
			data: {
				action: "stop",
				id: _id
				
	
			},
			dataType: 'json',
			error: function(request, status, error) {
				handleAjaxError(request, status, error);
			},
        success: function(data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
            	$('#div_alert').showAlert({message:  data.result,level: 'danger'});
                return;
            }
            modifyWithoutSave=false;
             window.location.reload();
        }
    });
}


</script>