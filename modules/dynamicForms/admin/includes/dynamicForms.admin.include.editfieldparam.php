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
		$data->output['abort']=true;
        $data->output['abortMessage']='<h2>'.$data->phrases['core']['accessDeniedHeading'].'</h2>'.$data->phrases['core']['accessDeniedMessage'];
		return;
	}	
	if($data->action[3]===false){
		$data->output['abort']=true;
		$data->output['abortMessage']='<h2>'.$data->phrases['core']['invalidID'].'</h2>';
		return;
	}
	$data->action[3]=intval($data->action[3]);
	$statement=$db->prepare('getFieldParamByID','admin_dynamicForms');
	$statement->execute(array(':id' => $data->action[3]));
	$fieldParam=$statement->fetch();
	if($fieldParam===false){
		$data->output['abort']=true;
		$data->output['abortMessage']='<h2>'.$data->phrases['core']['invalidID'].'</h2>';
		return;
	}
  $data->output['fieldParam']['param']=$fieldParam['param'];
  $data->output['fieldParam']['value']=$fieldParam['value'];
  $data->output['fromForm']=new formHandler('formFieldParam',$data,true);
  if((!empty($_POST['fromForm']))&&($_POST['fromForm']==$data->output['fromForm']->fromForm)) {
		$data->output['fromForm']->populateFromPostData();
		if ($data->output['fromForm']->validateFromPost()) {
      $data->output['fromForm']->sendArray[':form']=$fieldParam['form'];
      $data->output['fromForm']->sendArray[':field']=$fieldParam['id'];
      $statement=$db->prepare('addFieldParam','admin_dynamicForms');
      if($statement->execute($data->output['fromForm']->sendArray)) {
        $data->output['savedOkMessage']=$data->phrases['dynamic-forms']['fieldParamSaveSuccessMessage'];
      } else {
				$data->output['abort'] = true;
				$data->output['abortMessage'] = '<h2>'.$data->phrases['core']['databaseErrorHeading'].'</h2>'.$data->phrases['core']['databaseErrorMessage'];
				return;
      }
			common_updateAcrossLanguageTables($data,$db,'form_fields_params',array('id'=>$fieldParam['id']),array(
				'form' => $fieldParam['form'],
				'field' => $fieldParam['field'],
				'param' =>  $fieldParam['param']
			));
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
?>