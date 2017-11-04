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


$(document).ready(function(){
	if ($('#snapshot').checked){
	    $('.snapshotFtp').fadeIn('slow');
	} else {
	   	$('.snapshotFtp').fadeOut('slow');
	};
    $('#snapshot').change(function(){
    	if(this.checked)
        	$('.snapshotFtp').fadeIn('slow');
        else
        	$('.snapshotFtp').fadeOut('slow');
    });
    if (volumeControlEnable==0){
    	$('.volumeControlEnable').hide();
    }
	if ($('#volume').checked){
	    $('.volume').fadeIn('slow');
	} else {
	   	$('.volume').fadeOut('slow');
	};
    $('#volume').change(function(){
    	if(this.checked)
        	$('.volume').fadeIn('slow');
        else
        	$('.volume').fadeOut('slow');
    });
});

$('#bt_replaceCmd').on('click', function () {

    bootbox.confirm('{{Etes-vous sûr de vouloir récréer toutes les commandes ? Cela va supprimer les commandes existantes}}', function (result) {
        if (result) {
            $.ajax({
                type: "POST", // méthode de transmission des données au fichier php
                url: "plugins/karotz/core/ajax/karotz.ajax.php", 
                data: {
                    action: "replaceCmd",
                    id: $('.eqLogicAttr[data-l1key=id]').value(),
                },
                dataType: 'json',
                global: false,
                error: function (request, status, error) {
                    handleAjaxError(request, status, error);
                },
                success: function (data) { 
                    if (data.state != 'ok') {
                        $('#div_alert').showAlert({message: data.result, level: 'danger'});
                        return;
                    }
                    $('#div_alert').showAlert({message: '{{Opération réalisée avec succès}}', level: 'success'});
                    $('.li_eqLogic[data-eqLogic_id=' + $('.eqLogicAttr[data-l1key=id]').value() + ']').click();
                }
            });
        }
    });
});

$('#bt_refreshCmd').on('click', function () {

    bootbox.confirm('{{Etes-vous sûr de vouloir mettre à jour toutes les commandes ? Cela va conserver les commandes existantes}}', function (result) {
        if (result) {
            $.ajax({
                type: "POST", // méthode de transmission des données au fichier php
                url: "plugins/karotz/core/ajax/karotz.ajax.php", 
                data: {
                    action: "refreshCmd",
                    id: $('.eqLogicAttr[data-l1key=id]').value(),
                },
                dataType: 'json',
                global: false,
                error: function (request, status, error) {
                    handleAjaxError(request, status, error);
                },
                success: function (data) { 
                    if (data.state != 'ok') {
                        $('#div_alert').showAlert({message: data.result, level: 'danger'});
                        return;
                    }
                    $('#div_alert').showAlert({message: '{{Opération réalisée avec succès}}', level: 'success'});
                    $('.li_eqLogic[data-eqLogic_id=' + $('.eqLogicAttr[data-l1key=id]').value() + ']').click();
                }
            });
        }
    });
});

 $("#table_cmd").sortable({axis: "y", cursor: "move", items: ".cmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});

 function addCmdToTable(_cmd) {
  if (!isset(_cmd)) {
    var _cmd = {configuration: {}};
}
if (!isset(_cmd.configuration)) {
    _cmd.configuration = {};
}
var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
tr += '<td>';
tr += '<span class="cmdAttr" data-l1key="id" style="display:none;"></span>';
tr += '<div class="row">';
tr += '<div class="col-sm-6">';
tr += '<input class="cmdAttr form-control input-sm" data-l1key="name">';
tr += '</div>';
tr += '</div>';
tr += '</td>';
tr += '<td>';
if (is_numeric(_cmd.id)) {
    tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fa fa-cogs"></i></a> ';
    tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>';
}
tr += '</td>';
tr += '</tr>';
$('#table_cmd tbody').append(tr);
$('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
if (isset(_cmd.type)) {
    $('#table_cmd tbody tr:last .cmdAttr[data-l1key=type]').value(init(_cmd.type));
}
jeedom.cmd.changeType($('#table_cmd tbody tr:last'), init(_cmd.subType));
}