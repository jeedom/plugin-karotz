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

try {
    require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
    include_file('core', 'authentification', 'php');

    
    if (init('action') == 'replaceCmd') {
        $eqLogic = karotz::byId(init('id'));
        if (!is_object($eqLogic)) {
            throw new Exception(__('Karotz eqLogic non trouvé : ', __FILE__) . init('id'));
        }
        foreach ($eqLogic->getCmd() as $cmd) {
            $cmd->remove();
        }
        $eqLogic->postSave();
        ajax::success();
    }
    
    if (init('action') == 'refreshCmd') {
        $eqLogic = karotz::byId(init('id'));
        if (!is_object($eqLogic)) {
            throw new Exception(__('Karotz eqLogic non trouvé : ', __FILE__) . init('id'));
        }
        $eqLogic->postSave();
        ajax::success();
    }
    
    if (init('action') == 'checkvolumecontrol') {
        $return=json_encode(karotz::checkVolumeControl());
        ajax::success($return);
    }
    
    if (init('action') == 'installvolumecontrol') {
        $return=json_encode(karotz::installVolumeControl());
        ajax::success($return);
    }
    
	if (init('action') == 'moveear') {
		$id = init('id');
		$karotz = karotz::byId($id);
		$right=init('right');
		$left=init('left');
		if (!is_numeric($right) || !is_numeric($left)){
			ajax::error('Veuillez saisir un nombre SVP');
		} else if (($right)<0 || ($right)>16 || ($left)<0 || ($left)>16 ){
			ajax::error('Veuillez saisir une valeur comprise entre 1 et 16');
		} else {
			karotz::moveearkarotz(init('id'),init('right'),init('left'));
			ajax::success();
		}
	}
	
	if (init('action') == 'ventrecouleur') {
		karotz::changeventrekarotz(init('id'),init('couleur'));
		ajax::success();
	}
    
    if (init('action') == 'tts') {
        if (init('message')==''){
            ajax::error('Veuillez saisir un message');
        }
		karotz::ttskarotz(init('id'),init('voice'),init('message'));
		ajax::success();
	}
    
    if (init('action') == 'sound') {
        if (init('sound')==''){
            ajax::error('Veuillez saisir un son');
        }
		karotz::soundkarotz(init('id'),init('sound'));
		ajax::success();
	}
    
    if (init('action') == 'url') {
        if (init('url')==''){
            ajax::error('Veuillez saisir une url');
        }
		karotz::urlkarotz(init('id'),init('url'));
		ajax::success();
	}
    
    if (init('action') == 'stop') {
		karotz::stopkarotz(init('id'));
		ajax::success();
	}
	log::add('karotz', 'debug', 'karotz.ajax.php volume action : '.init('action'));
    throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
    /*     * *********Catch exeption*************** */
} catch (Exception $e) {
    ajax::error(displayExeption($e), $e->getCode());
}
?>
