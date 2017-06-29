<?php if (!defined('IS_SYSTEM')) exit;?>
<div class="page-header">
	<h1>Шаблон отделки  <small><?php echo $vars['get']['Event'] == 'Add' ? 'Создание' : 'Редактирование'; ?></small></h1>
	<a class="btn" href="<?php echo '/'.$_GET['System'].'/'.$_GET['Module'].'/List/'; ?>"><i class="icon-arrow-left"></i> Вернуться к списку</a>
</div>
<div id="FurnishApartTemplateForm" class="form-horizontal">
	<div id="ErrorMsg"></div>
	<div class="control-group">
		<label class="control-label">Активность:</label>
		<div class="controls">
			<input type="checkbox" id="FurnishApartmentTemplateActive" <?php echo $vars['post']['FurnishApartmentTemplateActive'] == 1 ? 'checked="checked"' : ''; ?>/>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Уникальность:</label>
		<div class="controls">
			<input type="checkbox" id="FurnishApartmentTemplateUnique" <?php echo $vars['post']['FurnishApartmentTemplateUnique'] == 1 ? 'checked="checked"' : ''; ?> />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Тип: <span style="color:red;">*</span></label>
		<div class="controls">
			<select class="span8" id="FurnishApartmentTemplateType">
				<option value="0">Не выбрано</option>
				<?php 
				$FurnishApartmentsTypes = getFurnishAparmentsTypeList();

				foreach ($FurnishApartmentsTypes as $type) { ?>
					<option <?php echo (isset($vars['post']['FurnishApartmentType']) && $vars['post']['FurnishApartmentType'] == $type['FURNISH_APART_TYPE_ID']) ? 'selected' : ''; ?> value="<?php echo $type['FURNISH_APART_TYPE_ID']; ?>"><?php echo $type['FurnishApartmentTypeName']; ?></option>	
				<?php } ?>		
			</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Название: <span style="color:red;">*</span></label>
		<div class="controls">
			<input type="text" class="span8" id="FurnishApartmentTemplateName" value="<?php echo isset($vars['post']['FurnishApartmentTemplateName']) ? cStr::toInput($vars['post']['FurnishApartmentTemplateName']) : ''; ?>"/>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Описание:</label>
		<div class="controls">
			<textarea rows="5" class="span8" id="FurnishApartmentTemplateDescription"/><?php echo isset($vars['post']['FurnishApartmentTemplateDescription']) ? cStr::toInput($vars['post']['FurnishApartmentTemplateDescription']) : ''; ?></textarea>
		</div>
	</div>

	<div class="control-group">
		<div class="controls">
			<button onclick=<?php echo ($vars['get']['Event'] == 'Add') ? "addNewTemplate(); return false;" : "editTemplate(); return false; getAuditsTable(".$_GET['Unit']."); return false;"; ?> class="btn btn-success"><?php echo ($vars['get']['Event'] == 'Add') ? 'Создать' : 'Сохранить';?></button>
		</div>
	</div>

	<input type="hidden" id="submit_type" value="<?php echo cStr::toInput($vars['get']['Event']); ?>" />
	<input type="hidden" id="is_submit" value="true" />
	<input type="hidden" id="FurnishApartmentTemplateId" value="<?php echo cStr::toInput($vars['get']['Unit']); ?>" />
</div>
<?php if ($_GET['Event'] == 'Edit') {
	if (isAccessToEvent('Edit', 'FurnishApartments')) { ?>
		<div class="tableWorkTypes container" style="width: 100%; margin: 0; padding: 0;">	
			<div class="tabbable span10">
			  <ul class="nav nav-tabs">
			    <li class="active"><a href="#FurnishApartWorkTypes" data-toggle="tab">Виды работ</a></li>
			    <li><a href="#FurnishApartAudits" data-toggle="tab" onclick="getAuditsTable(<?php echo $_GET['Unit']; ?>); return false;">История</a></li>
			  </ul>
			  <div class="tab-content">
			    <div class="tab-pane active" id="FurnishApartWorkTypes">
			      <table class="table table-bordered" id="FurnishApartWorkTypesTable">
			      	<thead>
			      	<th>№</th>
			      	<th>Название</th>
			      	<th></th>
			      </thead>
			      <tbody></tbody>
			      </table>
			    </div>
			    <div class="tab-pane" id="FurnishApartAudits">
			      <table class="table table-bordered" id="FurnishApartAuditsTable">
			      	<thead>
			      		<tr>
					      	<th>Дата</th>
					      	<th>Пользователь</th>
					      	<th>Событие</th>
					      	<th>Описание</th>
					      	<th>Старое значение</th>
					      	<th>Новое значение</th>
				      	</tr>
			      	</thead>
			      	<tbody></tbody>
			      </table>
			    </div>
			  </div>
			</div>	
		</div>
		<script type="text/javascript">
		$('document').ready(function(){
			addWorkTypesTable(<?php echo $_GET['Unit']; ?>);
		});
		</script> 
<?php }
	else { ?>
	 <div class="alert alert-danger">Нет доступа для редактирования шаблона</div> <?php
	}
}?>
<script type="text/javascript">
var Timer;
function addNewTemplate() {
	var addNewTemplate = $.ajax ({
		url: '/Materials/modules/FurnishApartments/ajax-actions.php',
		method: 'post',
		data: {
		'FurnishApartmentTemplateActive': (($('#FurnishApartmentTemplateActive').is(':checked')) ? 1 : 0),
		'FurnishApartmentTemplateUnique': (($('#FurnishApartmentTemplateUnique').is(':checked')) ? 1 : 0),
		'FurnishApartmentTemplateTypeId': $('#FurnishApartmentTemplateType').val(),
		'FurnishApartmentTemplateName': $('#FurnishApartmentTemplateName').val(),
		'FurnishApartmentTemplateDescription': $('#FurnishApartmentTemplateDescription').val(),
		'SubmitType': $('#submit_type').val(),
		'IsSubmit': $('#is_submit').val()
		}
	});
	addNewTemplate.done(function(msg) {
		if ((msg * 1) > 0) {
			window.location = '/Materials/FurnishApartments/Edit/' + msg + '/';
		}
		else { 
			$('#ErrorMsg').html(msg);
			$('#ErrorMsg').fadeIn(400);
			Timer = setTimeout(function(){
				$('#ErrorMsg').fadeOut(400);
			}, 3000);
		}
	});
	addNewTemplate.fail(function(jqXHR, textStatus) {
		alert('Ошибка ajax: ' + textStatus);
	});
}
function editTemplate() {
	var editTemplate = $.ajax ({
		url: '/Materials/modules/FurnishApartments/ajax-actions.php',
		method: 'post',
		data: {
		'FurnishApartmentTemplateActive': (($('#FurnishApartmentTemplateActive').is(':checked')) ? 1 : 0),
		'FurnishApartmentTemplateUnique': (($('#FurnishApartmentTemplateUnique').is(':checked')) ? 1 : 0),
		'FurnishApartmentTemplateTypeId': $('#FurnishApartmentTemplateType').val(),
		'FurnishApartmentTemplateName': $('#FurnishApartmentTemplateName').val(),
		'FurnishApartmentTemplateDescription': $('#FurnishApartmentTemplateDescription').val(),
		'FurnishApartmentTemplateId': $('#FurnishApartmentTemplateId').val(),
		'SubmitType': $('#submit_type').val(),
		'IsSubmit': $('#is_submit').val()
		},
		dataType: 'html'
	});
	editTemplate.done(function(msg) {
		if (msg*1 != 0) {
			$('#ErrorMsg').html(msg);
			$('#ErrorMsg').fadeIn(400);
			Timer = setTimeout(function(){
			$('#ErrorMsg').fadeOut(400);
			 	}, 3000);
		}
		else {
			$('#ErrorMsg').html('<div class="alert alert-success">Шаблон сохранен</div>');
		 	$('#ErrorMsg').fadeIn(400);
		 	Timer = setTimeout(function(){
				$('#ErrorMsg').fadeOut(400);
			}, 1000);
		}
		getAuditsTable($('#FurnishApartmentTemplateId').val());
	});
	editTemplate.fail(function(jqXHR, textStatus) {
		alert('Ошибка ajax: ' + textStatus);
	});
}
function addWorkTypesTable(furnishApartTemplateId) {
	var addWorkTypesTable = $.ajax ({
		url: '/Materials/modules/FurnishApartments/ajax-form-work-type-table.php',
		method: 'post',
		data: {
			'event': 'Edit',
			'furnishApartTemplateId': furnishApartTemplateId
		},
		dataType: 'html'
	});
	addWorkTypesTable.done(function(msg) {
		$('#FurnishApartWorkTypesTable tbody').html(msg);
		$('#AddFurnishApartWorkType').select2();
	});
	addWorkTypesTable.fail(function(jqXHR, textStatus) {
		alert('Ошибка ajax: ' + textStatus);
	});
}
function getAuditsTable(furnishApartTemplateId) {
	var getAuditsTable = $.ajax ({
		url: '/Materials/modules/FurnishApartments/ajax-furnish-apartment-template-audits.php',
		method: 'post',
		data: {
			'furnishApartTemplateId': furnishApartTemplateId
		},
		dataType: 'html'
	});
	getAuditsTable.done(function(msg) {
		if(msg != '') {
			$('#FurnishApartAuditsTable tbody').html(msg);
		}
	});
	getAuditsTable.fail(function(jqXHR, textStatus) {
		alert('Ошибка ajax: ' + textStatus);
	});
}
function addWorkTypeToFurnishApartTemplate (furnishApartTemplateId) {
	var addWorkTypeToFurnishApartTemplate = $.ajax ({
		url: '/Materials/modules/FurnishApartments/ajax-add-work-type-to-template.php',
		method: 'post',
		data: {
			'FurnishApartTemplateId': furnishApartTemplateId,
			'FurnishApartWorkType' : $('#AddFurnishApartWorkType').val()
		}
	});
	addWorkTypeToFurnishApartTemplate.done(function(msg) {
		$('#ErrorMsg').html(msg);
		$('#ErrorMsg').fadeIn(400);
		addWorkTypesTable(furnishApartTemplateId);

		Timer = setTimeout(function(){
			$('#ErrorMsg').fadeOut(400);
		}, 1000);
	});
	addWorkTypeToFurnishApartTemplate.fail(function(jqXHR, textStatus) {
		alert('Ошибка ajax: ' + textStatus);
	});
}

function delWorkTypeFromFurnishApartTemplate (furnishApartTemplateId, WorkTypeId) {
	var delWorkTypeFromFurnishApartTemplate = $.ajax({
		url: '/Materials/modules/FurnishApartments/ajax-delete-work-type.php',
		method: 'post',
		data: {
			'FurnishApartTemplateId': furnishApartTemplateId,
			'WorkTypeId' : WorkTypeId,
		}
	});
	delWorkTypeFromFurnishApartTemplate.done(function(msg) {
		$('#ErrorMsg').html(msg);
		$('#ErrorMsg').fadeIn(400);
		addWorkTypesTable(furnishApartTemplateId);

		Timer = setTimeout(function(){
			$('#ErrorMsg').fadeOut(400);
		}, 1000);
	});
	delWorkTypeFromFurnishApartTemplate.fail(function(jqXHR, textStatus) {
		alert('Ошибка ajax: ' + textStatus);
	});
}
</script>
