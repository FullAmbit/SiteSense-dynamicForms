<?php
/*
* SiteSense
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@sitesense.org so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade SiteSense to newer
* versions in the future. If you wish to customize SiteSense for your
* needs please refer to http://www.sitesense.org for more information.
*
* @author     Full Ambit Media, LLC <pr@fullambit.com>
* @copyright  Copyright (c) 2011 Full Ambit Media, LLC (http://www.fullambit.com)
* @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/
common_include('libraries/forms.php');
function admin_dynamicFormsBuild($data,$db) {
	//permission check for forms edit
	if(!checkPermission('edit','dynamicForms',$data)) {
		$data->output['abort'] = true;
        $data->output['abortMessage']='<h2>'.$data->phrases['core']['accessDeniedHeading'].'</h2>'.$data->phrases['core']['accessDeniedMessage'];
		return;
	}	
	if($data->action[3] === false){
		$data->output['abort'] = true;
		$data->output['abortMessage']='<h2>'.$data->phrases['core']['invalidID'].'</h2>';
		return;
	}
	$formId = $data->action[3] = intval($data->action[3]);
	$statement = $db->prepare('getFormById','admin_dynamicForms');
	$statement->execute(array(':id' => $data->action[3]));
	$dbform = $statement->fetch();
	if($dbform === false){
		$data->output['abort'] = true;
		$data->output['abortMessage']='<h2>'.$data->phrases['core']['invalidID'].'</h2>';
		return;
	}
	$form = $data->output['fromForm'] = new formHandler('fieldGroups',$data,true);
	if ((!empty($_POST['fromForm'])) && ($_POST['fromForm']==$form->fromForm)) {
		$form->populateFromPostData();
		if ($form->validateFromPost()) {
			$form->sendArray[':formId'] = $dbform['id'];
			$statement = $db->prepare('newFieldGroup','admin_dynamicForms');
			$result = $statement->execute($form->sendArray);
			$fieldId = $db->lastInsertId();
			if(!$result) {
				$data->output['abort'] = true;
				$data->output['abortMessage'] = '<h2>'.$data->phrases['core']['databaseErrorHeading'].'</h2>'.$data->phrases['core']['databaseErrorMessage'];
				return;
			}
			common_populateLanguageTables($data,$db,'form_fields_groups','id',$fieldId);
			if (empty($data->output['secondSidebar'])) {
				$data->output['savedOkMessage']='
					<h2>'.$data->phrases['dynamic-forms']['saveFieldSuccessHeading'].'</h2>
					<div class="panel buttonList">
						<a href="'.$data->linkRoot.'admin/'.$data->output['moduleShortName']['dynamicForms'].'/newField/'.$dbform['id'].'">
							'.$data->phrases['dynamic-forms']['addField'].'
						</a>
						<a href="'.$data->linkRoot.'admin/'.$data->output['moduleShortName']['dynamicForms'].'/listFields/'.$dbform['id'].'">
							'.$data->phrases['dynamic-forms']['returnToFields'].'
						</a>
					</div>';
			}
		}
	}
}
function admin_dynamicFormsShow($data) {
	if (isset($data->output['savedOkMessage'])) {
		echo $data->output['savedOkMessage'];
	} else {
		theme_buildForm($data->output['fromForm']);
	}
}