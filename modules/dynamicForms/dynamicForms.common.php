<?php
function dynamicForms_common_getRow($db,$rowId){
	$statement=$db->prepare('getRowById','dynamicForms');
	$statement->execute(array(
		':id'   => $rowId,
	));
	$row=$statement->fetch(PDO::FETCH_ASSOC);
	if(!$row) return false;
	$statement=$db->prepare('getFieldsByForm','dynamicForms');
	$statement->execute(array(
		':form' => $row['form'],
	));
	while($field=$statement->fetch(PDO::FETCH_ASSOC)){
		$fields[$field['id']]=$field;
	}
	$statement=$db->prepare('getValuesByRow','dynamicForms');
	$statement->execute(array(
		':row' => $row['id'],
	));
	$parsedRow=array();
	while($value=$statement->fetch(PDO::FETCH_ASSOC)){
		if(isset($fields[$value['field']])){
			$parsedRow[$fields[$value['field']]['name']]=$value['value'];
		}else{
			$parsedRow[$value['field']]=$value['value'];
		}
	}
	return $parsedRow;
}