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
            Choisissez la position des oreilles (valeur de 0 à 16)
</div>
<div id="changeear">
   Oreille droite <input id="rightear" type='number' min="0" max="16" step="1" name='<?php echo $id; ?>'/></br></br>
   Oreille gauche <input id="leftear" type='number'  min="0" max="16" step="1" name='<?php echo $id; ?>'/>
</div>
<br/>
<a class="btn btn-success ok"><i class="fa fa-check-circle"></i> Faire bouger les oreilles</a>
<script>

$('.ok').on('click', function() {
	moveear($('#rightear').attr('name'),$('input[id=rightear]').val(),$('input[id=leftear]').val());
})
	function moveear(_id,_right,_left) {
		
		$.ajax({// fonction permettant de faire de l'ajax
			type: "POST", // methode de transmission des données au fichier php
			url: "plugins/karotz/core/ajax/karotz.ajax.php", // url du fichier php
			data: {
				action: "moveear",
				id: _id,
				right: _right,
				left: _left
				
	
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