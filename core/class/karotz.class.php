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
		if ($_eqlogic_id !== null) {
			$eqLogics = array(eqLogic::byId($_eqlogic_id));
		} else {
			$eqLogics = eqLogic::byType('karotz');
		}
		foreach ($eqLogics as $karotz) {
			if ($karotz->getIsEnable() == 1) {
				$request = 'http://' . $karotz->getConfiguration('addr') . '/cgi-bin/status';
				$request = new com_http($request);
				$jsonstatus = json_decode($request->exec(5, 1), true);
				$change = false;
				foreach ($karotz->getCmd() as $cmd) {
					if (!isset($jsonstatus[$cmd->getLogicalId()])) {
						continue;
					}
					$value = $jsonstatus[$cmd->getLogicalId()];
					if ($cmd->getLogicalId() == 'led_color') {
						$value = '#' . $value;
					}
					if ($cmd->execCmd() !== $cmd->formatValue($value)) {
						$cmd->setCollectDate('');
						$cmd->event($value);
						$change = true;
					}
				}
				if ($change) {
					$karotz->refreshWidget();
				}
			}
		}
	}

	public function postUpdate() {
		$this->cron30($this->getId());
	}

	public function postSave() {
		$cmd = $this->getCmd(null, 'sleeping');
		if (!is_object($cmd)) {
			$cmd = new karotzCmd();
			$cmd->setLogicalId('sleeping');
			$cmd->setIsVisible(1);
			$cmd->setName(__('Coucher', __FILE__));
		}
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setConfiguration('request', 'sleep');
		$cmd->setConfiguration('parameters', '');
		$cmd->save();

		$cmd = $this->getCmd(null, 'wakeup');
		if (!is_object($cmd)) {
			$cmd = new karotzCmd();
			$cmd->setLogicalId('wakeup');
			$cmd->setIsVisible(1);
			$cmd->setName(__('Debout', __FILE__));
		}
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setConfiguration('request', 'wakeup');
		$cmd->setConfiguration('parameters', 'silent=0');
		$cmd->save();

		$cmd = $this->getCmd(null, 'color');
		if (!is_object($cmd)) {
			$cmd = new karotzCmd();
			$cmd->setLogicalId('color');
			$cmd->setIsVisible(1);
			$cmd->setName(__('Couleur Led', __FILE__));
		}
		$cmd->setType('action');
		$cmd->setSubType('color');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setConfiguration('request', 'leds');
		$cmd->setConfiguration('parameters', 'color=#color#');
		$cmd->save();

		$cmd = $this->getCmd(null, 'earraz');
		if (!is_object($cmd)) {
			$cmd = new karotzCmd();
			$cmd->setLogicalId('earraz');
			$cmd->setIsVisible(1);
			$cmd->setName(__('Oreille RAZ', __FILE__));
		}
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setConfiguration('request', 'ears_reset');
		$cmd->setConfiguration('parameters', '');
		$cmd->save();

		if ($this->getConfiguration('enablemoods') == 1) {
			$cmd = $this->getCmd(null, 'moods');
			if (!is_object($cmd)) {
				$cmd = new karotzCmd();
				$cmd->setLogicalId('moods');
				$cmd->setIsVisible(1);
				$cmd->setName(__('Humeur', __FILE__));
			}
			$cmd->setType('action');
			$cmd->setSubType('other');
			$cmd->setEqLogic_id($this->getId());
			$cmd->setConfiguration('request', 'apps/moods');
			$cmd->setConfiguration('parameters', '');
			$cmd->save();

		} else {
			$cmd = $this->getCmd(null, 'moods');
			if (is_object($cmd)) {
				$cmd->remove();
			}
		}
		if ($this->getConfiguration('enableclock') == 1) {
			$cmd = $this->getCmd(null, 'clock');
			if (!is_object($cmd)) {
				$cmd = new karotzCmd();
				$cmd->setLogicalId('clock');
				$cmd->setIsVisible(1);
				$cmd->setName(__('Horloge', __FILE__));
			}
			$cmd->setType('action');
			$cmd->setSubType('other');
			$cmd->setEqLogic_id($this->getId());
			$cmd->setConfiguration('request', 'apps/clock');
			$cmd->setConfiguration('parameters', '');
			$cmd->save();
		} else {
			$cmd = $this->getCmd(null, 'clock');
			if (is_object($cmd)) {
				$cmd->remove();
			}
		}

		$cmd = $this->getCmd(null, 'tts');
		if (!is_object($cmd)) {
			$cmd = new karotzCmd();
			$cmd->setLogicalId('tts');
			$cmd->setIsVisible(1);
			$cmd->setName(__('Dit', __FILE__));
		}
		$cmd->setType('action');
		$cmd->setSubType('message');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setDisplay('title_placeholder', __('Options', __FILE__));
		$cmd->setDisplay('message_placeholder', __('Phrase', __FILE__));
		$cmd->setConfiguration('request', 'tts');
		$cmd->setConfiguration('parameters', 'text=#message#&#title#');
		$cmd->save();

		$cmd = $this->getCmd(null, 'sound');
		if (!is_object($cmd)) {
			$cmd = new karotzCmd();
			$cmd->setLogicalId('sound');
			$cmd->setIsVisible(1);
			$cmd->setName(__('Son du Karotz', __FILE__));
		}
		$cmd->setType('action');
		$cmd->setSubType('message');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setDisplay('title_disable', 1);
		$cmd->setConfiguration('request', 'sound');
		$cmd->setDisplay('message_placeholder', __('Id du son', __FILE__));
		$cmd->setConfiguration('parameters', 'id=#message#');
		$cmd->save();

		$cmd = $this->getCmd(null, 'stopsound');
		if (!is_object($cmd)) {
			$cmd = new karotzCmd();
			$cmd->setLogicalId('stopsound');
			$cmd->setIsVisible(1);
			$cmd->setName(__('Arrêter son', __FILE__));
		}
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setConfiguration('request', 'sound_control');
		$cmd->setConfiguration('parameters', 'cmd=quit');
		$cmd->save();

		$cmd = $this->getCmd(null, 'url');
		if (!is_object($cmd)) {
			$cmd = new karotzCmd();
			$cmd->setLogicalId('url');
			$cmd->setName(__('Son url', __FILE__));
		}
		$cmd->setType('action');
		$cmd->setSubType('message');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setDisplay('title_disable', 1);
		$cmd->setConfiguration('request', 'sound');
		$cmd->setDisplay('message_placeholder', __('Url à Jouer', __FILE__));
		$cmd->setConfiguration('parameters', 'url=#message#');
		$cmd->save();

		if ($this->getConfiguration('enablesqueezebox') == 1) {
			$cmd = $this->getCmd(null, 'squeezeon');
			if (!is_object($cmd)) {
				$cmd = new karotzCmd();
				$cmd->setLogicalId('squeezeon');
				$cmd->setIsVisible(1);
				$cmd->setName(__('SqueezeBox On', __FILE__));
			}
			$cmd->setType('action');
			$cmd->setSubType('other');
			$cmd->setEqLogic_id($this->getId());
			$cmd->setConfiguration('request', 'squeezebox');
			$cmd->setConfiguration('parameters', 'cmd=start');
			$cmd->save();

			$cmd = $this->getCmd(null, 'squeezeoff');
			if (!is_object($cmd)) {
				$cmd = new karotzCmd();
				$cmd->setLogicalId('squeezeoff');
				$cmd->setIsVisible(1);
				$cmd->setName(__('SqueezeBox Off', __FILE__));
			}
			$cmd->setType('action');
			$cmd->setSubType('other');
			$cmd->setEqLogic_id($this->getId());
			$cmd->setConfiguration('request', 'squeezebox');
			$cmd->setConfiguration('parameters', 'cmd=stop');
			$cmd->save();
		} else {
			$cmd = $this->getCmd(null, 'squeezeon');
			if (is_object($cmd)) {
				$cmd->remove();
			}
			$cmd = $this->getCmd(null, 'squeezeoff');
			if (is_object($squeezeoff)) {
				$squeezeoff->remove();
			}
		}

		$cmd = $this->getCmd(null, 'pulseon');
		if (!is_object($cmd)) {
			$cmd = new karotzCmd();
			$cmd->setLogicalId('pulseon');
			$cmd->setIsVisible(1);
			$cmd->setName(__('Clignotement On', __FILE__));
		}
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setConfiguration('request', 'leds');
		$cmd->setConfiguration('parameters', 'pulse=1');
		$cmd->save();

		$cmd = $this->getCmd(null, 'pulseoff');
		if (!is_object($cmd)) {
			$cmd = new karotzCmd();
			$cmd->setLogicalId('pulseoff');
			$cmd->setIsVisible(1);
			$cmd->setName(__('Clignotement Off', __FILE__));
		}
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setConfiguration('request', 'leds');
		$cmd->setConfiguration('parameters', 'pulse=0');
		$cmd->save();

		$cmd = $this->getCmd(null, 'led_pulse');
		if (!is_object($cmd)) {
			$cmd = new karotzCmd();
			$cmd->setLogicalId('led_pulse');
			$cmd->setIsVisible(1);
			$cmd->setName(__('Clignotement', __FILE__));
		}
		$cmd->setType('info');
		$cmd->setSubType('binary');
		$cmd->setEqLogic_id($this->getId());
		$cmd->save();

		$cmd = $this->getCmd(null, 'earspos');
		if (!is_object($cmd)) {
			$cmd = new karotzCmd();
			$cmd->setLogicalId('earspos');
			$cmd->setIsVisible(1);
			$cmd->setName(__('Oreilles Positions', __FILE__));
		}
		$cmd->setType('action');
		$cmd->setSubType('message');
		$cmd->setDisplay('message_placeholder', __('Oreille Droite [0-16]', __FILE__));
		$cmd->setDisplay('title_placeholder', __('Oreille Gauche [0-16]', __FILE__));
		$cmd->setEqLogic_id($this->getId());
		$cmd->setConfiguration('request', 'ears');
		$cmd->setConfiguration('parameters', 'right=#message#&left=#title#&noreset=1');
		$cmd->save();

		$cmd = $this->getCmd(null, 'sleep');
		if (!is_object($cmd)) {
			$cmd = new karotzCmd();
			$cmd->setLogicalId('sleep');
			$cmd->setIsVisible(1);
			$cmd->setName(__('Statut', __FILE__));
		}
		$cmd->setType('info');
		$cmd->setSubType('binary');
		$cmd->setEventOnly(1);
		$cmd->setEqLogic_id($this->getId());
		$cmd->save();

		$cmd = $this->getCmd(null, 'led_color');
		if (!is_object($cmd)) {
			$cmd = new karotzCmd();
			$cmd->setLogicalId('led_color');
			$cmd->setIsVisible(1);
			$cmd->setName(__('Statut Couleur', __FILE__));
		}
		$cmd->setType('info');
		$cmd->setEventOnly(1);
		$cmd->setSubType('string');
		$cmd->setEqLogic_id($this->getId());
		$cmd->save();

		$cmd = $this->getCmd(null, 'refresh');
		if (!is_object($cmd)) {
			$cmd = new karotzCmd();
			$cmd->setLogicalId('refresh');
			$cmd->setIsVisible(1);
			$cmd->setName(__('Rafraichir', __FILE__));
		}
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setEqLogic_id($this->getId());
		$cmd->save();
	}

	public function toHtml($_version = 'dashboard') {
		$replace = $this->preToHtml($_version);
		if (!is_array($replace)) {
			return $replace;
		}
		$version = jeedom::versionAlias($_version);
		foreach ($this->getCmd('info') as $cmd) {
			$replace['#' . $cmd->getLogicalId() . '#'] = $cmd->execCmd();
			$replace['#' . $cmd->getLogicalId() . '_id#'] = $cmd->getId();
			if ($cmd->getIsHistorized() == 1) {
				$replace['#' . $cmd->getLogicalId() . '_history#'] = 'history cursor';
			}
		}
		if ($replace['#sleep#'] == 0) {
			$replace['#state#'] = 'awake';
			$replace['#actionstate#'] = __('Endormir le Karotz', __FILE__);
		} else {
			$replace['#state#'] = 'sleep';
			$replace['#actionstate#'] = __('Réveiller le Karotz', __FILE__);
		}
		$replace['#enablesqueezebox#'] = $this->getConfiguration('enablesqueezebox', 0);
		$replace['#enablemoods#'] = $this->getConfiguration('enablemoods', 0);
		$replace['#enableclock#'] = $this->getConfiguration('enableclock', 0);
		foreach ($this->getCmd('info') as $cmd) {
			$replace['#' . $cmd->getLogicalId() . '_history#'] = '';
			$replace['#' . $cmd->getLogicalId() . '_id#'] = $cmd->getId();
			$replace['#' . $cmd->getLogicalId() . '#'] = $cmd->execCmd();
			$replace['#' . $cmd->getLogicalId() . '_collect#'] = $cmd->getCollectDate();
			if ($cmd->getIsHistorized() == 1) {
				$replace['#' . $cmd->getLogicalId() . '_history#'] = 'history cursor';
			}

		}
		foreach ($this->getCmd('action') as $cmd) {
			$replace['#' . $cmd->getLogicalId() . '_id#'] = $cmd->getId();
		}
		$html = template_replace($replace, getTemplate('core', $version, 'karotz', 'karotz'));
		cache::set('widgetHtml' . $version . $this->getId(), $html, 0);
		return $html;
	}

}

class karotzCmd extends cmd {
	/*     * *************************Attributs****************************** */

	/*     * ***********************Methode static*************************** */

	/*     * *********************Methode d'instance************************* */

	public function dontRemoveCmd() {
		return true;
	}

	public function execute($_options = null) {
		$karotz = $this->getEqLogic();
		if ($this->getLogicalId() == 'refresh') {
			$karotz->cron30($karotz->getId());
			return true;
		}
		if ($this->type != 'action') {
			return;
		}
		$requestHeader = 'http://' . $karotz->getConfiguration('addr') . '/cgi-bin/';
		$type = $this->getConfiguration('request');
		if ($this->getConfiguration('parameters') == '') {
			$request = $requestHeader . $type;
		} else {
			$parameters = $this->getConfiguration('parameters');
			if ($_options != null) {
				switch ($this->getSubType()) {
					case 'message':
						if ($this->getLogicalId() == 'tts') {
							$parameters = str_replace('#message#', rawurlencode($_options['message']), $parameters);
							if ($_options['title'] != null && $_options['title'] && strpos($_options['title'], ' ') === false) {
								$parameters = str_replace('#title#', $_options['title'], $parameters);
							} else {
								$parameters = str_replace('#title#', '', $parameters);
							}
							$parameters = trim($parameters, '&');
							if ($karotz->getConfiguration('ttsengine') != 0 && strpos($parameters, 'engine') === false) {
								$parameters .= '&engine=' . $karotz->getConfiguration('ttsengine');
							}
						} else {
							$parameters = str_replace('#message#', $_options['message'], $parameters);
							$parameters = str_replace('#title#', rawurlencode($_options['title']), $parameters);
						}
						break;
					case 'slider':
						$parameters = str_replace('#slider#', $_options['slider'], $parameters);
						break;
					case 'color':
						$parameters = str_replace('#', '', str_replace('#color#', $_options['color'], $parameters));
						break;
				}
			}
			$request = $requestHeader . $type . '?' . $parameters;
		}
		$request = new com_http($request);
		$request->exec(10, 1);
		if ($this->getLogicalId() == 'color') {
			$led_pulse = $karotz->getCmd('info', 'led_pulse');
			if (is_object($led_pulse) && $led_pulse->execCmd() == 1) {
				$pulseon = $karotz->getCmd(null, 'pulseon');
				if (is_object($pulseon)) {
					$pulseon->execCmd();
				}
			}
		}
		if (in_array($this->getLogicalId(), array('wakeup', 'sleeping', 'color', 'pulseon', 'pulseoff'))) {
			sleep(1);
			$karotz->cron30($karotz->getId());
		}
		return $response;
	}

	/*     * **********************Getteur Setteur*************************** */
}
?>