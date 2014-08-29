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

global $listCmdKarotz;
$listCmdKarotz = array(
    array(
        'name' => 'Coucher',
        'configuration' => array(
            'request' => 'sleep',
            'parameters' => '',
        ),
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Le Karotz au dodo',
        'version' => '0.1',
        'required' => '',
    ),
    array(
        'name' => 'Debout',
        'configuration' => array(
            'request' => 'wakeup',
            'parameters' => 'silent=0',
        ),
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Le Karotz se réveille',
        'version' => '0.1',
        'required' => '',
    ),
    array(
        'name' => 'Couleur Led',
        'configuration' => array(
            'request' => 'leds',
            'parameters' => 'color=#color#',
        ),
        'type' => 'action',
        'subType' => 'color',
        'description' => 'Change la couleur de la led',
        'version' => '0.1',
        'required' => '',
    ),
    array(
        'name' => 'Ears Reset',
        'configuration' => array(
            'request' => 'ears_reset',
            'parameters' => '',
        ),
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Remise à zéro des oreilles',
        'version' => '0.1',
        'required' => '',
    ),
    array(
        'name' => 'Ears Random',
        'configuration' => array(
            'request' => 'ears_random',
            'parameters' => '',
        ),
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Position aléatoire des oreilles',
        'version' => '0.1',
        'required' => '',
    ),
    array(
        'name' => 'Humeur',
        'configuration' => array(
            'request' => 'apps/moods',
            'parameters' => '',
        ),
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Humeur du Karotz',
        'version' => '0.1',
        'required' => '',
    ),
    array(
        'name' => 'Horloge Parlante',
        'configuration' => array(
            'request' => 'apps/clock',
            'parameters' => '',
        ),
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Horloge Parlante',
        'version' => '0.1',
        'required' => '',
    ),
    array(
        'name' => 'Ears Random',
        'configuration' => array(
            'request' => 'ears_random',
            'parameters' => '',
        ),
        'type' => 'action',
        'subType' => 'other',
        'description' => 'Position aléatoire des oreilles',
        'version' => '0.1',
        'required' => '',
    ),
    array(
        'name' => 'TTS',
        'configuration' => array(
            'request' => 'tts',
            'parameters' => 'text=#message#',
        ),
        'type' => 'action',
        'subType' => 'message',
        'description' => 'Lit le message',
        'version' => '0.1',
        'required' => '',
    )
);
?>
