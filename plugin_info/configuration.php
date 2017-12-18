<?php

/*
 * This file is part of Jeedom.
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
require_once dirname ( __FILE__ ) . '/../../../core/php/core.inc.php';
include_file ( 'core', 'authentification', 'php' );
if (! isConnect ()) {
	include_file ( 'desktop', '404', 'php' );
	die ();
}
?>
<form class="form-horizontal">
	<fieldset>
         <legend><i class="fa fa-list-alt"></i> {{Général}}</legend>
      <div class="form-group">
        <label class="col-lg-4 control-label">{{Activer le module de contrôle du volume}}</label>
        <div class="col-lg-3">
           <input type="checkbox" class="configKey" data-l1key="volumeControlEnable" />
       </div>
	</div>
  
	</fieldset>
</form>
<form class="form-horizontal">
    <fieldset>
    <legend><i class="fa fa-volume-up"></i> {{Gestion du contrôle du volume sur les Karotz}}</legend>
	<div class="form-group">
    	<label class="col-lg-4"></label>
    	<div class="col-lg-8">
    		<a class="btn btn-success" id="bt_check"><i class="fa fa-check"></i> {{Vérification des Karotz}}</a>
    		<a class="btn btn-warning" id="bt_install"><i class="fa fa-cogs"></i> {{Installation du contrôle de volume des Karotz}}</a>
    	</div>
    </div>
	</fieldset>
</form>
<script>
    $('#bt_check').on('click', function () {
        bootbox.confirm('{{Vérifier le support du contrôle du volume sur les Karotz déjà définis }}', function (result) {
    		if (result) {
    	        $.ajax({// fonction permettant de faire de l'ajax
    	            type: "POST", // methode de transmission des données au fichier php
    	            url: "plugins/karotz/core/ajax/karotz.ajax.php", // url du fichier php
    	            data: {
    	            	action: "checkvolumecontrol"
    	            },
    	            dataType: 'json',
    	            error: function (request, status, error) {
    	            	handleAjaxError(request, status, error);
    	            },
    	            success: function (data) { // si l'appel a bien fonctionné
    	            	if (data.state != 'ok') {
    		            	$('#div_alert').showAlert({message: data.result, level: 'danger'});
    		            	return;
    		            }
    		            var karotzTab=JSON.parse(data.result);
			            var karotzList,separator;
			            karotzList='';
			            separator='';
			            for(var k in karotzTab) {
			            	karotzList+=separator+'<b>'+karotzTab[k].name+'</b>:'+(karotzTab[k].volumeControl?' support ok':' contrôle du volume non installé');
		            		separator='<br>';
		            	}
			            
    		            $('#div_alert').showAlert({message: '{{Vérification réussie}}', level: 'success'});
    		            bootbox.alert({ 
			            	  size: "small",
			            	  title: "{{Vérification du support du contrôle du volume}}",
			            	  message: karotzList
			            	})
    					//$('#ul_plugin .li_plugin[data-plugin_id="karotz"]').click();
    	        	}
    			});
    		}
    	});
    });
	$('#bt_install').on('click', function () {
        bootbox.confirm('{{Installer le contrôle du volume sur les Karotz }}', function (result) {
			if (result) {
		        $.ajax({// fonction permettant de faire de l'ajax
		            type: "POST", // methode de transmission des données au fichier php
		            url: "plugins/karotz/core/ajax/karotz.ajax.php", // url du fichier php
		            data: {
		            	action: "installvolumecontrol",
		            },
		            dataType: 'json',
		            error: function (request, status, error) {
		            	handleAjaxError(request, status, error);
		            },
		            success: function (data) { // si l'appel a bien fonctionné
		            	console.log("data = %o",data);
		            	if (data.state != 'ok') {
			            	$('#div_alert').showAlert({message: data.result, level: 'danger'});
			            	return;
			            }
		            	var karotzTab=JSON.parse(data.result);
			            var karotzList,separator;
			            karotzList='';
			            separator='';
			            for(var k in karotzTab) {
			            	karotzList+=separator+'<b>'+karotzTab[k].name+'</b>:'+(karotzTab[k].volumeControl?' support ok':' contrôle du volume non installé');
		            		separator='<br>';
		            	}
			            $('#div_alert').showAlert({message: '{{Installation terminée}}', level: 'success'});
			            bootbox.alert({ 
			            	  size: "small",
			            	  title: "{{Installation du support du contrôle du volume}}",
			            	  message: karotzList
			            	})
						//$('#ul_plugin .li_plugin[data-plugin_id="karotz"]').click();
		        	}
    			});
    		}
    	});
    });
</script>