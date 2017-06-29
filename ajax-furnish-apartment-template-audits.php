<?php
define('IS_SYSTEM', true);
include_once '../../init.environment.php';
include_once DIR_FPATH.'modules/FurnishApartments/functions.php';

try {
	if (!isAccessToEvent('Edit', 'FurnishApartments')) {
		throw new Exception('Нет доступа');
	}
	if (!isset($_POST['furnishApartTemplateId']) && !isExistsFurnishApartTemplate($_POST['furnishApartTemplateId'])) {
		throw new Exception('Не существует шаблона отделки');
	}
	
	$FurnishApartAudits = getFurnishApartAudits($_POST['furnishApartTemplateId']);

	if (isset($FurnishApartAudits) && is_array($FurnishApartAudits) && count($FurnishApartAudits)>0) {

		foreach ($FurnishApartAudits as $row) { ?>
			<tr>
				<td><?php echo $row['DateCreate'];?></td>			
				<td><?php echo $row['UserPerson'];?></td>	
				<td><?php echo $row['AuditEventName'];?></td>
				<td><?php echo $row['AuditDescription'];?></td>
				<td><?php echo $row['AuditBefore'];?></td>			
				<td><?php echo $row['AuditAfter'];?></td>
			</tr> 
		<?php
		}
	}
}
catch (Exception $e) {
	echo '<tr><td colspan="200"><div class="alert alert-error">'.$e->getMessage().'</div></td></tr>';
}