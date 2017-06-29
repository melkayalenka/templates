<?php
define('IS_SYSTEM', true);
include_once '../../init.environment.php';
include_once DIR_FPATH.'modules/FurnishApartments/functions.php';
include_once DIR_FPATH.'modules/WorkTypes/functions.php';

try {
	if (!isAccessToEvent('Edit', 'FurnishApartments')) {
		throw new Exception('Нет доступа для редактирования списка работ в шаблоне отделки');
	}
	if (!isset($_POST['furnishApartTemplateId']) && !isExistsFurnishApartTemplate($_POST['furnishApartTemplateId'])) {
		throw new Exception('Не существует шаблона отделки');
	}
	if ($_POST['event'] == 'Edit') { 
		$FurnishApartWorkTypeList = getFurnishApartWorkTypeList($_POST['furnishApartTemplateId']);
		if (isset($FurnishApartWorkTypeList) && is_array($FurnishApartWorkTypeList) && count($FurnishApartWorkTypeList) > 0) {
			$num=1;
			foreach ($FurnishApartWorkTypeList as $FurnishApartWorkType) { ?>
				<tr>
					<td><?php echo $num++; ?></td>
					<td><?php echo $FurnishApartWorkType['WorkTypeName'];?></br><small><?php echo $FurnishApartWorkType['SubWorkTypeName'];?></small></td>
					<td><a class="btn btn-danger" onclick="delWorkTypeFromFurnishApartTemplate(<?php echo $_POST['furnishApartTemplateId'];?>, <?php echo $FurnishApartWorkType['WORK_TYPE_ID'];?>); return false;"><i class="icon-remove icon-white"></i></a></td>
				</tr>
			<?php 
			}
		}?>
		<tr>
			<td></td>
			<td> 
				<select id="AddFurnishApartWorkType" class="span8">
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
												<option value="<?php echo $WorkTypeInner['WORK_TYPE_ID']; ?>">--- <?php echo $WorkTypeInner['WorkTypeName']; ?></option>
								<?php } ?>
							</optgroup>
				<?php } ?>
				</select>
			</td>
			<td><a class="btn btn-success" onclick="addWorkTypeToFurnishApartTemplate(<?php echo $_POST['furnishApartTemplateId'] ?>); return false;"><i class="icon-plus icon-white"></i></a></td>
		</tr> <?php 
	}
} 
catch (Exception $e) {
	echo '<tr><td colspan="200"><div class="alert alert-error">'.$e->getMessage().'</div></td></tr>';
}

		