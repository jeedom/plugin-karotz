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

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class karotz extends eqLogic {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    public function preUpdate() {
        if ($this->getConfiguration('addr') == '') {
            throw new Exception(__('L\'adresse ne peut etre vide',__FILE__));
        }
    }
    
    public function postSave() {
        $coucher = $this->getCmd(null, 'coucher');
		if (!is_object($coucher)) {
			$coucher = new karotzCmd();
			$coucher->setLogicalId('coucher');
			$coucher->setIsVisible(1);
			$coucher->setName(__('Coucher', __FILE__));
		}
		$coucher->setType('action');
		$coucher->setSubType('other');
		$coucher->setEqLogic_id($this->getId());
        $coucher->setConfiguration('request', 'sleep');
		$coucher->setConfiguration('parameters', '');
		$coucher->save();
        
        $debout = $this->getCmd(null, 'debout');
		if (!is_object($debout)) {
			$debout = new karotzCmd();
			$debout->setLogicalId('debout');
			$debout->setIsVisible(1);
			$debout->setName(__('Debout', __FILE__));
		}
		$debout->setType('action');
		$debout->setSubType('other');
		$debout->setEqLogic_id($this->getId());
        $debout->setConfiguration('request', 'wakeup');
		$debout->setConfiguration('parameters', 'silent=0');
		$debout->save();
        
        $deboutsilent = $this->getCmd(null, 'deboutsilent');
		if (!is_object($deboutsilent)) {
			$deboutsilent = new karotzCmd();
			$deboutsilent->setLogicalId('deboutsilent');
			$deboutsilent->setIsVisible(1);
			$deboutsilent->setName(__('Debout Silencieux', __FILE__));
		}
		$deboutsilent->setType('action');
		$deboutsilent->setSubType('other');
		$deboutsilent->setEqLogic_id($this->getId());
        $deboutsilent->setConfiguration('request', 'wakeup');
		$deboutsilent->setConfiguration('parameters', 'silent=1');
		$deboutsilent->save();
        
        $couleur = $this->getCmd(null, 'couleur');
		if (!is_object($couleur)) {
			$couleur = new karotzCmd();
			$couleur->setLogicalId('couleur');
			$couleur->setIsVisible(1);
			$couleur->setName(__('Couleur Led', __FILE__));
		}
		$couleur->setType('action');
		$couleur->setSubType('color');
		$couleur->setEqLogic_id($this->getId());
        $couleur->setConfiguration('request', 'leds');
		$couleur->setConfiguration('parameters', 'color=#color#');
		$couleur->save();
        
        $oreilleraz = $this->getCmd(null, 'oreilleraz');
		if (!is_object($oreilleraz)) {
			$oreilleraz = new karotzCmd();
			$oreilleraz->setLogicalId('oreilleraz');
			$oreilleraz->setIsVisible(1);
			$oreilleraz->setName(__('Oreille RAZ', __FILE__));
		}
		$oreilleraz->setType('action');
		$oreilleraz->setSubType('other');
		$oreilleraz->setEqLogic_id($this->getId());
        $oreilleraz->setConfiguration('request', 'ears_reset');
		$oreilleraz->setConfiguration('parameters', '');
		$oreilleraz->save();
        
        $oreillerandom = $this->getCmd(null, 'oreillerandom');
		if (!is_object($oreillerandom)) {
			$oreillerandom = new karotzCmd();
			$oreillerandom->setLogicalId('oreillerandom');
			$oreillerandom->setIsVisible(1);
			$oreillerandom->setName(__('Oreille Aléatoire', __FILE__));
		}
		$oreillerandom->setType('action');
		$oreillerandom->setSubType('other');
		$oreillerandom->setEqLogic_id($this->getId());
        $oreillerandom->setConfiguration('request', 'ears_random');
		$oreillerandom->setConfiguration('parameters', 'noreset=1');
		$oreillerandom->save();
        
        $humeur = $this->getCmd(null, 'humeur');
		if (!is_object($humeur)) {
			$humeur = new karotzCmd();
			$humeur->setLogicalId('humeur');
			$humeur->setIsVisible(1);
			$humeur->setName(__('Humeur', __FILE__));
		}
		$humeur->setType('action');
		$humeur->setSubType('other');
		$humeur->setEqLogic_id($this->getId());
        $humeur->setConfiguration('request', 'apps/moods');
		$humeur->setConfiguration('parameters', '');
		$humeur->save();
        
        $clock = $this->getCmd(null, 'clock');
		if (!is_object($clock)) {
			$clock = new karotzCmd();
			$clock->setLogicalId('clock');
			$clock->setIsVisible(1);
			$clock->setName(__('Horloge', __FILE__));
		}
		$clock->setType('action');
		$clock->setSubType('other');
		$clock->setEqLogic_id($this->getId());
        $clock->setConfiguration('request', 'apps/clock');
		$clock->setConfiguration('parameters', '');
		$clock->save();
        
        $tts = $this->getCmd(null, 'tts');
		if (!is_object($tts)) {
			$tts = new karotzCmd();
			$tts->setLogicalId('tts');
			$tts->setIsVisible(1);
			$tts->setName(__('TTS', __FILE__));
		}
		$tts->setType('action');
		$tts->setSubType('message');
		$tts->setEqLogic_id($this->getId());
        $tts->setDisplay('title_disable', 1);
        $tts->setConfiguration('request', 'tts');
		$tts->setConfiguration('parameters', 'text=#message#');
		$tts->save();
        
        $sound = $this->getCmd(null, 'sound');
		if (!is_object($sound)) {
			$sound = new karotzCmd();
			$sound->setLogicalId('sound');
			$sound->setIsVisible(1);
			$sound->setName(__('Son du Karotz', __FILE__));
		}
		$sound->setType('action');
		$sound->setSubType('message');
		$sound->setEqLogic_id($this->getId());
        $sound->setDisplay('title_disable', 1);
        $sound->setConfiguration('request', 'sound');
        $sound->setDisplay('message_placeholder', __('Id du son', __FILE__));
		$sound->setConfiguration('parameters', 'id=#message#');
		$sound->save();
        
        $url = $this->getCmd(null, 'url');
		if (!is_object($url)) {
			$url = new karotzCmd();
			$url->setLogicalId('url');
			$url->setIsVisible(1);
			$url->setName(__('Son url', __FILE__));
		}
		$url->setType('action');
		$url->setSubType('message');
		$url->setEqLogic_id($this->getId());
        $url->setDisplay('title_disable', 1);
        $url->setConfiguration('request', 'sound');
        $url->setDisplay('message_placeholder', __('Url à Jouer', __FILE__));
		$url->setConfiguration('parameters', 'url=#message#');
		$url->save();
        
        $squeezeon = $this->getCmd(null, 'squeezeon');
		if (!is_object($squeezeon)) {
			$squeezeon = new karotzCmd();
			$squeezeon->setLogicalId('squeezeon');
			$squeezeon->setIsVisible(1);
			$squeezeon->setName(__('SqueezeBox On', __FILE__));
		}
		$squeezeon->setType('action');
		$squeezeon->setSubType('other');
		$squeezeon->setEqLogic_id($this->getId());
        $squeezeon->setConfiguration('request', 'squeezebox');
		$squeezeon->setConfiguration('parameters', 'cmd=start');
		$squeezeon->save();
        
        $squeezeoff = $this->getCmd(null, 'squeezeoff');
		if (!is_object($squeezeoff)) {
			$squeezeoff = new karotzCmd();
			$squeezeoff->setLogicalId('squeezeoff');
			$squeezeoff->setIsVisible(1);
			$squeezeoff->setName(__('SqueezeBox Off', __FILE__));
		}
		$squeezeoff->setType('action');
		$squeezeoff->setSubType('other');
		$squeezeoff->setEqLogic_id($this->getId());
        $squeezeoff->setConfiguration('request', 'squeezebox');
		$squeezeoff->setConfiguration('parameters', 'cmd=stop');
		$squeezeoff->save();
        
        $oreillepos = $this->getCmd(null, 'oreillepos');
		if (!is_object($oreillepos)) {
			$oreillepos = new karotzCmd();
			$oreillepos->setLogicalId('oreillepos');
			$oreillepos->setIsVisible(1);
			$oreillepos->setName(__('Oreilles Positions', __FILE__));
		}
		$oreillepos->setType('action');
		$oreillepos->setSubType('message');
        $oreillepos->setDisplay('message_placeholder', __('Oreille Droite [0-16]', __FILE__));
        $oreillepos->setDisplay('title_placeholder', __('Oreille Gauche [0-16]', __FILE__));
		$oreillepos->setEqLogic_id($this->getId());
        $oreillepos->setConfiguration('request', 'ears');
		$oreillepos->setConfiguration('parameters', 'right=#message#&left=#title#&noreset=1');
		$oreillepos->save();
    }
}

class karotzCmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    public function preSave() {
        if ($this->getConfiguration('request') == '') {
            throw new Exception(__('La requete ne peut etre vide',__FILE__));
        }
    }

    public function execute($_options = null) {
    	
		$karotz = $this->getEqLogic();
        $requestHeader = 'http://' . $karotz->getConfiguration('addr') . '/cgi-bin/';
		$type=$this->getConfiguration('request');
		
        if ($this->getConfiguration('parameters') == '') {
            $request = $requestHeader.$type;
        } else {
            $parameters = $this->getConfiguration('parameters');
			
            if ($this->type == 'action' && $_options != null) {
                switch ($this->subType) {
                    case 'message':
                        $type=$this->getConfiguration('request');
                        if ($this->getLogicalId() == 'tts') {
                            $parameters = str_replace('#message#',rawurlencode($_options['message']), $parameters);
                        }
                        else {
                            $parameters = str_replace('#message#',$_options['message'], $parameters);
                        }
                        $parameters = str_replace('#title#',rawurlencode($_options['title']), $parameters);
						log::add('karotz','debug','Execution de la commande suivante : ' .$parameters);
                        break;
                    case 'slider':
						$type=$this->getConfiguration('request');
                        $parameters = str_replace('#slider#', $_options['slider'], $parameters);
                        break;
					case 'color':
						$type=$this->getConfiguration('request');
                        $parameters = str_replace('#color#', $_options['color'], $parameters);
						$parameters = str_replace('#', '', $parameters);
                        break;
                    default:
						$type=$this->getConfiguration('request');
                        break;
                }
            }
        $request = $requestHeader.$type.'?'.$parameters;
		}
        
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $request);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    

    /*     * **********************Getteur Setteur*************************** */
}
}
?>