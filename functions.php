<?php if (!defined('IS_SYSTEM')) exit; 
if (!function_exists('getFurnishAparmentsTypeList')) {
	function getFurnishAparmentsTypeList() {
		global $db;
		$result = array();
		$sql = "SELECT `FURNISH_APART_TYPE_ID`, `FurnishApartmentTypeName`
				  FROM `".TABLE_PREFIX."FurnishApartmentsTypes`
				 WHERE `FurnishApartmentTypeActive` = 1
				 ORDER BY `FurnishApartmentTypeName`";
		if ($res = $db->Query($sql)) {
			$result = $db->FetchRowSet($res);
		}
		return $result;
	}
}
if (!function_exists('isExistsFurnishApartType')) {
	function isExistsFurnishApartType($furnish_apart_type_id) {
		global $db;
		$sql = "SELECT `FURNISH_APART_TYPE_ID` 
				FROM `".TABLE_PREFIX."FurnishApartmentsTypes` 
				WHERE `FURNISH_APART_TYPE_ID` = ".(int)$furnish_apart_type_id;
		$res = $db->Query($sql);
		if ($db->NumRows($res)) {
			return true;
		}
		return false;
	}
}
if (!function_exists('getFurnishApartTypeInfo')) {
	function getFurnishApartTypeInfo($furnish_apart_type_id) {
		global $db;

		$result = array();

		$sql = "SELECT `FurnishApartmentTypeName`, `FurnishApartmentTypeActive` 
				FROM `".TABLE_PREFIX."FurnishApartmentsTypes` WHERE `FURNISH_APART_TYPE_ID` = ".(int)$furnish_apart_type_id;
		if ($res = $db->Query($sql)) {
			$result = $db->FetchRow($res);

		}
		return $result;
	}
}
if (!function_exists('isExistsFurnishApartTemplate')) {
	function isExistsFurnishApartTemplate($furnish_apart_template_id) {
		global $db;
		$sql = "SELECT `FURNISH_APART_TEMPLATE_ID` 
				FROM `".TABLE_PREFIX."FurnishApartmentsTemplates` 
				WHERE `FURNISH_APART_TEMPLATE_ID` = ".(int)$furnish_apart_template_id;
		$res = $db->Query($sql);
		if ($db->NumRows($res)) {
			return true;
		}
		return false;
	}
}

if (!function_exists('isExistsFurnishApartWorkType')) {
	function isExistsFurnishApartWorkType($furnish_apart_work_type_id) {
		global $db;
		$sql = "SELECT `FURNISH_APART_WORK_TYPE_ID` 
				FROM `".TABLE_PREFIX."FurnishApartmentsWorkTypes` 
				WHERE `FURNISH_APART_WORK_TYPE_ID` = ".(int)$furnish_apart_work_type_id;
		$res = $db->Query($sql);
		if ($db->NumRows($res)) {
			return true;
		}
		return false;
	}
}
if (!function_exists('getFurnishApartmentsListSQLFilter')) {
	function getFurnishApartListSQLFilter($post) {
		$filter_arr = array();
		// Типы
		if (isset($post['furnish_apart_types']) && is_array($post['furnish_apart_types']) && count($post['furnish_apart_types']) > 0) {
			$filter_arr['furnish_apart_types'] = $post['furnish_apart_types'];
		}
		// Название шаблона
		if (isset($post['furnish_apart_template_name']) && $post['furnish_apart_template_name'] <> '') {
			$filter_arr['furnish_apart_template_name'] = $post['furnish_apart_template_name'];
		}
		// Описание
		if (isset($post['furnish_apart_template_description']) && $post['furnish_apart_template_description'] <> '') {
			$filter_arr['furnish_apart_template_description'] = $post['furnish_apart_template_description'];
		}
		// Уникальность
		if (isset($post['furnish_apart_template_unique']) && $post['furnish_apart_template_unique'] <> '') {
			$filter_arr['furnish_apart_template_unique'] = $post['furnish_apart_template_unique'];
		}
		// Активность
		if (isset($post['furnish_apart_template_active']) && $post['furnish_apart_template_active'] <> '') {
			$filter_arr['furnish_apart_template_active'] = $post['furnish_apart_template_active'];
		}
		// Виды работ
		if (isset($post['furnish_apart_template_work_types']) && is_array($post['furnish_apart_template_work_types']) && count($post['furnish_apart_template_work_types']) > 0) {
			$filter_arr['furnish_apart_template_work_types'] = $post['furnish_apart_template_work_types'];
		}
		// Дата создания шаблона
		if (isset($post['start_create_date']) && $post['start_create_date'] <> '') {
			$filter_arr['start_create_date'] = $post['start_create_date'];
		}
		if (isset($post['end_create_date']) && $post['end_create_date'] <> '') {
			$filter_arr['end_create_date'] = $post['end_create_date'];
		}
		return $filter_arr;
	}
}
if (!function_exists('getFurnishApartTemplateList')) {
	function getFurnishApartTemplateList($filter_arr = array(), $offset = 0, $limit = 10) {
		global $db;
		
		$result = array();
		$where = array();
		$LIMIT_SQL = '';
		// Типы
		if (isset($filter_arr['furnish_apart_types']) && is_array($filter_arr['furnish_apart_types']) && count($filter_arr['furnish_apart_types']) > 0) {
			$filterTypeArr = array();
			foreach ($filter_arr['furnish_apart_types'] as $type) {
				if (isset($type) && (int)$type != 0) {
					$filterTypeArr[] = (int)$type;
				}
			}	
			if (isset($filterTypeArr) && is_array($filterTypeArr) && count($filterTypeArr) > 0) {
				$where[] = "`F_A_T`.`REF_FURNISH_APART_TYPE_ID` IN (".implode(',', $filterTypeArr).")";
			}
		}
		//Название шаблона
		if (isset($filter_arr['furnish_apart_template_name']) && $filter_arr['furnish_apart_template_name'] <> '') {
			$where[] = "`F_A_T`.`FurnishApartmentTemplateName` LIKE '%".cStr::toBase($filter_arr['furnish_apart_template_name'])."%'";
		}
		// Описание шаблона
		if (isset($filter_arr['furnish_apart_template_description']) && $filter_arr['furnish_apart_template_description'] <> '') {
			$where[] = "`F_A_T`.`FurnishApartmentTemplateDescription` LIKE '%".cStr::toBase($filter_arr['furnish_apart_template_description'])."%'";
		}
		// Признак уникальности
		if (isset($filter_arr['furnish_apart_template_unique']) && $filter_arr['furnish_apart_template_unique'] <> '' && in_array($filter_arr['furnish_apart_template_unique'], array('0', '1'))) {
			$where[] = "`F_A_T`.`FurnishApartmentTemplateUnique` = ". (int)$filter_arr['furnish_apart_template_unique'];
		}
		// Признак активности
		if (isset($filter_arr['furnish_apart_template_active']) && $filter_arr['furnish_apart_template_active'] <> '' && in_array($filter_arr['furnish_apart_template_active'], array('0', '1'))) {
			$where[] = "`F_A_T`.`FurnishApartmentTemplateActive` = ".(int)$filter_arr['furnish_apart_template_active'];
		}
		// Виды работ
		if (isset($filter_arr['furnish_apart_template_work_types']) && is_array($filter_arr['furnish_apart_template_work_types']) && count($filter_arr['furnish_apart_template_work_types']) > 0) {
			$wt_arr = array();

			foreach ($filter_arr['furnish_apart_template_work_types'] as $wt_id) {
				if(isset($wt_id) && (int)$wt_id != 0)
				$wt_arr[] = (int)$wt_id;
			}
			if (count($wt_arr) > 0) {
				$where[] = "`F_A_W_T`.`REF_WORK_TYPE_ID` IN (".implode(',', $wt_arr).")";
			}
		}
		// Дата создания
		if (isset($filter_arr['start_create_date']) && $filter_arr['start_create_date'] <> '' && isset($filter_arr['end_create_date']) && $filter_arr['end_create_date'] <> '') {
			$where[] = "`F_A_T`.`FurnishApartmentTemplateCreateDate` >= STR_TO_DATE('" .$filter_arr['start_create_date']."', '%d.%m.%Y') AND `F_A_T`.`FurnishApartmentTemplateCreateDate`< ADDDATE(STR_TO_DATE('".$filter_arr['end_create_date']."', '%d.%m.%Y'),  INTERVAL 1 DAY)";
		}
		// LIMIT
		if (isset($limit)) {
			$LIMIT_SQL = isset($offset) && (int)$offset > 0
							? " LIMIT ".(int)$offset.", 10"
							: " LIMIT 0, 10";
		}
		// LIMIT END

		$where = sizeof($where) > 0 ? "WHERE ".implode(" AND ", $where) : '';

		$sql = "	SELECT `F_A_T`.`FURNISH_APART_TEMPLATE_ID`, `F_A_T`.`REF_FURNISH_APART_TYPE_ID`, `F_A_T`.`FurnishApartmentTemplateName`, 
						`F_A_T`.`FurnishApartmentTemplateUnique`, `F_A_T`.`FurnishApartmentTemplateActive`, 
						`F_A_T`.`FurnishApartmentTemplateDescription`, `F_A_Types`.`FurnishApartmentTypeName`,
						GROUP_CONCAT(CASE WHEN `W_T`.`REF_WORK_TYPE_ID` IS NULL THEN `W_T`.`WorkTypeName` ELSE 
						CONCAT(`W_T2`.`WorkTypeName`, '->', `W_T`.`WorkTypeName`) END ORDER BY `W_T`.`WorkTypeName` ASC SEPARATOR '</br> ') as `TemplateWorkTypeName`,
						GROUP_CONCAT(DISTINCT `W_T`.`WORK_TYPE_ID` ORDER BY `W_T`.`WORK_TYPE_ID` ASC SEPARATOR ', ') as `TemplateWorkTypeId`,
						DATE_FORMAT(`F_A_T`.`FurnishApartmentTemplateCreateDate`, '%d.%m.%Y %H:%i') as `DateCreate`
					FROM `".TABLE_PREFIX."FurnishApartmentsTemplates` as `F_A_T` INNER JOIN 
				 		`".TABLE_PREFIX."FurnishApartmentsTypes` as `F_A_Types` ON `F_A_T`.`REF_FURNISH_APART_TYPE_ID` = `F_A_Types`.`FURNISH_APART_TYPE_ID` LEFT JOIN 
				 		`".TABLE_PREFIX."FurnishApartmentsWorkTypes` as `F_A_W_T` ON `F_A_T`.`FURNISH_APART_TEMPLATE_ID` = `F_A_W_T`.`REF_FURNISH_APART_TEMPLATE_ID` LEFT JOIN 
				 		`".TABLE_PREFIX."WorkTypes` as `W_T` ON `F_A_W_T`.`REF_WORK_TYPE_ID`=`W_T`.`WORK_TYPE_ID` LEFT JOIN
			          	`".TABLE_PREFIX."WorkTypes` AS `W_T2` ON `W_T`.`REF_WORK_TYPE_ID` = `W_T2`.`WORK_TYPE_ID`
						{$where} AND `F_A_Types`.`FurnishApartmentTypeActive` = 1
					GROUP BY `F_A_T`.`FURNISH_APART_TEMPLATE_ID`, `F_A_T`.`REF_FURNISH_APART_TYPE_ID`, `F_A_T`.`FurnishApartmentTemplateName`, 
						`F_A_T`.`FurnishApartmentTemplateUnique`, `F_A_T`.`FurnishApartmentTemplateActive`, 
						`F_A_T`.`FurnishApartmentTemplateDescription`, `F_A_Types`.`FurnishApartmentTypeName`
					ORDER BY `F_A_T`.`FurnishApartmentTemplateSi` DESC {$LIMIT_SQL}";

		$res = $db->Query($sql);
		if ($db->NumRows($res) > 0) {
			$result = $db->FetchRowSet($res);
		}
		return $result;	
	}
}
if (!function_exists('getFurnishApartTemplateInfo')) {
	function getFurnishApartTemplateInfo($furnish_apart_template_id) {

		global $db;
		$result = array();

		if (isset($furnish_apart_template_id) && isExistsFurnishApartTemplate($furnish_apart_template_id)) {
			$sql = "SELECT `F_A_T`.`FURNISH_APART_TEMPLATE_ID`, `F_A_T`.`FurnishApartmentTemplateName`, `F_A_T`.`REF_FURNISH_APART_TYPE_ID`, `F_A_T`.`FurnishApartmentTemplateUnique`, 
						`F_A_T`.`FurnishApartmentTemplateActive`, `F_A_T`.`FurnishApartmentTemplateDescription`, `F_A_Types`.`FurnishApartmentTypeName`,
						GROUP_CONCAT(DISTINCT `W_T`.`WorkTypeName` ORDER BY `W_T`.`WorkTypeName` ASC SEPARATOR ', ') as `TemplateWorkTypeName`,
						GROUP_CONCAT(DISTINCT `W_T`.`WORK_TYPE_ID` ORDER BY `W_T`.`WORK_TYPE_ID` ASC SEPARATOR ', ') as `TemplateWorkTypeId`
					FROM `".TABLE_PREFIX."FurnishApartmentsTemplates` as `F_A_T` INNER JOIN 
						`".TABLE_PREFIX."FurnishApartmentsTypes` as `F_A_Types` ON `F_A_T`.`REF_FURNISH_APART_TYPE_ID` = `F_A_Types`.`FURNISH_APART_TYPE_ID` LEFT JOIN 
						`".TABLE_PREFIX."FurnishApartmentsWorkTypes` as `F_A_W_T` ON `F_A_T`.`FURNISH_APART_TEMPLATE_ID` = `F_A_W_T`.`REF_FURNISH_APART_TEMPLATE_ID` LEFT JOIN 
						`".TABLE_PREFIX."WorkTypes` as `W_T` ON `F_A_W_T`.`REF_WORK_TYPE_ID`=`W_T`.`WORK_TYPE_ID`
					WHERE `F_A_T`.`FURNISH_APART_TEMPLATE_ID` = ".(int)$furnish_apart_template_id."
					GROUP BY `F_A_T`.`FURNISH_APART_TEMPLATE_ID`, `F_A_T`.`FurnishApartmentTemplateName`, `F_A_T`.`REF_FURNISH_APART_TYPE_ID`, `F_A_T`.`FurnishApartmentTemplateUnique`,
					`F_A_T`.`FurnishApartmentTemplateActive`, `F_A_T`.`FurnishApartmentTemplateDescription`, `F_A_Types`.`FurnishApartmentTypeName`";

			if ($res = $db->Query($sql)) {
				$result = $db->FetchRow($res);
			}
		}
		return $result;
	}
}
if (!function_exists('getFurnishApartWorkTypeList')) {
	function getFurnishApartWorkTypeList($furnish_apart_template_id) {
		global $db;
		$result = array();

		if (isset($furnish_apart_template_id) && isExistsFurnishApartTemplate($furnish_apart_template_id)) {

			$sql = "SELECT `WT`.`WORK_TYPE_ID`, (CASE WHEN `WT`.`REF_WORK_TYPE_ID` IS NULL THEN `WT`.`WorkTypeName` ELSE `WT2`.`WorkTypeName` END) as `WorkTypeName`, 
						(CASE WHEN `WT`.`REF_WORK_TYPE_ID` IS NULL THEN NULL ELSE `WT`.`WorkTypeName` END) as `SubWorkTypeName`
					FROM `".TABLE_PREFIX."WorkTypes` as `WT` INNER JOIN 
						`".TABLE_PREFIX."FurnishApartmentsWorkTypes` as `FAT` ON `WT`.`WORK_TYPE_ID`=`FAT`.`REF_WORK_TYPE_ID` INNER JOIN 
						`".TABLE_PREFIX."WorkTypes` as `WT2` ON `WT`.`REF_WORK_TYPE_ID` = `WT2`.`WORK_TYPE_ID`
					WHERE `FAT`.`REF_FURNISH_APART_TEMPLATE_ID` = ".(int)$furnish_apart_template_id."
					ORDER BY `FAT`.`FURNISH_APART_WORK_TYPE_ID` ASC";
			
			if ($res = $db->Query($sql)) {
				$result = $db->FetchRowSet($res);
			}	
		}
		return $result;
	}
}
if (!function_exists('getWorkTypeNameByFurnishApartWorkTypeId')) {
	function getWorkTypeNameByFurnishApartWorkTypeId($furnish_apart_work_type_id) {
		global $db;
		$result = array();
		$sql = 'SELECT `WT`.`WorkTypeName` 
				FROM `WorkTypes` as `WT`
				INNER JOIN `FurnishApartmentsWorkTypes` as `FAWT`
				ON `FAWT`.`REF_WORK_TYPE_ID` = `WT`.`WORK_TYPE_ID`
				WHERE `FAWT`.`FURNISH_APART_WORK_TYPE_ID` = '.(int)$furnish_apart_work_type_id;

		if ($res = $db->Query($sql)) {
			$result = $db->FetchRow($res);
		}
		return $result['WorkTypeName'];
	}
}
if (!function_exists('getFurnishApartWorkTypeId')) {
	function getFurnishApartWorkTypeId($furnish_apart_template_id, $work_type_id) {
		if (!isset($furnish_apart_template_id) || !isExistsFurnishApartTemplate($furnish_apart_template_id)) {
			throw new Exception('Не существует шаблона отделки');
		}
		if (!isset($work_type_id) || !isExistsWorkType($work_type_id)) {
			throw new Exception('Не существует типа работ');
		}
		global $db;

		$FURNISH_APART_WORK_TYPE_ID = null;

		$sql = "SELECT `FURNISH_APART_WORK_TYPE_ID` 
				FROM `".TABLE_PREFIX."FurnishApartmentsWorkTypes`
				WHERE `REF_FURNISH_APART_TEMPLATE_ID` = ".(int)$furnish_apart_template_id."
				AND `REF_WORK_TYPE_ID` = ".(int)$work_type_id;
		if ($res=$db->Query($sql)) {
			$FURNISH_APART_WORK_TYPE_ID = $db->FetchRow($res);
		}
		return $FURNISH_APART_WORK_TYPE_ID['FURNISH_APART_WORK_TYPE_ID'];
	}
}
if (!function_exists('addFurnishApartWorkTypeToTemplate')) {
	function addFurnishApartWorkTypeToTemplate($furnish_apart_template_id, $work_type_id) {
		global $db;
		$FURNISH_APART_WORK_TYPE_ID = null;

		if (!isAccessToEvent('Edit', 'FurnishApartments')) {
			throw new Exception('Нет прав для добавления типа работ');
		}
		if (!isset($furnish_apart_template_id) || !isExistsFurnishApartTemplate($furnish_apart_template_id)) {
			throw new Exception('Не существует шаблона отделки');
		}
		if (!isset($work_type_id) || !isExistsWorkType($work_type_id)) {
			throw new Exception('Не существует типа работ');
		}

		$existFurnishApartWorkType = getFurnishApartWorkTypeId($furnish_apart_template_id, $work_type_id);

		if (isset($existFurnishApartWorkType)) {
			throw new Exception('Такой тип работ уже добавлен');
		}

		$sql = "INSERT INTO `".TABLE_PREFIX."FurnishApartmentsWorkTypes`
			(`REF_FURNISH_APART_TEMPLATE_ID`, `REF_WORK_TYPE_ID`)
			VALUES (
			'".(int)$furnish_apart_template_id."',
			'".(int)$work_type_id."')";
			
		if ($db->Query($sql)) {
			$FURNISH_APART_WORK_TYPE_ID = $db->NextID();
			$WorkTypeName = getWorkTypeInfo($work_type_id);
			audit(212, 24, (int)$furnish_apart_template_id, (int)$FURNISH_APART_WORK_TYPE_ID, ''.$WorkTypeName['WorkTypeName'].'', '', '');
		}
		return $FURNISH_APART_WORK_TYPE_ID;
	}
}
if (!function_exists('delFurnishApartWorkTypeFromTemplate')) {
	function delFurnishApartWorkTypeFromTemplate($furnish_apart_template_id, $work_type_id) {
		global $db;
		$result = true;
		
		if (!isAccessToEvent('Edit', 'FurnishApartments')) {
			throw new Exception('Нет прав для удаления вида работ');
		}
		if (!isset($furnish_apart_template_id) || !isExistsFurnishApartTemplate($furnish_apart_template_id)) {
			throw new Exception('Не существует шаблона отделки');
		}
		if (!isset($work_type_id) || !isExistsWorkType($work_type_id)) {
			throw new Exception('Не существует вида работ');
		}

		$existFurnishApartWorkType = getFurnishApartWorkTypeId($furnish_apart_template_id, $work_type_id);

		if (!isset($existFurnishApartWorkType)) {
			throw new Exception('Не существует вида работ в шаблоне');
		}

		$WorkTypeName = getWorkTypeNameByFurnishApartWorkTypeId($existFurnishApartWorkType);

		$sql = "DELETE FROM `".TABLE_PREFIX."FurnishApartmentsWorkTypes` 
				WHERE `REF_FURNISH_APART_TEMPLATE_ID` = ".(int)$furnish_apart_template_id."
				AND `REF_WORK_TYPE_ID` = ". (int)$work_type_id;

		if (!$db->Query($sql)) {
			$error = $db->Error();
			throw new Exception('Ошибка удаления вида работ из шаблона '.$error['message']);
		}
		audit(213, 24, (int)$furnish_apart_template_id, (int)$existFurnishApartWorkType, ''.$WorkTypeName.'', '', '');
		return $result;
	}
}
if (!function_exists('addFurnishApartTemplate')) {
	function addFurnishApartTemplate($furnish_apart_template_name, $furnish_apart_template_type_id, $furnish_apart_template_active, $furnish_apart_template_unique, $furnish_apart_template_description) {
		global $db;

		$NEW_TEMPLATE_ID = null;

		if (!isAccessToEvent('Add', 'FurnishApartments')) {
			throw new Exception('Нет прав для добавления шаблона отделки');
		}
		if (!isset($furnish_apart_template_name) || $furnish_apart_template_name == '') {
			throw new Exception('Заполните все обязательные поля');
		}
		if (isExistsFurnishApartTemplateName($furnish_apart_template_name)) {
			throw new Exception('Шаблон с таким названием уже существует');
		}
		if (!isset($furnish_apart_template_type_id) || !isExistsFurnishApartType($furnish_apart_template_type_id)) {
			throw new Exception('Такого типа шаблона не существует');
		}
		
		$sql = "INSERT INTO `".TABLE_PREFIX."FurnishApartmentsTemplates` (
					`REF_FURNISH_APART_TYPE_ID`,
					`FurnishApartmentTemplateName`,
					`FurnishApartmentTemplateUnique`,
					`FurnishApartmentTemplateActive`,
					`FurnishApartmentTemplateDescription`
					)
				VALUES (
					'".(int)$furnish_apart_template_type_id."',
					'".cStr::toBase($furnish_apart_template_name)."',
					".(int)$furnish_apart_template_unique.",
					".(int)$furnish_apart_template_active.",
					".((isset($furnish_apart_template_description) && $furnish_apart_template_description <> '') ? "'".cStr::toBase($furnish_apart_template_description)."'" : 'NULL')."
		)";

		if ($db->Query($sql)) {
			$NEW_TEMPLATE_ID = $db->NextID();
			$NewFurnishApartTemplateInfo = getFurnishApartTemplateInfo($furnish_apart_template_type_id);
			audit(206, 24, $NEW_TEMPLATE_ID, null, 'Название: '.cStr::toBase($furnish_apart_template_name).'; Тип: '.cStr::toBase($NewFurnishApartTemplateInfo['FurnishApartmentTypeName']).'; '.((isset($furnish_apart_template_description) && $furnish_apart_template_description <> '') ? 'Описание: '.cStr::toBase($furnish_apart_template_description) .'; ' : '').'Активность: '.((int)$furnish_apart_template_active == 0 ? 'Нет' : 'Да').'; Уникальность: '.((int)$furnish_apart_template_unique == 0 ? 'Нет' : 'Да').'','', ''); 
		}
		else {
			$error = $db->Error();
			throw new Exception('Ошибка добавления шаблона отделки '.$error['message']);
		}
		return $NEW_TEMPLATE_ID;
	}
}
if (!function_exists('isExistsFurnishApartTemplateName')) {
	function isExistsFurnishApartTemplateName($furnish_apart_template_name) {
		if (!isset($furnish_apart_template_name) && $furnish_apart_template_name == '') {
			throw new Exception('Не задано имя шаблона');	
		}
		global $db;
		$sql = "SELECT `FurnishApartmentTemplateName` 
				FROM `".TABLE_PREFIX."FurnishApartmentsTemplates` 
				WHERE `FurnishApartmentTemplateName` = '".cStr::toBase($furnish_apart_template_name)."'";
		$res = $db->Query($sql);
		if ($db->NumRows($res)) {
			return true;
		}
		return false;
	}
}
if (!function_exists('editFurnishApartTemplate')) {
	function editFurnishApartTemplate($furnish_apart_template_id, $furnish_apart_template_name, $furnish_apart_template_type_id, $furnish_apart_template_unique, $furnish_apart_template_active, $furnish_apart_template_description) {
		global $db;

		if (!isAccessToEvent('Edit', 'FurnishApartments')) {
			throw new Exception('Нет прав для редактирования шаблона отделки');
		}
		if (!isset($furnish_apart_template_id) || !isExistsFurnishApartTemplate($furnish_apart_template_id)) {
			throw new Exception('Не существует шаблона отделки');
		}
		if (!isset($furnish_apart_template_type_id) || (int)$furnish_apart_template_type_id == 0 ) {
			throw new Exception('Заполните все обязательные поля');
		}
		if (!isExistsFurnishApartType($furnish_apart_template_type_id)) {
			throw new Exception('Такого типа шаблона не существует');
		}
		if (!isset($furnish_apart_template_name) || $furnish_apart_template_name == '') {
			throw new Exception('Заполните все обязательные поля');
		}

		$FurnishApartTemplateInfo = getFurnishApartTemplateInfo($furnish_apart_template_id);
		// Проверяем существует ли такое название шаблона

		if ($FurnishApartTemplateInfo['FurnishApartmentTemplateName'] <> cStr::toBase($furnish_apart_template_name)) {

			if (isExistsFurnishApartTemplateName($furnish_apart_template_name)) {
				throw new Exception('Шаблон с таким названием уже существует');
			}
		}

		$sql = "UPDATE `".TABLE_PREFIX."FurnishApartmentsTemplates` 
					SET 
						`REF_FURNISH_APART_TYPE_ID`= ".(int)$furnish_apart_template_type_id.",
						`FurnishApartmentTemplateName` = '".cStr::toBase($furnish_apart_template_name)."',
						`FurnishApartmentTemplateUnique` = ".(int)$furnish_apart_template_unique.",
						`FurnishApartmentTemplateActive` = ".(int)$furnish_apart_template_active.",
						`FurnishApartmentTemplateDescription` = ".(isset($_POST['FurnishApartmentTemplateDescription']) && $_POST['FurnishApartmentTemplateDescription'] <> '' ? "'".cStr::toBase($_POST['FurnishApartmentTemplateDescription'])."'" : 'NULL')."
					WHERE `FURNISH_APART_TEMPLATE_ID` = ".(int)$furnish_apart_template_id;

		if ($res=$db->Query($sql)) {

			if ($FurnishApartTemplateInfo['FurnishApartmentTemplateActive'] != (int)$furnish_apart_template_active) {
				audit(207, 24, (int)$furnish_apart_template_id, null, '', $FurnishApartTemplateInfo['FurnishApartmentTemplateActive'], (int)$furnish_apart_template_active);
			}
			if ($FurnishApartTemplateInfo['FurnishApartmentTemplateUnique'] != (int)$furnish_apart_template_unique) {
				audit(208, 24, (int)$furnish_apart_template_id, null, '', $FurnishApartTemplateInfo['FurnishApartmentTemplateUnique'], (int)$furnish_apart_template_unique);
			}
			if ($FurnishApartTemplateInfo['REF_FURNISH_APART_TYPE_ID'] != (int)$furnish_apart_template_type_id) {
				$NewFurnishApartTemplateInfo = getFurnishApartTemplateInfo($furnish_apart_template_id);
				audit(209, 24, (int)$furnish_apart_template_id, null, '', cStr::toBase($FurnishApartTemplateInfo['FurnishApartmentTypeName']), cStr::toBase($NewFurnishApartTemplateInfo['FurnishApartmentTypeName']));
			}
			if ($FurnishApartTemplateInfo['FurnishApartmentTemplateName'] != $furnish_apart_template_name) {
				audit(210, 24, (int)$furnish_apart_template_id, null, '', $FurnishApartTemplateInfo['FurnishApartmentTemplateName'], cStr::toBase($furnish_apart_template_name));
			}
			if ($FurnishApartTemplateInfo['FurnishApartmentTemplateDescription'] != $furnish_apart_template_description) {
				audit(211, 24, (int)$furnish_apart_template_id, null, '', $FurnishApartTemplateInfo['FurnishApartmentTemplateDescription'], cStr::toBase($furnish_apart_template_description));
			}
			return true;
		}
		else {
			$error = $db->Error();
			throw new Exception('Ошибка редактирования шаблона отделки '.$error['message']);	
		}
	}
}
if (!function_exists('getFurnishApartHistoryList')) {
	function getFurnishApartAudits($furnish_apart_template_id) {
		global $db;
		$result = array();

		if (isset($furnish_apart_template_id) && isExistsFurnishApartTemplate($furnish_apart_template_id)) {
			$sql = "SELECT `A`.`AUDIT_ID`, `A`.`REF_AUDIT_EVENT_ID`, `A`.`REF_USER_ID`, `A`.`AuditBefore`, `A`.`AuditAfter`, DATE_FORMAT(`A`.`DateCreate`, '%d.%m.%Y %h:%s') as `DateCreate`,
		  		 		`U`.`UserPerson`, `AE`.`AuditEventName`, `A`.`AuditDescription`
			  		FROM `".TABLE_PREFIX."Audits` as `A` INNER JOIN
				       	`".TABLE_PREFIX."Users` as `U` ON `A`.`REF_USER_ID` = `U`.`USER_ID` INNER JOIN
				       	`".TABLE_PREFIX."AuditEvents` as `AE` ON `AE`.`AUDIT_EVENT_ID` = `A`.`REF_AUDIT_EVENT_ID`
			 		WHERE `A`.`REF_PARENT_TABLE_ID` IN (24) AND
				   		`A`.`REF_PARENT_ID` = ".(int)$furnish_apart_template_id."
			 		ORDER BY `A`.`DateCreate` DESC, `A`.`AUDIT_ID` DESC";
		
			if ($res = $db->Query($sql)) {
				$result = $db->FetchRowSet($res);
			}
		}	
		return $result;
	}
}