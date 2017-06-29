<?php define('IS_SYSTEM', true);

include_once '../../init.environment.php';
include_once DIR_FPATH.'modules/FurnishApartments/functions.php';

try {
	//Добавление

	if (isset($_POST['SubmitType']) && ($_POST['SubmitType'] == 'Add')) {
			
			if (!isAccessToEvent('Add', 'FurnishApartments')) {
				throw new Exception('Нет прав для добавления шаблона отделки');	
			}
			if (!isset($_POST['FurnishApartmentTemplateName']) || $_POST['FurnishApartmentTemplateName'] == '') {
				throw new Exception('Заполните все обязательные поля');
			}
			if (!isset($_POST['FurnishApartmentTemplateTypeId']) || !isExistsFurnishApartType($_POST['FurnishApartmentTemplateTypeId'])) {
				throw new Exception('Такого типа шаблона не существует');
			}

			$newTemplateId = addFurnishApartTemplate($_POST['FurnishApartmentTemplateName'], $_POST['FurnishApartmentTemplateTypeId'], $_POST['FurnishApartmentTemplateActive'], $_POST['FurnishApartmentTemplateUnique'], $_POST['FurnishApartmentTemplateDescription']);

			if (isset($newTemplateId) && isExistsFurnishApartTemplate($newTemplateId)) {
				echo $newTemplateId;
			}
			else {
				throw new Exception('Ошибка добавления шаблона');	
			}
		}
	// Редактирование

	if (isset($_POST['SubmitType']) && $_POST['SubmitType'] == 'Edit') {

		if (!isAccessToEvent('Edit', 'FurnishApartments')) {
			throw new Exception('Нет прав для редактирования шаблона отделки');	
		}	
		if (!isset($_POST['FurnishApartmentTemplateId']) && !isExistsFurnishApartTemplate($_POST['FurnishApartmentTemplateId'])) {
			throw new Exception('Такого шаблона отделки не существует');
		}
		if (!isset($_POST['FurnishApartmentTemplateName']) || $_POST['FurnishApartmentTemplateName'] == '') {
			throw new Exception('Заполните все обязательные поля');
		}
		if (!isset($_POST['FurnishApartmentTemplateTypeId']) || !isExistsFurnishApartType($_POST['FurnishApartmentTemplateTypeId'])) {
			throw new Exception('Такого типа шаблона не существует');
		}

		editFurnishApartTemplate($_POST['FurnishApartmentTemplateId'], $_POST['FurnishApartmentTemplateName'], $_POST['FurnishApartmentTemplateTypeId'], $_POST['FurnishApartmentTemplateUnique'], $_POST['FurnishApartmentTemplateActive'], $_POST['FurnishApartmentTemplateDescription']);
	}
}
catch (Exception $e) {
	echo '<div class="alert alert-error">'.$e->getMessage().'</div>';
}