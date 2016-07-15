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
<form class="form-horizontal">
	<div class="form-group">
		<label class="col-sm-2 control-label">{{Message}}</label>
		<div class="col-sm-10">
			<textarea class="form-control" id="ttstext" rows="4" name='<?php echo $id; ?>'></textarea>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">{{TTS Voix}}</label>
		<div class="col-sm-10">
			<input class="form-control" id="ttsvoice" type='text' name='<?php echo $id; ?>'/>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">{{Son  Id}}</label>
		<div class="col-sm-10">
			<input class="form-control" id="soundid" type='text' name='<?php echo $id; ?>'/>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">{{Url}}</label>
		<div class="col-sm-10">
			<input class="form-control" id="soundurl" type='text' name='<?php echo $id; ?>' size="55"/>
		</div>
	</div>
</form>
<div class='pull-right'>
<a class="btn btn-success tts"><i class="icon loisir-microphone52"></i> Faire parler</a>
<a class="btn btn-success sound"><i class="icon loisir-musical"></i> Jouer un son</a>
<a class="btn btn-success url"><i class="icon loisir-musical81"></i> Jouer une url</a>
<a class="btn btn-danger stop"><i class="fa fa-stop"></i> Arrêter le son</a>
</div>
<script>
	$('.tts').on('click', function() {
		tts($('#ttsvoice').attr('name'),$('input[id=ttsvoice]').val(),$('textarea[id=ttstext]').val());
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
		$.ajax({
			type: "POST",
			url: "plugins/karotz/core/ajax/karotz.ajax.php",
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
			success: function(data) {
				if (data.state != 'ok') {
					$('#div_alert').showAlert({message:  data.result,level: 'danger'});
					return;
				}
			}
		});
	}

	function sound(_id,_sound) {
		$.ajax({
			type: "POST",
			url: "plugins/karotz/core/ajax/karotz.ajax.php",
			data: {
				action: "sound",
				id: _id,
				sound: _sound,
			},
			dataType: 'json',
			error: function(request, status, error) {
				handleAjaxError(request, status, error);
			},
			success: function(data) {
				if (data.state != 'ok') {
					$('#div_alert').showAlert({message:  data.result,level: 'danger'});
					return;
				}
			}
		});
	}

	function url(_id,_url) {
		$.ajax({
			type: "POST",
			url: "plugins/karotz/core/ajax/karotz.ajax.php",
			data: {
				action: "url",
				id: _id,
				url: _url
			},
			dataType: 'json',
			error: function(request, status, error) {
				handleAjaxError(request, status, error);
			},
			success: function(data) {
				if (data.state != 'ok') {
					$('#div_alert').showAlert({message:  data.result,level: 'danger'});
					return;
				}
			}
		});
	}
	function stop(_id) {
		$.ajax({
			type: "POST",
			url: "plugins/karotz/core/ajax/karotz.ajax.php",
			data: {
				action: "stop",
				id: _id
			},
			dataType: 'json',
			error: function(request, status, error) {
				handleAjaxError(request, status, error);
			},
			success: function(data) {
				if (data.state != 'ok') {
					$('#div_alert').showAlert({message:  data.result,level: 'danger'});
					return;
				}
			}
		});
	}
</script>