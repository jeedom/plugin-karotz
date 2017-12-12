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
	<div class="col-lg-2">
		<div class="bs-sidebar">
			<ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
				<a class="btn btn-default eqLogicAction"
				style="width: 100%; margin-top: 5px; margin-bottom: 5px;"
				data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter un
			Karotz}}</a>
			<li class="filter" style="margin-bottom: 5px;"><input
				class="filter form-control input-sm" placeholder="{{Rechercher}}"
				style="width: 100%" /></li>
				<?php
foreach ($eqLogics as $eqLogic) {
	echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
}
?>
			</ul>
		</div>
	</div>
	<div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
		<legend>{{Gestion}}</legend>
		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction" data-action="add"
			style="text-align: center; background-color: #ffffff; height: 120px; margin-bottom: 10px; padding: 5px; border-radius: 2px; width: 160px; margin-left: 10px;">
			<i class="fa fa-plus-circle" style="font-size: 5em; color: #94ca02;"></i>
			<br> <span
			style="font-size: 1.1em; position: relative; top: 23px; word-break: break-all; white-space: pre-wrap; word-wrap: break-word;; color: #94ca02">Ajouter</span>
		</div>
	</div>
	<legend>{{Mes Karotzs}}</legend>
	<input class="form-control" placeholder="{{Rechercher}}" style="margin-bottom:4px;" id="in_searchEqlogic" />
	<div class="eqLogicThumbnailContainer">
		<?php
foreach ($eqLogics as $eqLogic) {
	$opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
	echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="text-align: center; background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
	echo '<img src="' . $plugin->getPathImgIcon() . '" height="105" width="95" />';
	echo "<br>";
	echo '<span class="name" style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;">' . $eqLogic->getHumanName(true, true) . '</span>';
	echo '</div>';
}
?>
	</div>
</div>
<div class="col-lg-10 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px; display: none;">
	<a class="btn btn-success eqLogicAction pull-right" data-action="save"><i
		class="fa fa-check-circle"></i> {{Sauvegarder}}</a> <a
		class="btn btn-danger eqLogicAction pull-right" data-action="remove"><i
		class="fa fa-minus-circle"></i> {{Supprimer}}</a> <a
		class="btn btn-default eqLogicAction pull-right"
		data-action="configure"><i class="fa fa-cogs"></i> {{Configuration
	avancée}}</a>
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation"><a class="eqLogicAction cursor"
			aria-controls="home" role="tab"
			data-action="returnToThumbnailDisplay"><i
			class="fa fa-arrow-circle-left"></i></a></li>
			<li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-tachometer"></i> {{Equipement}}</a></li>
			<li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Commandes}}</a></li>
		</ul>
		<div class="tab-content" style="height: calc(100% - 50px); overflow: auto; overflow-x: hidden;">
			<div role="tabpanel" class="tab-pane active" id="eqlogictab">
				<br />
				<div class="col-sm-6">
					<form class="form-horizontal">
						<fieldset>
							<div class="form-group">
								<label class="col-sm-3 control-label">{{Nom de l'équipement}}</label>
								<div class="col-sm-4">
									<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display: none;" /> <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement}}" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">{{Objet parent}}</label>
								<div class="col-sm-4">
									<select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
										<option value="">{{Aucun}}</option>
										<?php
foreach (object::all() as $object) {
	echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
}
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
	echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
	echo '</label>';
}
?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label"></label>
								<div class="col-sm-9">
									<label class="checkbox-inline"><input type="checkbox"
										class="eqLogicAttr" data-l1key="isEnable" checked />{{Activer}}</label>
										<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked />{{Visible}}</label>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{Adresse IP}}</label>
									<div class="col-sm-3">
										<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="addr" placeholder="{{Adresse IP}}" />
									</div>
									<div class="col-sm-5">
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
										<select id="sel_voice" class="form-control eqLogicAttr"
										data-l1key="configuration" data-l2key="ttsVoice"
										placeholder="{{Voix TTS}}">
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
									<label class="col-sm-6 control-label" >{{Variation volume}}
										<sup>
											<i class="fa fa-question-circle tooltips" title="Sensibilité en % [1-20] de la variation sonore pour le réglage du volume : une valeur de 5 est recommandée." style="font-size : 1em;color:grey;"></i>
										</sup>
									</label>
									<div class="col-sm-2">
										<input type="text" class="eqLogicAttr" size="6"
										data-l1key="configuration" data-l2key="volume_inc"
										placeholder="{{Variation volume}}" />
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
															<input type="text" class="eqLogicAttr form-control"
															data-l1key="configuration" data-l2key="addrFtp"
															placeholder="{{Adresse IP ftp}}" />
														</div>
														<div class="col-sm-3">
															<input type="text" class="eqLogicAttr form-control"
															data-l1key="configuration" data-l2key="remoteDirFtp"
															placeholder="{{remote dir ftp}}" />
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3 control-label">{{User & password ftp}}
															<sup>
																<i class="fa fa-question-circle tooltips" title="Utilisateur et mot de passe pour le serveur ftp." style="font-size : 1em;color:grey;"></i>
															</sup>
														</label>
														<div class="col-sm-3">
															<input type="text" class="eqLogicAttr form-control"
															data-l1key="configuration" data-l2key="userFtp"
															placeholder="{{user ftp}}" />
														</div>
														<div class="col-sm-3">
															<input type="text" class="eqLogicAttr form-control"
															data-l1key="configuration" data-l2key="passwordFtp"
															placeholder="{{password ftp}}" />
														</div>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 control-label">{{Affichage minimal}}</label>
													<div class="col-sm-2">
														<input type="checkbox" class="eqLogicAttr"
														data-l1key="configuration" data-l2key="enableminimumdisplay" />
													</div>
												</div>
											</fieldset>
										</form>
									</div>
									<div class="col-sm-6">
										<form class="form-horizontal">
											<fieldset>
												<div class="form-group">
													<label class="col-sm-2 control-label"></label>
													<div class="col-sm-8">
														<a class="btn btn-danger" id="bt_replaceCmd"><i
															class="fa fa-search" title="{{Recréer les commandes}}"></i>
														{{Recréer les commandes}}</a>
														<a	class="btn btn-warning" id="bt_refreshCmd"
														<i class="fa fa-cogs"></i>
													{{Mise à jour des commandes}}</a>
												</div>
											</div>

											<div class="form-group expertModeVisible">
												<label class="col-sm-3 control-label">{{Création}}</label>
												<div class="col-sm-3">
													<span class="eqLogicAttr label label-default"
													data-l1key="configuration" data-l2key="createtime"
													title="{{Date de création de l'équipement}}"
													style="font-size: 1em; cursor: default;"></span>
												</div>
												<label class="col-sm-3 control-label">{{Communication}}</label>
												<div class="col-sm-3">
													<span class="eqLogicAttr label label-default"
													data-l1key="status" data-l2key="lastCommunication"
													title="{{Date de dernière communication}}"
													style="font-size: 1em; cursor: default;"></span>
												</div>
											</div>

											<center>
												<img src="plugins/karotz/core/template/images/karotz.jpg" data-original=".jpg"
												id="img_device" class="img-responsive"
												style="max-height: 250px;" />
											</center>
										</fieldset>
									</form>
								</br>
								<div class="alert alert-info globalRemark" style="display: none"></div>
							</div>

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

		<?php include_file('desktop', 'karotz', 'js', 'karotz');?>
		<?php include_file('core', 'plugin.template', 'js');?>
