<?php
if (!defined('IS_SYSTEM')) exit;

$systemIdentifier = 'Materials';
$moduleIdentifier = 'FurnishApartments';
$pathAction = $systemIdentifier.'/'.$moduleIdentifier.'/'; 

include_once 'init.environment.php';

ob_start();

include_once DIR_FPATH.'html.header.php';
include_once DIR_FPATH.'modules/'.$moduleIdentifier.'/functions.php';
include_once DIR_FPATH.'modules/WorkTypes/functions.php';
include_once DIR_FPATH.'modules/'.$moduleIdentifier.'/initialization.php';

// Список
if (
	isset($vars['get']['Event']) && $vars['get']['Event'] == 'List'
	&& isAccessToEvent('List', 'FurnishApartments')
) {
	include_once DIR_FPATH.'modules/'.$moduleIdentifier.'/list.php';
}
// Добавление
if (isset($vars['get']['Event']) && $vars['get']['Event'] == 'Add' && isAccessToEvent('Add', 'FurnishApartments')) {
	$UrlAction = getSystemURL(null, 'Add');
	if (!isset($vars['post']['is_submit'])) {
		$vars['post']['FurnishApartmentTemplateActive'] = 1;
		$vars['post']['FurnishApartmentTemplateUnique'] = 1;
	}	
	require_once(DIR_FPATH.'modules/FurnishApartments/form.php');
}
 // Редактирование 
if (!is_null($FurnishApartTemplateInfo) && isset($vars['get']['Event']) && $vars['get']['Event'] == 'Edit' && isAccessToEvent('Edit', 'FurnishApartments')) {
	$UrlAction = getSystemURL($FurnishApartTemplateInfo['FURNISH_APART_TEMPLATE_ID'], 'Edit');

	if (!isset($vars['post']['is_submit'])) {
		$vars['post']['FurnishApartmentTemplateName'] = $FurnishApartTemplateInfo['FurnishApartmentTemplateName'];
		$vars['post']['FurnishApartmentTemplateDescription'] = $FurnishApartTemplateInfo['FurnishApartmentTemplateDescription'];
		$vars['post']['FurnishApartmentTemplateActive'] = (int)$FurnishApartTemplateInfo['FurnishApartmentTemplateActive'] > 0 ? 1 : 0;
		$vars['post']['FurnishApartmentTemplateUnique'] = (int)$FurnishApartTemplateInfo['FurnishApartmentTemplateUnique'] > 0 ? 1 : 0;
		$vars['post']['FurnishApartmentType'] = (int)$FurnishApartTemplateInfo['REF_FURNISH_APART_TYPE_ID'];
	}
	require_once(DIR_FPATH.'modules/FurnishApartments/form.php');
}

include_once DIR_FPATH.'html.footer.php';

$html = ob_get_contents();
ob_end_clean();

header('Content-type: text/html; charset=utf-8');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

echo $html;