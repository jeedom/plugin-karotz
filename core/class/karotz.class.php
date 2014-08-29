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
						$type = "tts";
                        $parameters = str_replace('#message#',rawurlencode($_options['message']), $parameters);
						
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