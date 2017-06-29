<?php if (!defined('IS_SYSTEM')) exit; ?>
<div class="page-header">
	<h1>Шаблоны отделки<small> Список</small></h1>
</div>
<?php if (isAccessToEvent('Add', 'FurnishApartments')) { ?>
<div class="row-fluid">
	<a href="/Materials/FurnishApartments/Add/" class="btn btn-success"><i class="icon-plus icon-white"></i> Добавить</a>
	<a href="#" class="btn" onclick="filterList(0);"><i class="icon-refresh"></i> Обновить</a>
	<br /><br />
</div>
<?php } ?>
<div id="ErrorMsg"></div>
<table id="tableFurnishApartmentTemplatesList" class="table table-striped table-bordered" style="width: auto;">
	<thead>
		<tr>
			<th></th>
			<th>Тип</th>
			<th>Название</th>
			<th>Описание</th>
			<th>Уникальность</th>
			<th>Активность</th>
			<th>Виды работ</th>
			<th>Создан</th>
		</tr>
		<tr>
			<th></th>
			<th>
				<select id="filterFurnishApartmentsTypes" onchange="filterList(0);" class="multiselect" multiple="multiple">
					<?php 
					$FurnishApartmentsTypes = getFurnishAparmentsTypeList();
					foreach ($FurnishApartmentsTypes as $type) { ?>
						<option value="<?php echo $type['FURNISH_APART_TYPE_ID']; ?>"><?php echo $type['FurnishApartmentTypeName']; ?></option>	
					<?php } ?>
				</select>
			</th>
			<th>
				<input type="text" class="span3" id="filterFurnishApartmentsTemplateName" onchange="filterList(0);">
			</th>
			<th>
				<input type="text" class="span3" id="filterFurnishApartmentsTemplateDescription" onchange="filterList(0);">
			</th>
			<th>
				<select id="FilterFurnishApartTemplateUnique" onchange="filterList(0);">
					<option>Все</option>
					<option value="1">Да</option>	
					<option value="0">Нет</option>	
				</select>
			</th>
			<th>
				<select id="FilterFurnishApartTemplateActive" onchange="filterList(0);">
					<option>Все</option>
					<option value="1">Да</option>	
					<option value="0">Нет</option>	
				</select>
			</th>
			<th>
				<select id="filterFurnishApartmentsWorkTypes" class="multiselect" multiple="multiple" onchange="filterList(0);">
					<?php 
						$WorkTypesList = getWorkTypesList(false, false, null, 'm'); 
						foreach ($WorkTypesList as $WorkType) { ?>
								<optgroup label="<?php echo cStr::htmlspecialchars($WorkType['WorkTypeName']); ?>">
									<?php if ($WorkType['CountChildren'] == 0) { ?>
									<option value="<?php echo $WorkType['WORK_TYPE_ID']; ?>" <?php echo $WorkType['CountChildren'] > 0 ? 'disabled' : ''; ?>><?php echo $WorkType['WorkTypeName']; ?></option>
									<?php } ?>
									<?php 
										$WorkTypesInner = getChildrenWorkTypesList($WorkType['WORK_TYPE_ID'], false, false, null, 'm'); 
											foreach ($WorkTypesInner as $WorkTypeInner) { ?>
													<option value="<?php echo $WorkTypeInner['WORK_TYPE_ID']; ?>">--- <?php echo $WorkTypeInner['WorkTypeName'];?></option>
									<?php } ?>
								</optgroup>
					<?php } ?>
				</select>
			</th>
			<th><?php
					$startRange = date("d.m.Y", strtotime("-3 years")).' - '.date("d.m.Y");
					//$startRange = '';
				?>
				<input value="<?php echo $startRange;?>" type="text" class="daterange" id="filterFurnishApartCreateDate" readonly style="cursor:default!important;background-color:#fff;font-size:65%;width:110px;" onchange="filterList(0);">
				<script type="text/javascript">
					$('#filterFurnishApartCreateDate').daterangepicker({
							format: 'DD.MM.YYYY',
							separator: ' - ',
							ranges: {
								'Сегодня': [new Date(), new Date()],
								'Вчера': [moment().subtract('days', 1), moment().subtract('days', 1)],
								'Текущая неделя': [moment().startOf('week').subtract('days', -1), moment().endOf('week').subtract('days', -1)],
								'Текущий месяц': [moment().startOf('month'), moment().endOf('month')],
								'Последние 7 дней': [moment().subtract('days', 7), new Date()],
								'Последние 14 дней': [moment().subtract('days', 14), new Date()],
								'Последние 30 дней': [moment().subtract('days', 30), new Date()],
								'Весь период': [new Date('1970-01-01'), new Date()],
							},
							locale: {
								applyLabel: 'ОК',
								cancelLabel: 'Отменить',
								fromLabel: 'С',
								toLabel: 'По',
								customRangeLabel: 'Выбрать период',
								fromRangeLabel: 'Период "С"',
								toRangeLabel: 'Период "ПО"',
								daysOfWeek: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
								monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
								firstDay: 1
							},
							showWeekNumbers: true
						}, function(sd, ed) { 
							filterList(0);
						});
				</script></th>
			</tr>
	</thead>
	<tbody></tbody>
</table>
<script type="text/javascript">
$(document).ready(function(){
	$('#filterFurnishApartmentsTypes').multiselect({nonSelectedText: 'Все', buttonWidth: '100%', includeSelectAllOption: true});
	$('#filterFurnishApartmentsWorkTypes').multiselect({nonSelectedText: 'Все', buttonWidth: '100%', enableClickableOptGroups: true, onChange: function(option, checked) { filterList(0); }, enableFiltering: true, enableCaseInsensitiveFiltering: true});
	filterList(0);
});
function filterList(offset) {
	if (offset * 1 > 0) {
		$('#tableFurnishApartmentTemplatesList tbody').first().append('<tr class="loading"><td colspan="200"><strong>Идёт загрузка...</strong><br /><div class="progress progress-striped active"><div class="bar" style="width: 100%;"></div></div></td></tr>');
	} else {
		$('#tableFurnishApartmentTemplatesList tbody').first().html('<tr class="loading"><td colspan="200"><strong>Идёт загрузка...</strong><br /><div class="progress progress-striped active"><div class="bar" style="width: 100%;"></div></div></td></tr>');
	}
	var filterList = $.ajax({
	  	url: '/<?php echo DIR_RPATH; ?>modules/FurnishApartments/ajax-list.php',
	  	type: 'POST',
		cache: false,
      	data: {
	      	'furnish_apart_types': $('#filterFurnishApartmentsTypes').val(),
	      	'furnish_apart_template_name': $('#filterFurnishApartmentsTemplateName').val(),
	      	'furnish_apart_template_description': $('#filterFurnishApartmentsTemplateDescription').val(),
	      	'furnish_apart_template_unique': $('#FilterFurnishApartTemplateUnique').val(),
	      	'furnish_apart_template_active': $('#FilterFurnishApartTemplateActive').val(),
	      	'furnish_apart_template_work_types': $('#filterFurnishApartmentsWorkTypes').val(),
	      	'furnish_apart_template_create_date': $('#filterFurnishApartCreateDate').val(),
	      	'offset': offset * 1
      	},
 	 	dataType: 'html'
	});
  	filterList.done(function(msg) {
  		if (offset*1 > 0) {
			$('#tableFurnishApartmentTemplatesList tbody tr.loading' ).remove();
			$('#tableFurnishApartmentTemplatesList tbody').first().append(msg);
		}
		else {
			$('#tableFurnishApartmentTemplatesList tbody').first().html(msg);
		}
	});

	filterList.fail(function(jqXHR, textStatus) {
		alert('Ошибка запроса: ' + textStatus);
	});
}
</script>