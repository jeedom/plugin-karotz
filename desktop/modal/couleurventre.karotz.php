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
$id = $karotz->getId();
$couleur= $karotz->getCmd(null,'couleurstatut')->execCmd();
?>
<div class="alert alert-info">
            Choisissez la couleur du ventre de votre karotz. La couleur actuelle est <span style="background:<?php echo $couleur; ?>;border-radius: 60px;">&nbsp;&nbsp;&nbsp;&nbsp;</span>.
</div>
<div id="ventrecouleur">
<input type="color" id="couleur" name='<?php echo $id; ?>' value='<?php echo $couleur; ?>'/>
</div>
<br />
<a class="btn btn-success ventrecouleur"><i class="fa fa-check-circle"></i> ok</a>

<script>

$('.ventrecouleur').on('click', function() {
	changeventrecouleur($('#couleur').attr('name'),$('input[id=couleur]').val());
	
})

	function changeventrecouleur(_id,_couleur) {
		
		$.ajax({// fonction permettant de faire de l'ajax
			type: "POST", // methode de transmission des données au fichier php
			url: "plugins/karotz/core/ajax/karotz.ajax.php", // url du fichier php
			data: {
				action: "ventrecouleur",
				id: _id,
				couleur: _couleur
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