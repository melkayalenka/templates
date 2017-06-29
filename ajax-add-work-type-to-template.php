<?php
define('IS_SYSTEM', true);
include_once '../../init.environment.php';
include_once DIR_FPATH.'modules/FurnishApartments/functions.php';
include_once DIR_FPATH.'modules/WorkTypes/functions.php';

try {
	if (!isAccessToEvent('Edit', 'FurnishApartments')) {
		throw new Exception('Нет доступа на добавление видов работ');
	}
	if (!isset($_POST['FurnishApartTemplateId']) && !isExistsFurnishApartTemplate($_POST['FurnishApartTemplateId'])) {
		throw new Exception('Не существует шаблона отделки');
	}
	if (!isset($_POST['FurnishApartWorkType']) && !isExistsWorkType($_POST['FurnishApartWorkType'])) {
		throw new Exception('Не существует типа работ');
	}

	$newWorkTypeId = addFurnishApartWorkTypeToTemplate($_POST['FurnishApartTemplateId'], $_POST['FurnishApartWorkType']);
	
	if (isset($newWorkTypeId)) {
		echo '<div class="alert alert-success">Вид работ успешно добавлен</div>';
	}
	else {
		throw new Exception('Ошибка. Вид работ не добавлен.');
	}
}
catch (Exception $e) {
	echo '<div class="alert alert-error">'.$e->getMessage().'</div>';
}
	