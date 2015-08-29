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
    
    public function cron30($_eqlogic_id = null) {
        if($_eqlogic_id !== null){
			$eqLogics = array(eqLogic::byId($_eqlogic_id));
		}else{
			$eqLogics = eqLogic::byType('karotz');
		}
        foreach ($eqLogics as $karotz) {
            if ($karotz->getIsEnable() == 1) {
				log::add('karotz', 'debug', 'Pull Cron pour Karotz');
                $request = 'http://' . $karotz->getConfiguration('addr') . '/cgi-bin/status';
                $response=$karotz->executerequest($request);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_URL, $request);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                $response = curl_exec($ch);
                curl_close($ch);
                $jsonstatus=json_decode($response, true);
                $statut=isset($jsonstatus['sleep']) ? $jsonstatus['sleep'] : "old";
                $color=isset($jsonstatus['led_color']) ? $jsonstatus['led_color'] : "old";
                foreach ($karotz->getCmd('info') as $cmd) {
					switch ($cmd->getName()) {
						case 'Statut':
                            $value='Réveillé';
                            if ($statut==1){
                                $value='Endormi';
                            }
                            break;
                        case 'Statut Couleur':
                            $value=$color;
                            if ($color!='old') {
                                $value='#'.$color;
                            }
						break;
                    }
                    if ($value==0 ||$value != 'old'){
						$cmd->event($value);
						log::add('karotz','debug','set:'.$cmd->getName().' to '. $value);
					}
				}
				$karotz->refreshWidget();
            }
        }
    }
    
    public static function executerequest($request) {
        log::add('karotz', 'debug', $request);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $request);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
    
    public function changeventrekarotz($id,$color) {
		$karotz = karotz::byId($id);
        $request = 'http://' . $karotz->getConfiguration('addr') . '/cgi-bin/leds?color='.str_replace('#', '', $color);
		$karotz->executerequest($request);
        $karotz->cron30($id);
	}
    
    public function moveearkarotz($id,$right,$left) {
		$karotz = karotz::byId($id);
        $request = 'http://' . $karotz->getConfiguration('addr') . '/cgi-bin/ears?right='.$right.'&left='.$left.'&noreset=1';
		$karotz->executerequest($request);
	}
    
    public function ttskarotz($id,$voice,$message) {
		$karotz = karotz::byId($id);
        $request = 'http://' . $karotz->getConfiguration('addr') . '/cgi-bin/tts?voice='.$voice.'&text='.rawurlencode($message);
		$karotz->executerequest($request);
	}
    
    public function soundkarotz($id,$sound) {
		$karotz = karotz::byId($id);
        $request = 'http://' . $karotz->getConfiguration('addr') . '/cgi-bin/sound?id='.$sound;
		$karotz->executerequest($request);
	}
    
    public function urlkarotz($id,$url) {
		$karotz = karotz::byId($id);
        $request = 'http://' . $karotz->getConfiguration('addr') . '/cgi-bin/sound?url='.$url;
		$karotz->executerequest($request);
	}
    
    public function stopkarotz($id) {
		$karotz = karotz::byId($id);
        $request = 'http://' . $karotz->getConfiguration('addr') . '/cgi-bin/sound_control?cmd=quit';
		$karotz->executerequest($request);
	}
    
    public function postUpdate() {
        $this->cron30($this->getId());
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
        $tts->setDisplay('title_placeholder', __('Voix', __FILE__));
        $tts->setDisplay('message_placeholder', __('Phrase', __FILE__));
        $tts->setConfiguration('request', 'tts');
		$tts->setConfiguration('parameters', 'text=#message#&voice=#title#');
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
        
        $stopsound = $this->getCmd(null, 'stopsound');
		if (!is_object($stopsound)) {
			$stopsound = new karotzCmd();
			$stopsound->setLogicalId('stopsound');
			$stopsound->setIsVisible(1);
			$stopsound->setName(__('Arrêter son', __FILE__));
		}
		$stopsound->setType('action');
		$stopsound->setSubType('other');
		$stopsound->setEqLogic_id($this->getId());
        $stopsound->setConfiguration('request', 'sound_control');
		$stopsound->setConfiguration('parameters', 'cmd=quit');
		$stopsound->save();
        
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
        
        $pulseon = $this->getCmd(null, 'pulseon');
		if (!is_object($pulseon)) {
			$pulseon = new karotzCmd();
			$pulseon->setLogicalId('pulseon');
			$pulseon->setIsVisible(1);
			$pulseon->setName(__('Clignotement On', __FILE__));
		}
		$pulseon->setType('action');
		$pulseon->setSubType('other');
		$pulseon->setEqLogic_id($this->getId());
        $pulseon->setConfiguration('request', 'leds');
		$pulseon->setConfiguration('parameters', 'pulse=1');
		$pulseon->save();
        
        $pulseoff = $this->getCmd(null, 'pulseoff');
		if (!is_object($pulseoff)) {
			$pulseoff = new karotzCmd();
			$pulseoff->setLogicalId('pulseoff');
			$pulseoff->setIsVisible(1);
			$pulseoff->setName(__('Clignotement Off', __FILE__));
		}
		$pulseoff->setType('action');
		$pulseoff->setSubType('other');
		$pulseoff->setEqLogic_id($this->getId());
        $pulseoff->setConfiguration('request', 'leds');
		$pulseoff->setConfiguration('parameters', 'pulse=0');
		$pulseoff->save();
        
        $pulsespeed = $this->getCmd(null, 'pulsespeed');
		if (!is_object($pulsespeed)) {
			$pulsespeed = new karotzCmd();
			$pulsespeed->setLogicalId('pulsespeed');
			$pulsespeed->setIsVisible(1);
			$pulsespeed->setName(__('Vitesse Pulse', __FILE__));
		}
		$pulsespeed->setType('action');
		$pulsespeed->setSubType('slider');
		$pulsespeed->setConfiguration('minValue', 0);
		$pulsespeed->setConfiguration('maxValue', 2000);
		$pulsespeed->setEqLogic_id($this->getId());
        $pulsespeed->setConfiguration('request', 'leds');
		$pulsespeed->setConfiguration('parameters', 'speed=#slider#&pulse=1');
		$pulsespeed->save();
        
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
        
        $etat = $this->getCmd(null, 'etat');
		if (!is_object($etat)) {
			$etat = new karotzCmd();
			$etat->setLogicalId('etat');
			$etat->setIsVisible(1);
			$etat->setName(__('Statut', __FILE__));
		}
		$etat->setType('info');
		$etat->setSubType('string');
		$etat->setEventOnly(1);
        $etat->setEqLogic_id($this->getId());
        $etat->save();
        
        $couleurstatut = $this->getCmd(null, 'couleurstatut');
		if (!is_object($couleurstatut)) {
			$couleurstatut = new karotzCmd();
			$couleurstatut->setLogicalId('couleurstatut');
			$couleurstatut->setIsVisible(1);
			$couleurstatut->setName(__('Statut Couleur', __FILE__));
		}
		$couleurstatut->setType('info');
		$couleurstatut->setEventOnly(1);
		$couleurstatut->setSubType('string');
        $couleurstatut->setEqLogic_id($this->getId());
        $couleurstatut->save();
        
        $refresh = $this->getCmd(null, 'refresh');
		if (!is_object($refresh)) {
			$refresh = new karotzCmd();
			$refresh->setLogicalId('refresh');
			$refresh->setIsVisible(1);
			$refresh->setName(__('Rafraichir', __FILE__));
		}
		$refresh->setType('action');
		$refresh->setSubType('other');
		$refresh->setEqLogic_id($this->getId());
		$refresh->save();
    }
    
    public function toHtml($_version = 'dashboard') {
		if ($this->getIsEnable() != 1) {
			return '';
		}
		if (!$this->hasRight('r')) {
			return '';
		}
        if (is_object($this->getCmd(null,'etat')) && $this->getCmd(null,'etat')->execCmd()=='Réveillé'){
			$state='awake';
            $action='Endormir le Karotz';
		} else {
			$state='sleep';
            $action='Réveiller le Karotz';
		}
		$_version = jeedom::versionAlias($_version);
		$background=$this->getBackgroundColor($_version);
		$replace = array(
			'#name#' => $this->getName(),
			'#id#' => $this->getId(),
			'#background_color#' => $background,
			'#eqLink#' => $this->getLinkToConfiguration(),
			'#state#' => $state,
            '#actionstate#'=> $action,
		);
		foreach ($this->getCmd('info') as $cmd) {
			$replace['#' . $cmd->getLogicalId() . '_history#'] = '';
                $replace['#' . $cmd->getLogicalId() . '_id#'] = $cmd->getId();
				$replace['#' . $cmd->getLogicalId() . '#'] = $cmd->execCmd();
				$replace['#' . $cmd->getLogicalId() . '_collect#'] = $cmd->getCollectDate();
				if ($cmd->getIsHistorized() == 1) {
					$replace['#' . $cmd->getLogicalId() . '_history#'] = 'history cursor';
				}
			
		}

		$refresh = $this->getCmd(null, 'refresh');
        if (is_object($refresh)) {
		$replace['#refresh_id#'] = $refresh->getId();
        }
        $reveille = $this->getCmd(null, 'debout');
        if (is_object($reveille)) {
		$replace['#reveille_id#'] = $reveille->getId();
        }
        
        $dort = $this->getCmd(null, 'coucher');
        if (is_object($dort)) {
		$replace['#dort_id#'] = $dort->getId();
        }
        
        $oreillerandom = $this->getCmd(null, 'oreillerandom');
        if (is_object($oreillerandom)) {
		$replace['#oreillerandom_id#'] = $oreillerandom->getId();
        }
        
        $oreillereset = $this->getCmd(null, 'oreilleraz');
        if (is_object($oreillereset)) {
		$replace['#oreillereset_id#'] = $oreillereset->getId();
        }
        
        $clock = $this->getCmd(null, 'clock');
        if (is_object($clock)) {
		$replace['#clock_id#'] = $clock->getId();
        }
        
        $humeur = $this->getCmd(null, 'humeur');
        if (is_object($humeur)) {
		$replace['#humeur_id#'] = $humeur->getId();
        }
        
        $squeezeon = $this->getCmd(null, 'squeezeon');
        if (is_object($squeezeon)) {
		$replace['#squeezeon_id#'] = $squeezeon->getId();
        }
        
        $squeezeoff = $this->getCmd(null, 'squeezeoff');
        if (is_object($squeezeoff)) {
		$replace['#squeezeoff_id#'] = $squeezeoff->getId();
        }
        
        $pulseon = $this->getCmd(null, 'pulseon');
        if (is_object($pulseon)) {
		$replace['#pulseon_id#'] = $pulseon->getId();
        }
        
        $pulseoff = $this->getCmd(null, 'pulseoff');
        if (is_object($pulseoff)) {
		$replace['#pulseoff_id#'] = $pulseoff->getId();
        }
        
        $pulsespeed = $this->getCmd(null, 'pulsespeed');
        if (is_object($pulsespeed)) {
		$replace['#pulsespeed_id#'] = $pulsespeed->getId();
        }
		$parameters = $this->getDisplay('parameters');
		if (is_array($parameters)) {
			foreach ($parameters as $key => $value) {
				$replace['#' . $key . '#'] = $value;
			}
		}

		$html = template_replace($replace, getTemplate('core', $_version, 'karotz', 'karotz'));
		return $html;
	}
}

class karotzCmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    public function execute($_options = null) {
    	if ($this->getType() == '') {
			return '';
		}
        $karotz = $this->getEqLogic();
        if ($this->getLogicalId() == 'refresh') {
            $karotz->cron30($karotz->getId());
            return true;
        }
        if ($this->type == 'action') {
            $requestHeader = 'http://' . $karotz->getConfiguration('addr') . '/cgi-bin/';
            $type=$this->getConfiguration('request');
            
            if ($this->getConfiguration('parameters') == '') {
                $request = $requestHeader.$type;
            } else {
                $parameters = $this->getConfiguration('parameters');
			
            if ($_options != null) {
                switch ($this->subType) {
                    case 'message':
                        $type=$this->getConfiguration('request');
                        if ($this->getLogicalId() == 'tts') {
                            $parameters = str_replace('#message#',rawurlencode($_options['message']), $parameters);
                            if ($_options != null && $_options!='#title#'){
                                $parameters = str_replace('#title#',rawurlencode($_options['title']), $parameters);
                            } else {
                                $parameters = str_replace('#title#','', $parameters);
                            }
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
        $response=$karotz->executerequest($request);
        if (in_array($this->getLogicalId() , array('debout','deboutsilent','coucher','couleur'))) {
            sleep(1);
            $karotz->cron30($karotz->getId());
        }
        return $response;
        }

    /*     * **********************Getteur Setteur*************************** */
}
}
?>