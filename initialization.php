<?php if (!defined('IS_SYSTEM')) exit;

$FurnishApartTemplateInfo = null;
if (isset($vars['get']['Unit'])) {
	$vars['get']['Unit'] = (int)$vars['get']['Unit'];
	if (!isExistsFurnishApartTemplate($vars['get']['Unit'])) {
		unset($vars['get']['Unit']);
	} else {
		$FurnishApartTemplateInfo = getFurnishApartTemplateInfo($vars['get']['Unit']);
	}
}