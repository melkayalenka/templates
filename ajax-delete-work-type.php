<?php
define('IS_SYSTEM', true);
include_once '../../init.environment.php';
include_once DIR_FPATH.'modules/WorkTypes/functions.php';
include_once DIR_FPATH.'modules/FurnishApartments/functions.php';

try {
	if (!isAccessToEvent('Edit', 'FurnishApartments')) {
		throw new Exception('Нет доступа на удаление видов работ');		
	}
	if (!isset($_POST['FurnishApartTemplateId']) && !isExistsFurnishApartTemplate($_POST['FurnishApartTemplateId'])) {
		throw new Exception('Не существует шаблона');	
	}
	if (!isset($_POST['WorkTypeId']) && !isExistsWorkType($_POST['WorkTypeId'])) {
		throw new Exception('Не существует такого вида работ');	
	}
	if (delFurnishApartWorkTypeFromTemplate($_POST['FurnishApartTemplateId'], $_POST['WorkTypeId'])) {
		echo '<div class="alert alert-success">Вид работ успешно удален</div>';
	}
}
catch (Exception $e) {
	echo '<div class="alert alert-error">'.$e->getMessage().'</div>';
}