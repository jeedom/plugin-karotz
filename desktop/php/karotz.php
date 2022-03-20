<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('karotz');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
$volumeControlEnable = config::byKey('volumeControlEnable', 'karotz', '0');
sendVarToJS('volumeControlEnable', $volumeControlEnable);
?>

<div class="row row-overflow">
	<div class="col-xs-12 eqLogicThumbnailDisplay">
		<legend><i class="fas fa-cog"></i> {{Gestion}}</legend>

		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction logoPrimary" data-action="add">
				<i class="fas fa-plus-circle"></i>
				<br>
				<span>{{Ajouter}}</span>
			</div>
			<div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
				<i class="fas fa-wrench"></i>
				<br>
				<span>{{Configuration}}</span>
			</div>
		</div>
		<legend><i class="fas fa-table"></i> {{Mes Karotz}}</legend>
		<?php
		if (count($eqLogics) == 0) {
			echo '<br><div class="text-center" style="font-size:1.2em;font-weight:bold;">{{Aucun équipement Karotz trouvé, cliquer sur "Ajouter" pour commencer}}</div>';
		} else {

			echo '<div class="input-group" style="margin:5px;">';
			echo '<input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_searchEqlogic">';
			echo '<div class="input-group-btn">';
			echo '<a id="bt_resetSearch" class="btn" style="width:30px"><i class="fas fa-times"></i></a>';
			echo '<a class="btn roundedRight hidden" id="bt_pluginDisplayAsTable" data-coreSupport="1" data-state="0"><i class="fas fa-grip-lines"></i></a>';
			echo '</div>';
			echo '</div>';

			echo '<div class="eqLogicThumbnailContainer">';
			foreach ($eqLogics as $eqLogic) {
				$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
				echo '<div class="eqLogicDisplayCard cursor ' . $opacity . '" data-eqLogic_id="' . $eqLogic->getId() . '">';
				echo '<img src="' . $plugin->getPathImgIcon() . '">';
				echo '<br>';
				echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
				echo '<span class="hiddenAsCard displayTableRight hidden">';
				echo ($eqLogic->getIsVisible() == 1) ? '<i class="fas fa-eye" title="{{Equipement visible}}"></i>' : '<i class="fas fa-eye-slash" title="{{Equipement non visible}}"></i>';
				echo '</span>';
				echo '</div>';
			}
			echo '</div>';
		}
		?>
	</div>

	<div class="col-xs-12 eqLogic" style="display: none;">
		<div class="input-group pull-right" style="display:inline-flex;">
			<span class="input-group-btn">

				<a class="btn btn-sm btn-default eqLogicAction roundedLeft" data-action="configure"><i class="fas fa-cogs"></i><span class="hidden-xs"> {{Configuration avancée}}</span>
				</a><a class="btn btn-sm btn-default eqLogicAction" data-action="copy"><i class="fas fa-copy"></i><span class="hidden-xs"> {{Dupliquer}}</span>
				</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}
				</a><a class="btn btn-sm btn-danger eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}
				</a>
			</span>
		</div>

		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
			<li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
			<li role="presentation"><a href="#commandtab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-list"></i> {{Commandes}}</a></li>
		</ul>
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="eqlogictab">
				<form class="form-horizontal">
					<fieldset>
						<div class="col-lg-9">
							<legend><i class="fas fa-wrench"></i> {{Paramètres généraux}}</legend>
							<div class="form-group">
								<label class="col-sm-3 control-label">{{Nom de l'équipement}}</label>
								<div class="col-sm-6">
									<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display:none;">
									<input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement}}">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">{{Objet parent}}</label>
								<div class="col-sm-6">
									<select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
										<option value="">{{Aucun}}</option>
										<?php
										$options = '';
										foreach ((jeeObject::buildTree(null, false)) as $object) {
											$options .= '<option value="' . $object->getId() . '">' . str_repeat('&nbsp;&nbsp;', $object->getConfiguration('parentNumber')) . $object->getName() . '</option>';
										}
										echo $options;
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">{{Catégorie}}</label>
								<div class="col-sm-9">
									<?php
									foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
										echo '<label class="checkbox-inline">';
										echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" >' . $value['name'];
										echo '</label>';
									}
									?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">{{Options}}</label>
								<div class="col-sm-6">
									<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked>{{Activer}}</label>
									<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked>{{Visible}}</label>
								</div>
							</div>

							<legend><i class="fas fa-cogs"></i> {{Paramètres spécifiques}}</legend>
							<div class="form-group">
								<label class="col-sm-3 control-label">{{Adresse IP}}</label>
								<div class="col-sm-3">
									<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="addr" placeholder="{{Adresse IP}}" />
								</div>
								<div class="col-sm-3">
									<label class="col-sm-6 control-label">{{Timeout}}</label>
									<div class="col-sm-3">
										<input type="text" class="eqLogicAttr" size="6" data-l1key="configuration" data-l2key="timeout" placeholder="{{Timeout}}" />
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">{{Voix TTS}}</label>
								<div class="col-sm-3">
									<!--input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ttsVoice" placeholder="{{Voix TTS}}"/-->
									<select id="sel_voice" class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="ttsVoice" placeholder="{{Voix TTS}}">
										<option value="1">{{fr Female}}</option>
										<option value="2">{{fr Male}}</option>
										<option value="3">{{ca Female}}</option>
										<option value="4">{{ca Male}}</option>
										<option value="5">{{us Female}}</option>
										<option value="6">{{us Male}}</option>
										<option value="7">{{uk Female}}</option>
										<option value="8">{{uk Male}}</option>
										<option value="9">{{de Female}}</option>
										<option value="10">{{de Male}}</option>
										<option value="11">{{it Female}}</option>
										<option value="12">{{it Male}}</option>
										<option value="13">{{spain Female}}</option>
										<option value="14">{{spain Male}}</option>
									</select>
								</div>
								<div class="col-sm-5 volume volumeControlEnable">
									<label class="col-sm-6 control-label">{{Variation volume}}
										<sup>
											<i class="fa fa-question-circle tooltips" title="Sensibilité en % [1-20] de la variation sonore pour le réglage du volume : une valeur de 5 est recommandée." style="font-size : 1em;color:grey;"></i>
										</sup>
									</label>
									<div class="col-sm-2">
										<input type="text" class="eqLogicAttr" size="6" data-l1key="configuration" data-l2key="volume_inc" placeholder="{{Variation volume}}" />
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">{{Activer modules}}</label>
								<div class="col-sm-9">
									<label class="checkbox-inline">
										<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="enablesqueezebox" />{{Squeezebox}}</label>
									<label class="checkbox-inline">
										<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="enableclock" />{{Clock}}</label>
									<label class="checkbox-inline">
										<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="enablemoods" />{{Moods}}</label>
									<label class="checkbox-inline">
										<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="enablesnapshot" id="snapshot" />{{Photo}}</label>
									<label class="checkbox-inline volumeControlEnable">
										<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="enablevolume" id="volume" />{{Volume}}<sup>
											<i class="fa fa-question-circle tooltips" title="Commande du volume. Nécessite une version modifiée d'openkarotz." style="font-size : 1em;color:grey;"></i>
										</sup></label>
								</div>
							</div>
							<div class="form-group snapshotFtp">
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Server & directory ftp}}
										<sup>
											<i class="fa fa-question-circle tooltips" title="Serveur ftp pour récupérer les photos. Adresse IP du serveur et chemin du répertoire sur le serveur." style="font-size : 1em;color:grey;"></i>
										</sup>
									</label>
									<div class="col-sm-3">
										<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="addrFtp" placeholder="{{Adresse IP ftp}}" />
									</div>
									<div class="col-sm-3">
										<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="remoteDirFtp" placeholder="{{remote dir ftp}}" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{User & password ftp}}
										<sup>
											<i class="fa fa-question-circle tooltips" title="Utilisateur et mot de passe pour le serveur ftp." style="font-size : 1em;color:grey;"></i>
										</sup>
									</label>
									<div class="col-sm-3">
										<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="userFtp" placeholder="{{user ftp}}" />
									</div>
									<div class="col-sm-3">
										<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="passwordFtp" placeholder="{{password ftp}}" />
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">{{Affichage minimal}}</label>
								<div class="col-sm-2">
									<input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="enableminimumdisplay" />
								</div>
							</div>
						</div>
						<div class="col-sm-3">

							<div class="form-group">
								<a class="btn btn-danger" id="bt_replaceCmd">
									<i class="fa fa-search" title="{{Recréer les commandes}}"></i>{{Recréer les commandes}}
								</a>
								<a class="btn btn-warning" id="bt_refreshCmd">
									<i class="fa fa-cogs"></i>{{Mise à jour des commandes}}
								</a>
							</div>
						</div>
					</fieldset>
				</form>
				</br>
				<div class="alert alert-info globalRemark" style="display: none"></div>
			</div>

			<div role="tabpanel" class="tab-pane" id="commandtab">
				<table id="table_cmd" class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th>{{Nom}}</th>
							<th>{{Action}}</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>
</div>

<?php include_file('desktop', 'karotz', 'js', 'karotz'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>