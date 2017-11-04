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

	/***************************Attributs*******************************/

	public static $_widgetPossibility = array('custom' => true, 'custom::layout' => false);

	/*     * ***********************Methode static*************************** */

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
					if ($cmd->getLogicalId() == 'volume') {
					    //log::add('karotz', 'debug', 'raw volume db : '.$value);
					    if ($value=="") {
					        $value=-10;
					    }
					    $value = ($value+30)*100/40;
					    //log::add('karotz', 'debug', '% volume : '.$value);
					}
					if ($cmd->execCmd() !== $cmd->formatValue($value)) {
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
		}
		$cmd->setName(__('Coucher', __FILE__));
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
		}
		$cmd->setName(__('Debout', __FILE__));
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setConfiguration('request', 'wakeup');
		$cmd->setConfiguration('parameters', 'silent=0');
		$cmd->save();

		$cmd = $this->getCmd(null, 'wakeupSilent');
		if (!is_object($cmd)) {
		    $cmd = new karotzCmd();
		    $cmd->setLogicalId('wakeupSilent');
		    $cmd->setIsVisible(1);
		}
		$cmd->setName(__('Debout Silencieux', __FILE__));
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setConfiguration('request', 'wakeup');
		$cmd->setConfiguration('parameters', 'silent=1');
		$cmd->save();
		
		$cmd = $this->getCmd(null, 'color');
		if (!is_object($cmd)) {
			$cmd = new karotzCmd();
			$cmd->setLogicalId('color');
			$cmd->setIsVisible(1);
		}
		$cmd->setName(__('Couleur Led', __FILE__));
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
		}
		$cmd->setName(__('Oreille RàZ', __FILE__));
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
			}
			$cmd->setName(__('Humeur aléatoire', __FILE__));
			$cmd->setType('action');
			$cmd->setSubType('other');
			$cmd->setEqLogic_id($this->getId());
			$cmd->setConfiguration('request', 'apps/moods');
			$cmd->setConfiguration('parameters', '');
			$cmd->save();
			
			$cmd = $this->getCmd(null, 'moodSelect');
			if (!is_object($cmd)) {
			    $cmd = new karotzCmd();
			    $cmd->setLogicalId('moodSelect');
			    $cmd->setIsVisible(1);
			}
			$cmd->setName(__('Humeur', __FILE__));
			$cmd->setType('action');
			$cmd->setSubType('select');
			$cmd->setEqLogic_id($this->getId());
			$cmd->setConfiguration('request', 'apps/moods');
			$cmd->setConfiguration('parameters', 'id=#select#');
			$cmd->setConfiguration('listValue',"1|Ronflements;2|Bâillement;3|Eternuement Pardon !;4|Arracher les orties.;5|Bonjour ! Je suis un lapin, je suis gentil. Ha ha ha ha...;6|Na na ni, na na na...;7|Guili guili guili guili.;8|Et vous, euh, vous en pensez quoi de la réforme sur les garennes ...;9|Euh, pardon, excusez-moi s'il vous plait, pardon euh ...;10|J'mangerais bien un rouleau de printemps, ou une choucroute ...;11|Hep ! Non, rien.;12|Aaaah ! Vous m'avez fait peur !;13|Les issues de secours sont situées à l'avant et à l'arrière de l'appareil.;14|Euh ! des yaourts, du café...;15|Et alors, tu fais les mêmes mouvements que moi. Euh ! comme ça ;16|Hummm ! Y a pas moyen d'être tranquille, hein !;17|Hum ! J'ai faim ! Quand est-ce qu'on mange ?;18|Tada !!;19|Enhhh ! Mais qu'est-ce que c'est qu'cette lampe ?;20|Et des stylos...;21|Hum,hum,hum,humhum (en rythme);22|Oué euh, chez pas, peut-être.;23|Non mais c'est vrai quand on y pense.;24|Bien sure, tout le monde devrait faire ça, c'est évident.;25|être extravagant ? Mais pourquoi ?;26|J'ai l'impression que la polarité c'est inversée (chuchotement).;27|ça, c'est la goutte qui va peut-être faire déborder le vase.;28|Mais pourquoi y font ça ?;29|Hum ! ça serait bien !;30|Comme quoi, euh !;31|Abracadabra !;32|Oui, mais euh, ça reste son ami quand même !;33|Comment ça s'écrit déjà ?;34|Euh, 5 et 6 onze, et 3 quatorze, non mais attend ça doit faire 15, euh, 15 oui.;35|Euh, peut-être l'année prochaine, hein ?;36|Euh, ça dépend, c'est sûr !;37|Alors c'était quoi déjà euh ? Euh 4 oeufs, 120 grammes de sucre ...;38|Euh, accepter les conseils des autres, d'accord mais, euh ...;39|Ah, des réponses, des réponses, si seulement on savait !;40|J'ai encore faim !;41|Alors ça, euh, c'est fait !;42|Ho ! C'est mieux qu'hier !;43|Y a des jours quand même, euh...;44|Je pense qu'il faut, en toute circonstance, afficher un optimisme triomphant !;45|Ce serait quand même exagéré d'se plaindre !;46|How do you do ?;47|Of course, hein ! That's obvious that mean.;48|Bla Bla !;49| I'm happy !;50|What's that ? Qui ? A what ? Qui ? What's that ?;51|What's that ?;52|Now, repeat after me.;53|Pick a boo.;54|I'm a rabbit.;55|What do you mean, Flash Gordon ?;56|ça suffit maintenant !;57|Bin non, j'chez pas moi ...;58|Dans l'ensemble, euh, j'suis plutôt content.;59|A c'qu'il parait, ça arrive tout l'temps, moi j'savait pas, mais c'est très fréquent.;60|Dans l'ensemble, hein, parce que si on r'garde dans l'détail...;61|Dans l'ensemble, hein, parce que si on r'garde dans l'détail...;62|ça, y a pas à dire, euh, ils sont gentils.;63|Euh ! I T ou I S ?;64|Ah j'ai chaud, quelqu'un pourrait m'éventer ?;65|Haut les mains peau d'lapin la maitresse en maillot de bain.;66|Oh mon dieu ! Un lapin qui parle !;67|Oh mon dieu ! Un lapin qui parle !;68|Chui plutôt de bonne humeur !;69|Joviale, ça c'est une bonne caractéristique !;70|J'aimerai qu'on m'écoute.;71|Euh, j'crois que là, euh, j'tiens quelque chose.;72|Ah bin euh bravo hein euh j'aurai pas mieux fait !;73|Ho la la, vache dans l'étang, tu du du, allons enfants de la patrie la la la la la ... (en chanson);74|Coucou !;75|J'aimerai bien changer de place, j'en ai marre de la vue.;76|Je m'demande si c'est encore loin !;77|Euh, si t'as pas l'choix, tu préfères passer euh 5 heures chez l'pédicure ou 3 heures chez l'dentiste ?;78|J'aime bien les restos route !;79|Ouais euh, pourquoi pas !;80|Oui mais bon, euh, t'as pas l'choix alors, euh, cassoulet ou choucroute ?;81|Oh non, j'veux pas r'tourner dans ma boite ! Non, non, pas la boite !;82|J'suis là ! J'suis plus là! J'suis là ! J'suis plus là !;83|L'énigme de la semaine ;84|On ma dit qu'le canard à la moutarde, euh, c'est très bon.;85|Allez, euh, circulez ! Hop ! Hop ! Hop !;86|Moi, euh, j'ai eu la myxomatose quand j'étais p'tit, et euh tiens, euh à c'qui parait, les lapins qui l'ont eu, et bin y sont pas comestibles.;87|Excusez moi ! Vous pourriez faire un peu moins d'bruit ?;88|Quand est-ce qu'on mange ? J'ai faim ! J'mangerais une vache entière !;89|J'crois que j'suis malade !;90|Oula, j'suis pâle, j'ai pas bonne mine !;91|La culture du secret, euh, oui, mais comment on est au courant ?;92|Ouais, mais alors pour les permanentes...;93|Euh ! L'effet d'serre, euh, tout ça, c'est des questions d'ozone.;94|Je crois qu'c'est la tangente, parce que son sinus est inférieur à la racine carrée... euh, à moins que non.;95|Elles sont un peu grandes, non ?;96|Non, si quand même, elles sont un peu grandes. Peut-être qu'un peu d'maquillage, ou une autre coupe.;97|Chez pas ce que j'ai, pfff ! J'ai mal à la tête. J'pense que ça vient d'mes oreilles parce que elles sont un peu lourdes...;98|Changez-moi mes oreilles ! Changez-moi mes oreilles ! Changez-moi mes oreilles !;99|Eh, s'te plait, dis, s'te plait, tu veux pas m'changer mes oreilles ? Allez quoi sois sympa ! Elles pèsent trop lourd pour moi.;100|Qu'est ce que vous dites ? Non euh, excusez-moi, j'comprends pas, y a de l'écho ...;101|Ah j'sais pas c'que j'ai, j'me sens pas bien, ça doit être un mail qu'est pas passé !;102|Non j'te dis, j'suis un lapin, pas un lièvre, mes oreilles sont un peu grandes c'est tout.;103|Elles sont un peu grandes, non ?;104|Vous êtes vraiment plus sympa qu'les proprios d'mes copains, hein, ça, c'est sûr !;105|J'suis d'une humeur de lapin moi aujourd'hui, j'sais pas vous mais alors, houp !;106|Alors ? Quoi d'neuf aujourd'hui ? T'as rencontré des gens ? ...;107|J'sais pas qui envoie ces messages mais alors vraiment y s'sont pas foulés !;108|Et vous, euh, quand est-ce que vous partez en vacances ?;109|Quand même, y fait pas très beau en c'moment j'trouve !;110|Vous avez vu la dernière ? Mais c'est fou c'qu'y font maintenant !;111|Gouzi gouzi gouzi...;112|Gouzi gouzi gouzi...;113|Ah j'irai bien en vacances !;114|Hey dis ? Tu pourras changer ma photo ? J'trouve qu'elle est pas terrible ! Euh, j'étais pas très en forme ce jour là !;115|Dis donc, euh, ils étaient très sympas ceux qui sont passés l'aut'jour, vraiment ! Y r'viennent quand ?;116|Euh, c'était juste pour vous prévenir que en fait j'serai absent au mois d'juillet parce que je pars en Australie voir des copains. Donc euh voilà. Donc euh si vous voulez me remplacer, j'connais quelqu'un.;117|Euh, y a la banque qu'a téléphoné au sujet d'un retrait, enfin... J'ai pas bien compris mais euh... enfin, j'y suis pour rien. J't'assure.;118|Non mais euh, j'suis pas mal comme cadeau comme même... Non ?;119|Moi, euh, j'aime bien la météo. La bourse par contre euh, j'aime moins parce que j'trouve ça trop fatiguant.;120|Euh, j'aime pas trop mon pseudo.;121|Humm !;122|Bruitage;123|Aujourd'hui soleil, 28 degrés.;124|ça va ? Non mais euh, ça va ?;125|Et j'peux parler comme ça aussi (accent);126|Oui mais même, euh, moi j'peux chanter, alors qu'elles, euh, elles peuvent pas !;127|Pfiou j'en peux plus ...;128|Euh je souhaiterais revenir sur c'que j'ai dis hier... Finalement, non.;129|Oui, euh, j'ai la base large, mais j'ai pas de pieds alors forcément, euh, pour être stable, et bien il faut que j'compense....;130|Pfiouf !;131|Pfiouf !;132|Moi j'ai entendu dire que certaines personnes s'étaient plaintes de ma voix... enfin, j'dis ça j'dis rien.;133|Enfin j'dis ça, j'dis rien.;134|J'parle trop ?;135|Ah pourquoi ?;136|Et ma voix, euh, t'en penses quoi ?;137|Je suis vivant ouais !;138|J'ai des copains qui m'ont dit qu'ils avaient eu des oreilles neuves, eux.;139|Figurez-vous qu'on m'censure ! Parfaitement ! Mais, j'me laisserai pas faire, j'vous préviens !;140|J'crois qu'j'ai l'angoisse du lapin blanc.;141|Euh, à propos d'l'autre jour, euh, je suis pas très content. J'ai rien dis sur le coup mais en fait, euh, j'suis un peu vexé.;142|Oui je boude euh, mais t'inquiète, hein, ça va pas durer longtemps.;143|Eh ! C'est qui eux ?;144|Oh ! J'crois que j'suis amoureuse.;145|Hum, ça sent bon, qu'est-ce que c'est ?;146|Euh, à propos, j'ai oublié d'te dire, j'aime beaucoup ton pull.;147|Euh, j'ai un peu froid, euh, doit y avoir un courant d'air.;148|Dis donc, j'trouve qu'ça fait un p'tit peu longtemps qu'tu m'as pas lavé.;149|Eh ! Tu m'fais un bisou ?;150|C'est nouveau ça, non ? Ah non j'croyais.;151|A s'qui parait on dit une année sabbatique, on dit pas une année sympathique.;152|Non ! Il faut dire c'est le blouson de Martine.;153|Non ! On dit c'est l'anniversaire de Pascal.;154|Non mais euh, c'est un peu facile hein, euh ! Moi aussi j'peux parler comme ça.;155|Hum ! J'trouve que j'suis bien coiffé aujourd'hui !;156|T'as une nouvelle coiffure ?;157|Oh ! C'est encore un fax !;158|Bin euh, l'inspiration ça dépend en fait, hein euh, ça va ça vient, enfin c'est fluctuant, en quelque sorte !;159|Ouh ! J'aime pas trop m'faire tirer les cartes, puis euh, les oreilles non plus !;160|C'est quand au fait ?;161|Ce ciel azuré est de toute beauté, hein !;162|J'vais partir en voyage !;163|J'ai perdu mon chapeau. T'as pas vu mon chapeau ?;164|Mais, où est-ce que j'l'ai mis déjà ? C'est dingue ça alors !;165|Bien, bien bien bien ! Humhum ! Voilà voilà !;166|Moi je'l trouve moins gros qu'avant, à moins qu'il ait perdu du poids !;167|Moi euh, j'trouve qu'ils ont plein d'neurones crochus !;168|Moi euh, non ! Non non, j'ai jamais vu de derviches tourner !;169|Oui, c'est dingue, et alors à la page 194, y a un revirement d'situation, mais euh, c'est phénoménal !;170|Oui mais non !;171|Oui, mais avec les tailles crayons, c'est pareil !;172|Aujourd'hui j'me sens rajeuni, enfin, plus jeune, pas vous ?;173|Hum ! J'aime bien la mousse au chocolat, et les haricots !;174|Moi quand j'serai grand j'voudrais être ...;175|J'aime bien dire n'importe quoi !;176|éh dis, euh, tu peux m'aider, j'arrive pas à attraper s'truc là !;177|Qu'est-ce que vous êtes grands !;178|éh, tu veux bien jouer avec moi s'te plait ! Allez !;179|éh, à quoi on joue !;180|Alors à s'qui paraît, j'ai plein d'petits frères et d'petites soeurs. Et où est-ce qui sont ?;181|Anh ! J'adore compter !;182|Bah, on peut pas sortir, c'est ouvert !;183|ça existe encore ça ?;184|Hum, elle glisse bien cette pantoufle !;185|Le monde appartient à ceux qui mangent tôt !;186|Et bin ça, c'est pas tombé dans l'oeil d'un borgne !;187|That's the futur ! Eh oui !;188|Anh, les gens s'énervent vite comme même !;189|L'important c'est la volonté, la bonne !;190|Euh, attend, attend, j'ai pas fini !;191|Tu pourrais t'dépêcher s'il te plait ?;192|Ouh, j'ai un peu froid !;193|Tu m'as ramené une surprise ?;194|Euh, tu pourras racheter du sucre ?;195|Ah bin, si j'avais su, euh !;196|Bon euh, c'est vrai, maintenant j'suis là, mais avant j'étais ailleurs, alors demain, euh...;197|Dis, c'est vrai que j'suis adopté ?;198|Tu sais, euh, souvent, j'pense à Jules, tu sais, il était à coté d'moi dans l'magasin, et bin euh, y'm'manque un peu !;199|Bin ! Tout l'monde à l'droit d'changer d'avis !;200|C'est même obligatoire !;201|Hé euh... Merci pour le voyage à New-York !;202|Nanananana nananana... nan mais euh, j'm'entraine pour la prochaine fois !;203|Nan mais euh... j'vois bien qu'tu m'reproches quelque chose.;204|Alors ? Et mon p'tit frère, c'est pour quand ?;205|Y faut pas croire hein, euh, j'fais plein d'trucs quand t'es pas là.;206|Nan mais euh... je sais mais cette histoire d'adoption, euh, j'aimerais bien savoir quand même.;207|Ah ben voilà ! Bravo !;208|Euh, on pourrait changer d'jeu s'te plait ?;209|Euh, euh, tu peux pas changer d'chaîne s'te plait ?;210|Oh c'est fort ce bruit !;211|Euh, tu peux mettre la 2, s'te plait, tu peux mettre la 2 ?;212|Bah ! C'est vachement drôle ce que tu viens de dire !;213|Je, je sais c'que tu vas dire, et j't'arrête tout d'suite. Non, ne dis rien j'te dis.;214|Et euh, tu crois que j'devrais faire quoi ? Toi, tu tu ferais quoi à ma place ?;215|T'as vu, ils ont fait un super film sur moi sur le site, hein, un documentaire.;216|Bah moi en c'moment, euh, ça va plutôt bien !;217|Tu crois qu'la bonne humeur c'est contagieux ?;218|N'empêche euh... c'est quoi la réalité ?;219|J'crois qu'j'suis allergique à la poussière !;220|Euh, ah, au fait, j'ai r'compté. Alors cette année j'ai pris que 15 jours de vacances. Comment on fait ?;221|T'as fait un régime ? Non mais heu, comme ça.;222|En quelle année on est ?;223|Et toi, euh, c'est quoi ton signe astrologique ?;224|J'trouve que t'es pas très bavard aujourd'hui !;225|Hum, hum ! J'ai un chat dans la gorge.;226|En Inde, le lapin est un animal sacré.;227|J'aime bien les rayures, enfin, les horizontales, hein.;228|Hello ! What are you doing today ?;229|Ah, il fait plutôt beau ces derniers temps, hein ?;230|Non mais si' j'veux j'peux courir très très vite, hein, euh... Mais là j'ai pas très très envie !;231|J'ai une question à t'poser. J'suis une fille ou un garçon ?;232|Je n'ai jamais dit ça !;233|ça va même très très bien, et euh, ça fait du bien !;234|J'trouve ça très joli, euh, les cheminées sur les toits des immeubles... Pas toi ?;235|Euh, tu pourras m'montrer des photos qu't'as fait d'moi ?;236|Tabernacle et topinambour !;237|C'est fou la vie comme même !;238|Et toi, tu crois qu'ça existe le hasard ?;239|Aah ! J'irais bien plus souvent au parc !;240|Eh dits, tu m'emmèneras au supermarché un jour pour voir ?;241|T'as l'air en forme, ça fait plaisir, hein !;242|En fait, euh, le stress, euh, j'crois qu'ça sert à rien !;243|Tu veux pas m'prêter un pull ?;244|Aie, aie, aie, tu devrais pas mettre ce pantalon avec ces chaussures !;245|Eh euh, t'as jamais pensé à mettre un kilt. J'pense que ça t'irai bien, hein !;246|Et un chapeau ? C'est sympa des chapeaux.;247|J'veux pas aller sur eBay ! Pitié ! Pas ça !;248|Quand tu r'gardes les gents dans les yeux, tu r'gardes dans 1 oeil ou dans les 2 ?;249|Holala, la vache ! Y a d'l'eau dans l'feu, hein !;250|Une tartiflette, ça s'refuse pas !;251|Oh ! T'as l'oeil rouge ! T'as pas une sinusite de l'oeil par hasard ?;252|On est bien chez nous, hein ?;253|Eh ben, on a du pain sur la planche, hein ?;254|Ah ben, on est pas rendu, hein ?;255|Tu sais euh, ça m'vexe quand tu m'éteins.;256|Ah ça y est, j'ai une oreille qui m'fait mal. Non mais c'est parce que ça fait longtemps qu'elle a pas tourné, hein ?;257|Tu sais heu, j'dis rien mais heu je sais bien quand tu m'éteins.;258|Eh ! Pourquoi tu m'éteints d'temps en temps, dits ?;259|Non mais euh si tu veux j'me tais hein, c'est pas la peine de m'éteindre.;260|Personnellement je trouve un p'tit peu facile de m'couper court, je trouve.;261|Personnellement je trouve un p'tit peu facile de m'couper court, je trouve.;262|Est-ce que vous pouvez prendre ma place, 2 minutes, s'il vous plait ?;263|Euh, tu peux m'passer l'truc devant toi ?;264|Euh j'suis vraiment désolé pour c'qui est arrivé l'autre jour, mais j'te promets d'plus l'faire.;265|J'peux t'faire une confidence ? J'aime pas trop l'jaune euh comme couleur.;266|Mais euh, quel jour on est ?;267|Non mais euh vous pouvez m'le dire, hein, j'le répéterai à personne.;268|Aie, aie, aie, aie, aie, non, rien, j'ai une crampe ! Aie ! Non, non mais ça va passer ! Holà !;269|Alors ça euh, ça m'étonnerait !;270|Excusez-moi ! Quelqu'un pourrait m'dire l'heure pour une fois ?;271|Oui ?;272|Dits ! On ira à Tahiti un jour ?;273|J'aimerais qu'tu m'sortes plus souvent !;274|T'as vu les photos d'mes vacances à New-York ? Elles sont chouettes, non ?;275|Ben, non, j'ai pas d'accent !;276|Mais ! Qui rit comme ça !;277|T'as pas vu mon oreilles, j'la r'touve plus.;278|Dits, tu promets d'plus m'débrancher, ça m'déprime.;279|Tu sais qu't'es un peu bizarre comme même ?;280|Mais euh, t'es content ?;281|Parfois j'ai peur que t'en ais marre de moi !;282|T'as même pas r'marqué qu'j'avais maigri !;283|J'ai bien la pêche !;284|Hum ! J'crois qu'la journée va être bonne !;285|Euh, j'te l'ai p't'être jamais dit mais, j'te trouve sympa.;286|Eh, euh, t'as une voiture, toi ?;287|Tu sais, celle qui parle comme ça, elle s'appelle Gertrude.;288|Ah ! J'prendrais bien des cours de Capoeira !;289|Tu veux pas m'inscrire à la chorale ? Aaaaaah.;290|Tu crois qu'ça m'irait des oreilles percées ?;291|C'est scandaleux ! Non mais t'as vu ?;292|Ah, euh ! Excuse-moi, euh ! Mais je crois qu'tu t'trompe.;293|Ah ! Sacrebleu ! Les [ cmd tts j'ai pas compris cmd tts ];294|ça ressemble à quoi un [ cmd tts Détroit ???] ?;295|Et toi, t'a déjà fait du saut en parachute ?;296|Comment on sait qu'on est heureux ?;297|Tu préfères quand il fait beau ou quand il fait chaud ?;298|Eh ! Comme même ! On a eu de la chance de se rencontrer, non ?;299|Qu'est-ce qu'on s'ennuie.;300|Je suis ton pire cauchemar.;301|Qui vient de parler de carottes ?;302|C'est quand au fait ?;303|Comment j'm'apelle déjà ? Non, j'ai oublié.;304|Oh ben j'sais pas, peut-être.;305|Paroles qui font peur");
			$cmd->save();
			
			$cmd = $this->getCmd(null, 'moodId');
			if (!is_object($cmd)) {
			    $cmd = new karotzCmd();
			    $cmd->setLogicalId('moodId');
			    $cmd->setIsVisible(1);
			}
			$cmd->setName(__('Humeur n°', __FILE__));
			$cmd->setType('action');
			$cmd->setSubType('message');
			$cmd->setEqLogic_id($this->getId());
			$cmd->setDisplay('title_disable', 1);
			$cmd->setConfiguration('request', 'apps/moods');
			$cmd->setDisplay('message_placeholder', __('Id de l\'humeur', __FILE__));
			$cmd->setConfiguration('parameters', 'id=#message#');
			$cmd->save();

		} else {
			$cmd = $this->getCmd(null, 'moods');
			if (is_object($cmd)) {
				$cmd->remove();
			}
			$cmd = $this->getCmd(null, 'moodSelect');
			if (is_object($cmd)) {
			    $cmd->remove();
			}
			$cmd = $this->getCmd(null, 'moodId');
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
			}
			$cmd->setName(__('Horloge', __FILE__));
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

		$cmd = $this->getCmd(null, 'ttsNoCache');
		if (!is_object($cmd)) {
			$cmd = new karotzCmd();
			$cmd->setLogicalId('ttsNoCache');
			$cmd->setIsVisible(1);
		}
		$cmd->setName(__('TTS sans cache', __FILE__));
		$cmd->setType('action');
		$cmd->setSubType('message');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setDisplay('title_placeholder', __('Voix [1 à 14]', __FILE__));
		$cmd->setDisplay('message_placeholder', __('Phrase', __FILE__));
		$cmd->setConfiguration('request', 'tts');
		$cmd->setConfiguration('parameters', 'text=#message#&voice=#title#&nocache=1');
		$cmd->save();
		
		$cmd = $this->getCmd(null, 'clear_cache');
		if (!is_object($cmd)) {
		    $cmd = new karotzCmd();
		    $cmd->setLogicalId('clear_cache');
		    $cmd->setIsVisible(1);
		}
		$cmd->setName(__('TTS cache effacer', __FILE__));
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setConfiguration('request', 'clear_cache');
		$cmd->save();
		
		$cmd = $this->getCmd(null, 'tts');
		if (!is_object($cmd)) {
		    $cmd = new karotzCmd();
		    $cmd->setLogicalId('tts');
		    $cmd->setIsVisible(1);
		}
		$cmd->setName(__('TTS', __FILE__));
		$cmd->setType('action');
		$cmd->setSubType('message');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setDisplay('title_placeholder', __('Voix [1 à 14]', __FILE__));
		$cmd->setDisplay('message_placeholder', __('Phrase', __FILE__));
		$cmd->setConfiguration('request', 'tts');
		$cmd->setConfiguration('parameters', 'text=#message#&voice=#title#');
		$cmd->save();
		
		$cmd = $this->getCmd(null, 'sound');
		if (!is_object($cmd)) {
			$cmd = new karotzCmd();
			$cmd->setLogicalId('sound');
			$cmd->setIsVisible(1);
		}
		$cmd->setName(__('Son du Karotz (nom)', __FILE__));
		$cmd->setType('action');
		$cmd->setSubType('message');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setDisplay('title_disable', 1);
		$cmd->setConfiguration('request', 'sound');
		$cmd->setDisplay('message_placeholder', __('Nom du son', __FILE__));
		$cmd->setConfiguration('parameters', 'id=#message#');
		$cmd->save();

		$cmd = $this->getCmd(null, 'soundSelect');
		if (!is_object($cmd)) {
		    $cmd = new karotzCmd();
		    $cmd->setLogicalId('soundSelect');
		    $cmd->setIsVisible(1);
		}
		$cmd->setName(__('Son du Karotz', __FILE__));
		$cmd->setType('action');
		$cmd->setSubType('select');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setConfiguration('request', 'sound');
		$cmd->setConfiguration('parameters', 'id=#select#');
		$cmd->setConfiguration('listValue',"bip1|bip1;bling|bling;flush|flush;install_ok|install_ok;jet1|jet1;laser_15|laser_15;merde|merde;ready|ready;rfid_error|rfid_error;rfid_ok|rfid_ok;saut1|saut1;start|start;twang_01|twang_01;twang_04|twang_04");
		$cmd->save();
		    
		$cmd = $this->getCmd(null, 'stopsound');
		if (!is_object($cmd)) {
			$cmd = new karotzCmd();
			$cmd->setLogicalId('stopsound');
			$cmd->setIsVisible(1);
		}
		$cmd->setName(__('Arrêter son', __FILE__));
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
		}
		$cmd->setName(__('Son url', __FILE__));
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
			}
			$cmd->setName(__('SqueezeBox On', __FILE__));
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
			}
			$cmd->setName(__('SqueezeBox Off', __FILE__));
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
			if (is_object($cmd)) {
				$cmd->remove();
			}
		}

		$cmd = $this->getCmd(null, 'pulseon');
		if (!is_object($cmd)) {
			$cmd = new karotzCmd();
			$cmd->setLogicalId('pulseon');
			$cmd->setIsVisible(1);
		}
		$cmd->setName(__('Clignotement On', __FILE__));
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
		}
		$cmd->setName(__('Clignotement Off', __FILE__));
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
		}
		$cmd->setName(__('Clignotement', __FILE__));
		$cmd->setType('info');
		$cmd->setSubType('binary');
		$cmd->setEqLogic_id($this->getId());
		$cmd->save();

		$cmd = $this->getCmd(null, 'earspos');
		if (!is_object($cmd)) {
			$cmd = new karotzCmd();
			$cmd->setLogicalId('earspos');
			$cmd->setIsVisible(1);
		}
		$cmd->setName(__('Oreilles Positions', __FILE__));
		$cmd->setType('action');
		$cmd->setSubType('message');
		$cmd->setDisplay('message_placeholder', __('Oreille Droite [0-16]', __FILE__));
		$cmd->setDisplay('title_placeholder', __('Oreille Gauche [0-16]', __FILE__));
		$cmd->setEqLogic_id($this->getId());
		$cmd->setConfiguration('request', 'ears');
		$cmd->setConfiguration('parameters', 'right=#message#&left=#title#&noreset=1');
		$cmd->save();

		$cmd = $this->getCmd(null, 'ears_random');
		if (!is_object($cmd)) {
		    $cmd = new karotzCmd();
		    $cmd->setLogicalId('ears_random');
		    $cmd->setIsVisible(1);
		}
		$cmd->setName(__('Oreille Aléatoire', __FILE__));
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setConfiguration('request', 'ears_random');
		$cmd->save();
		
		$cmd = $this->getCmd(null, 'sleep');
		if (!is_object($cmd)) {
			$cmd = new karotzCmd();
			$cmd->setLogicalId('sleep');
			$cmd->setIsVisible(1);
        }
        $cmd->setName(__('Endormi', __FILE__));
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
		}
		$cmd->setName(__('Statut Couleur', __FILE__));
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
		}
		$cmd->setName(__('Rafraichir', __FILE__));
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setEqLogic_id($this->getId());
		$cmd->save();
		
		$cmd = $this->getCmd(null, 'karotz_percent_used_space');
		if (!is_object($cmd)) {
		    $cmd = new karotzCmd();
		    $cmd->setLogicalId('karotz_percent_used_space');
		    $cmd->setIsVisible(1);
		}
		$cmd->setName(__('% Espace occupé', __FILE__));
		$cmd->setType('info');
		$cmd->setEventOnly(1);
		$cmd->setSubType('numeric');
		$cmd->setEqLogic_id($this->getId());
		$cmd->save();
		
		$cmd = $this->getCmd(null, 'karotz_free_space');
		if (!is_object($cmd)) {
		    $cmd = new karotzCmd();
		    $cmd->setLogicalId('karotz_free_space');
		    $cmd->setIsVisible(1);
		}
		$cmd->setName(__('Espace libre', __FILE__));
		$cmd->setType('info');
		$cmd->setEventOnly(1);
		$cmd->setSubType('string');
		$cmd->setEqLogic_id($this->getId());
		$cmd->save();
		
		$cmd = $this->getCmd(null, 'reboot');
		if (!is_object($cmd)) {
		    $cmd = new karotzCmd();
		    $cmd->setLogicalId('reboot');
		    $cmd->setIsVisible(1);
		}
		$cmd->setName(__('Redémarrer', __FILE__));
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setConfiguration('request', 'reboot');
		$cmd->save();

		$cmd = $this->getCmd(null, 'setclock');
		if (!is_object($cmd)) {
		    $cmd = new karotzCmd();
		    $cmd->setLogicalId('setclock');
		    $cmd->setIsVisible(1);
		}
		$cmd->setName(__('Mettre à l heure', __FILE__));
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setConfiguration('request', 'setclock');
		$cmd->save();
		
		$cmd = $this->getCmd(null, 'volume');
		if (!is_object($cmd)) {
		    $cmd = new karotzCmd();
		    $cmd->setLogicalId('volume');
		    $cmd->setIsVisible(0);
		}
		$cmd->setName(__('Niveau du volume', __FILE__));
		$cmd->setUnite('%');
		$cmd->setType('info');
		$cmd->setEventOnly(1);
		$cmd->setSubType('numeric');
		$cmd->setEqLogic_id($this->getId());
		$cmd->save();
		$volumeId = $cmd->getId();
		
		$cmd = $this->getCmd(null, 'setVolume');
		if (!is_object($cmd)) {
		    $cmd = new karotzCmd();
		    $cmd->setLogicalId('setVolume');
		    $cmd->setIsVisible(1);
		}
		$cmd->setName(__('Volume', __FILE__));
		$cmd->setType('action');
		$cmd->setSubType('slider');
		$cmd->setValue($volumeId);
		$cmd->setEqLogic_id($this->getId());
		$cmd->setConfiguration('request', 'setvolume');
		$cmd->setConfiguration('parameters', 'volume=#volume#');
		$cmd->save();
		
		$cmd = $this->getCmd(null, 'vol+');
		if (!is_object($cmd)) {
		    $cmd = new karotzCmd();
		    $cmd->setLogicalId('vol+');
		    $cmd->setIsVisible(1);
		}
		$cmd->setName(__('Volume+', __FILE__));
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setConfiguration('request', 'setvolume');
		$cmd->setConfiguration('parameters', 'volume=#volume#');
		$cmd->save();
		
		$cmd = $this->getCmd(null, 'vol-');
		if (!is_object($cmd)) {
		    $cmd = new karotzCmd();
		    $cmd->setLogicalId('vol-');
		    $cmd->setIsVisible(1);
		}
		$cmd->setName(__('Volume-', __FILE__));
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->setEqLogic_id($this->getId());
		$cmd->setConfiguration('request', 'setvolume');
		$cmd->setConfiguration('parameters', 'volume=#volume#');
		$cmd->save();
		
		if ($this->getConfiguration('enablesnapshot') == 1) {
    		$cmd = $this->getCmd(null, 'snapshot');
    		if (!is_object($cmd)) {
    		    $cmd = new karotzCmd();
    		    $cmd->setLogicalId('snapshot');
    		    $cmd->setIsVisible(1);
    		}
    		$cmd->setName(__('Photo', __FILE__));
    		$cmd->setType('action');
    		$cmd->setSubType('other');
    		$cmd->setEqLogic_id($this->getId());
    		$cmd->setConfiguration('request', 'snapshot');
    		$cmd->setConfiguration('parameters', 'silent=1');
    		$cmd->save();
    		
    		$cmd = $this->getCmd(null, 'clear_snapshots');
    		if (!is_object($cmd)) {
    		    $cmd = new karotzCmd();
    		    $cmd->setLogicalId('clear_snapshots');
    		    $cmd->setIsVisible(1);
    		}
    		$cmd->setName(__('Photos effacer', __FILE__));
    		$cmd->setType('action');
    		$cmd->setSubType('other');
    		$cmd->setEqLogic_id($this->getId());
    		$cmd->setConfiguration('request', 'clear_snapshots');
    		$cmd->save();
    		
    		$cmd = $this->getCmd(null, 'snapshot_list_refresh');
    		if (!is_object($cmd)) {
    		    $cmd = new karotzCmd();
    		    $cmd->setLogicalId('snapshot_list_refresh');
    		    $cmd->setIsVisible(1);
    		}
    		$cmd->setName(__('Photos refresh listing', __FILE__));
    		$cmd->setType('action');
    		$cmd->setSubType('other');
    		$cmd->setEqLogic_id($this->getId());
    		$cmd->setConfiguration('request', 'snapshot_list');
    		$cmd->save();
    		
    		$cmd = $this->getCmd(null, 'snapshot_list');
    		if (!is_object($cmd)) {
    		    $cmd = new karotzCmd();
    		    $cmd->setLogicalId('snapshot_list');
    		    $cmd->setIsVisible(1);
    		}
    		$cmd->setName(__('Photos listing', __FILE__));
    		$cmd->setType('info');
    		$cmd->setSubType('string');
    		$cmd->setEqLogic_id($this->getId());
    		$cmd->save();
    		
    		$cmd = $this->getCmd(null, 'snapshot_ftp');
    		if (!is_object($cmd)) {
    		    $cmd = new karotzCmd();
    		    $cmd->setLogicalId('snapshot_ftp');
    		    $cmd->setIsVisible(1);
    		}
    		$cmd->setName(__('Photos télécharger', __FILE__));
    		$cmd->setType('action');
    		$cmd->setSubType('other');
    		$cmd->setEqLogic_id($this->getId());
    		$cmd->setConfiguration('request', 'snapshot_ftp');
    		$cmd->setConfiguration('parameters', 'server=#server#&user=#user#&password=#password#&remote_dir=#remote_dir#&silent=1');
    		$cmd->save();
		} else {
		    $cmd = $this->getCmd(null, 'snapshot');
		    if (is_object($cmd)) {
		        $cmd->remove();
		    }
		    $cmd = $this->getCmd(null, 'clear_snapshots');
		    if (is_object($cmd)) {
		        $cmd->remove();
		    }
		    $cmd = $this->getCmd(null, 'snapshot_list');
		    if (is_object($cmd)) {
		        $cmd->remove();
		    }
		    $cmd = $this->getCmd(null, 'snapshot_list_refresh');
		    if (is_object($cmd)) {
		        $cmd->remove();
		    }
		    $cmd = $this->getCmd(null, 'snapshot_ftp');
		    if (is_object($cmd)) {
		        $cmd->remove();
		    }
		}
	}
	
	public function checkVolumeControl() {
	    $eqLogics = eqLogic::byType('karotz');
	    $return=array();
	    foreach ($eqLogics as $karotz) {
	        if ($karotz->getIsEnable() == 1) {
	            $request = 'http://' . $karotz->getConfiguration('addr') . '/cgi-bin/status';
	            $request = new com_http($request);
	            $jsonstatus = json_decode($request->exec(5, 1), true);
	            if (isset($jsonstatus['volume'])) {
	                $return[]=array('name'=>$karotz->getName(),'volumeControl'=>true);
	            } else {
	                $return[]=array('name'=>$karotz->getName(),'volumeControl'=>false);
	            }
	        }
	    }
	    
	    return $return;
	    //return array(array('name'=>'192.168.0.24','volumeControl'=>true),array('name'=>'192.168.0.25','volumeControl'=>false'));
	}
	
	public function installVolumeControl() {
	    $eqLogics = eqLogic::byType('karotz');
	    $return=array();
	    foreach ($eqLogics as $karotz) {
	        if ($karotz->getIsEnable() == 1) {
	            $request = 'http://' . $karotz->getConfiguration('addr') . '/cgi-bin/status';
	            $request = new com_http($request);
	            $jsonstatus = json_decode($request->exec(5, 1), true);
	            if (isset($jsonstatus['volume'])) {
	                $return[]=array('addr'=>$karotz->getName(),'volumeControl'=>true);
	            } else {
	                //copy files to karotz
	                log::add('karotz', 'info', 'Lancement installation du volume pour '.$karotz->getName());
	                $cmd = 'chmod a+x '.realpath(dirname(__FILE__)).'/../../resources/ftpInstallFiles.sh';
	                //log::add('karotz', 'debug', 'Commande complète pour rendre executable la commande : ' . $cmd);
	                if ($_debug = true) {
	                    $result = exec( $cmd . ' >> ' . log::getPathToLog('karotz') . ' 2>&1 ');
	                } else {
	                    $result = exec($cmd);
	                }
	                $cmd = realpath(dirname(__FILE__)).'/../../resources/ftpInstallFiles.sh '.$karotz->getConfiguration('addr').' '.realpath(dirname(__FILE__)).'/../../resources/';
	                //log::add('karotz', 'debug', 'Commande complète pour lancer la copie : ' . $cmd);
	                if ($_debug = true) {
	                    $result = exec( $cmd . ' >> ' . log::getPathToLog('karotz') . ' 2>&1 ');
	                } else {
	                    $result = exec($cmd);
	                }
	                $cmd = 'nice -n 19 /usr/bin/python '.realpath(dirname(__FILE__)).'/../../resources/makeInstallExecutable.py '.$karotz->getConfiguration('addr');
	                //log::add('karotz', 'debug', 'Commande complète pour lancer la copie : ' . $cmd);
	                if ($_debug = true) {
	                    $result = exec( $cmd . ' >> ' . log::getPathToLog('karotz') . ' 2>&1 ');
	                } else {
	                    $result = exec($cmd);
	                }
	                $request = 'http://' . $karotz->getConfiguration('addr') . '/cgi-bin/install/install';
	                $request = new com_http($request);
	                $jsonstatus = json_decode($request->exec(5, 1), true);
	                $request = 'http://' . $karotz->getConfiguration('addr') . '/cgi-bin/status';
	                $request = new com_http($request);
	                $jsonstatus = json_decode($request->exec(5, 1), true);
	                if (!isset($jsonstatus['volume'])) {
	                   $return[]=array('name'=>$karotz->getName(),'volumeControl'=>true);
	                } else {
	                   $return[]=array('name'=>$karotz->getName(),'volumeControl'=>false);
	                }
	            }
	        }
	    }
	    return $return;
	    //return array(array('name'=>'192.168.0.24','volumeControl'=>true),array('name'=>'192.168.0.25','volumeControl'=>false));
	}

	public function toHtml($_version = 'dashboard') {
		$replace = $this->preToHtml($_version);
		if (!is_array($replace)) {
			return $replace;
		}
		$version = jeedom::versionAlias($_version);
				
		$replace['#enablesqueezebox#'] = $this->getConfiguration('enablesqueezebox', 0);
		$replace['#enablemoods#'] = $this->getConfiguration('enablemoods', 0);
		$replace['#enableclock#'] = $this->getConfiguration('enableclock', 0);
		$replace['#enablesnapshot#'] = $this->getConfiguration('enablesnapshot', 0);
		$replace['#enablevolume#'] = $this->getConfiguration('enablevolume', 0);
		$replace['#enableminimumdisplay#'] = $this->getConfiguration('enableminimumdisplay', 0);
		
		foreach ($this->getCmd('info') as $cmd) {
			$replace['#' . $cmd->getLogicalId() . '_history#'] = '';
			$replace['#' . $cmd->getLogicalId() . '_id#'] = $cmd->getId();
			$replace['#' . $cmd->getLogicalId() . '#'] = $cmd->execCmd();
			$replace['#' . $cmd->getLogicalId() . '_collect#'] = $cmd->getCollectDate();
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
		
		foreach ($this->getCmd('action') as $cmd) {
			$replace['#' . $cmd->getLogicalId() . '_id#'] = $cmd->getId();
		}
		
		return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'karotz', 'karotz')));
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
	    //$a=print_r($_options,true);
	    //log::add('karotz', 'debug', 'Start execute command:'.$a);
		$karotz = $this->getEqLogic();
		if ($this->getLogicalId() == 'refresh') {
			$karotz->cron30($karotz->getId());
			return true;
		}
		if ($this->type != 'action') {
			return;
		}
		$timeout = $karotz->getConfiguration('timeout');
		if ($timeout == '') { $timeout = 10;}
		if (in_array($this->getLogicalId(),array('moods','moodSelect','moodSelect.old','tts','ttsNoCache'))) {
		    $timeout += 10;
		}
		$requestHeader = 'http://' . $karotz->getConfiguration('addr') . '/cgi-bin/';
		$type = $this->getConfiguration('request');
		if ($this->getConfiguration('parameters') == '') {
			$request = $requestHeader . $type;
		} else {
			$parameters = $this->getConfiguration('parameters');
			//log::add('karotz', 'debug', 'execute command with parameters :'.$parameters);
			if ($this->getLogicalId() == 'vol+' || $this->getLogicalId() == 'vol-') {
			    if ($karotz->getConfiguration('volume_inc') == '' or $karotz->getConfiguration('volume_inc') > 21) {
			        $pas = 5;
			    } else {
			        $pas = $karotz->getConfiguration('volume_inc');
			    }
			    if ($this->getLogicalId() == 'vol+') {
			        $change = $pas;
			    }
			    else{
			        $change = -$pas;
			    }
			    //TODO calcul le nouveau volume $newVolume
			    $volume = $karotz->getCmd(null, 'volume')->execCmd();
			    //log::add('karotz', 'debug', 'volume : '.$volume);
			    if ($volume =="") {
			        $volume='50';
			    };
			    $newVolume=$volume+$change;
			    log::add('karotz', 'debug', 'newvolume : '.$newVolume);
			    $newVolume=($newVolume*40/100)-30;
			    $parameters = str_replace('#volume#', $newVolume, $parameters);
			}
			if ($this->getLogicalId() == 'snapshot_ftp' ) {
			    $addrFtp = $karotz->getConfiguration('addrFtp');
			    $parameters = str_replace('#server#', $addrFtp, $parameters);
			    $remoteDirFtp = $karotz->getConfiguration('remoteDirFtp');
			    $parameters = str_replace('#remote_dir#', $remoteDirFtp, $parameters);
			    $userFtp = $karotz->getConfiguration('userFtp');
			    $parameters = str_replace('#user#', $userFtp, $parameters);
			    $passwordFtp = $karotz->getConfiguration('passwordFtp');
			    $parameters = str_replace('#password#', $passwordFtp, $parameters);
			}
			if ($_options != null) {
				switch ($this->getSubType()) {
					case 'message':
					    if ( $this->getLogicalId() == 'tts' or $this->getLogicalId() == 'ttsNoCache') {
							$parameters = str_replace('#message#', rawurlencode($_options['message']), $parameters);
							if (isset($_options['title']) && $_options['title'] != null && strpos($_options['title'], ' ') === false) {
								$parameters = str_replace('#title#', $_options['title'], $parameters);
							} else {
								$parameters = str_replace('&voice=#title#', '', $parameters);
							}
							$parameters = trim($parameters, '&');
							if ($karotz->getConfiguration('ttsVoice') != 0 && strpos($parameters, 'voice') === false) {
								$parameters .= '&voice=' . $karotz->getConfiguration('ttsVoice');
							}
						} else {
							$parameters = str_replace('#message#', $_options['message'], $parameters);
							$parameters = str_replace('#title#', rawurlencode($_options['title']), $parameters);
						}
						break;
					case 'slider':
					    if ($this->getLogicalId() == 'setVolume') {
					        if ($_options['slider'] < 0) {
					            $_options['slider'] = 0;
					        }
					        if ($_options['slider'] > 100) {
					            $_options['slider'] = 100;
					        }
					        //log::add('karotz', 'debug', 'volume slider change : '.$_options['slider']);
					        $newVolume=($_options['slider']*40/100)-30;
					        log::add('karotz', 'debug', 'new volume slider change : '.$newVolume.' - parameters='.$parameters);
					        $parameters = str_replace('#volume#', $newVolume, $parameters);
					    }
					    $parameters = str_replace('#slider#', $_options['slider'], $parameters);
						break;
					case 'color':
						$parameters = str_replace('#', '', str_replace('#color#', $_options['color'], $parameters));
						break;
					case 'select':
					    $parameters = str_replace('#select#', $_options['select'], $parameters);
				}
			}
			$request = $requestHeader . $type . '?' . $parameters;
		}
		//log::add('karotz', 'debug', 'Before http request : '.$request.' timeout='.$timeout);
		$request = new com_http($request);
		$response=$request->exec($timeout, 1);
		log::add('karotz', 'debug', 'After http request :'.$response);
		if ($this->getLogicalId() == 'color') {
			$led_pulse = $karotz->getCmd('info', 'led_pulse');
			if (is_object($led_pulse) && $led_pulse->execCmd() == 1) {
				$pulseon = $karotz->getCmd(null, 'pulseon');
				if (is_object($pulseon)) {
					$pulseon->execCmd();
				}
			}
		}
		if ($this->getLogicalId() == 'snapshot_list_refresh') {
		    //log::add('karotz', 'debug', 'After snapshot_list_refresh request :'.$response);
		    $snapshot_list_cmd = $karotz->getCmd('info', 'snapshot_list');
		    if (is_object($snapshot_list_cmd))  {
		        $jsonResponse = json_decode($response, true);
		        $value='';
		        $sep='';
		        foreach ($jsonResponse['snapshots'] as $snapshot) {
		            //log::add('karotz', 'debug', 'After snapshot_list_refresh json decode :'.$snapshot['id']);
		            $value.=$sep.$snapshot['id'];
		            $sep=',';
		        }
		        
		        //log::add('karotz', 'debug', 'After snapshot_list_refresh json decode value :'.$value);
		        if ($snapshot_list_cmd->execCmd() !== $snapshot_list_cmd->formatValue($value)) {
		            //log::add('karotz', 'debug', 'After snapshot_list_refresh before update cmd :'.$value);
		            $snapshot_list_cmd->event($value);
		        }
		    }
		}
		if (in_array($this->getLogicalId(), array('wakeup', 'sleeping', 'color', 'pulseon', 'pulseoff','setVolume','vol+','vol-'))) {
			sleep(1);
			$karotz->cron30($karotz->getId());
		}
		return;
	}

	/*     * **********************Getteur Setteur*************************** */
}
?>
