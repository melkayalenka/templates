<?php
define('IS_SYSTEM', true);

$systemIdentifier = 'Materials';
$moduleIdentifier = 'FurnishApartments';
$pathAction = $systemIdentifier.'/'.$moduleIdentifier.'/';

include_once '../../init.environment.php';
include_once '../../class.string.php';
include_once DIR_FPATH.'modules/'.$moduleIdentifier.'/functions.php';
try {
	if (!isAccessToEvent('List', 'FurnishApartments')) {
		throw new Exception('Нет доступа для получения списка шаблонов отделки');	
	}

	$filterDatePeriod = explode(' - ',$_POST['furnish_apart_template_create_date']);

	if (cStr::checkDate_ddmmYYYY($filterDatePeriod[0]) && cStr::checkDate_ddmmYYYY($filterDatePeriod[1])) {
		$_POST['start_create_date'] = $filterDatePeriod[0];
		$_POST['end_create_date'] = $filterDatePeriod[1];
	}

	$filter_arr = getFurnishApartListSQLFilter($_POST);
	$FurnishApartTemplatesArr = getFurnishApartTemplateList($filter_arr, $_POST['offset']);
	if (isset($FurnishApartTemplatesArr) && is_array($FurnishApartTemplatesArr) && count($FurnishApartTemplatesArr)>0) {

		foreach ($FurnishApartTemplatesArr as $template) {
			?>
			<tr>
				<td><?php
						if (isAccessToEvent('Edit', 'FurnishApartments')) {
							?><a class="btn" href="/Materials/FurnishApartments/Edit/<?php echo $template['FURNISH_APART_TEMPLATE_ID']; ?>/" target="_blank"><i class="icon-pencil"></i></a><?php
						} ?></td>
				<td><?php echo $template['FurnishApartmentTypeName']; ?></td>
				<td><?php echo $template['FurnishApartmentTemplateName']; ?></td>
				<td><?php echo $template['FurnishApartmentTemplateDescription']; ?></td>
				<td><?php echo $template['FurnishApartmentTemplateUnique'] > 0 ? '<span class="badge badge-success">Да</span>' : '<span class="badge badge-important">Нет</span>'; ?></td>
				<td><?php echo $template['FurnishApartmentTemplateActive'] > 0 ? '<span class="badge badge-success">Да</span>' : '<span class="badge badge-important">Нет</span>';; ?></td>
				<td><?php echo $template['TemplateWorkTypeName']; ?></td>
				<td><?php echo $template['DateCreate']; ?></td>
			</tr>
	<?php } ?>
	<tr class="loading">
		<td colspan="100">
			<button class="btn btn-info btn-block" onclick="filterList(<?php echo isset($_POST['offset']) && (int)$_POST['offset'] > 0 ? (int)$_POST['offset'] + 10 : 10; ?>);">Ещё...</button>
		</td>
	</tr>
	<?php
	}
	else {
		if (!isset($_POST['offset']) || (int)$_POST['offset'] == 0) {
			echo '<tr class="loading"><td colspan="200"><div class="alert alert-info">Список пустой</div></td></tr>';
		}	
	}	
}
catch (Exception $e) {
	echo '<tr><td colspan="200"><div class="alert alert-error">'.$e->getMessage().'</div></td></tr>';
}
