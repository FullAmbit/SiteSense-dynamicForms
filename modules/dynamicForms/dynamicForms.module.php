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
function dynamicForms_buildContent($data,$db) {
	common_include('libraries/forms.php');
	$form = false;
	if(isset($data->output['fauxaction'])){
		$action=$data->output['fauxaction'];
	}else{
		$action=$data->action[1];
	}
	if ($action!==false){
		$statement=$db->prepare('getFormByShortName','dynamicForms');
		$statement->execute(array(
			':shortName' => $action
		));
		$form=$statement->fetch();
	}
	if($form === false){
		$data->action['error'] = 'notFound';
		return;
	}
	$data->output['form'] =& $form;
	if(!isset($data->output['fauxaction'])){
		$data->output['pageTitle']=(empty($data->output['form']['title'])?$data->output['form']['name']:$data->output['form']['title']);
	}
	if($form['requireLogin'] == 1 && !isset($data->user['id'])){
		$data->action['error'] = 'accessDenied';
		return;
	}
	// Load Sidebars
	$statement = $db->prepare('getEnabledSidebarsByForm','dynamicForms');
	$statement->execute(array(':formId' => $form['id']));
	$sidebars=$statement->fetchAll();
	if(count($sidebars)>0){
		$data->sidebarList=$sidebars;
	}
	
	// Module List For Hooking
	$moduleList = array_flip($data->output['moduleShortName']);
	$hookedModules = array();
	
	// Get Fields
	$statement = $db->prepare('getFieldsByForm', 'dynamicForms');
	$statement->execute(array(':form' => $form['id']));
	$rawFields = $statement->fetchAll(PDO::FETCH_ASSOC);
	$rawForm = array();
	
	
	// Get Original Values?
	if($data->action[2]=='edit' && $data->action[3]!==FALSE){
		$data->output['rowId']=$rowId=$data->action[3];
	}
	
	foreach($rawFields as $field){
		if($field['enabled'] !== '1'){
			continue;
		}
		$f = array(
			'name' => $field['id'],
			'label' => $field['name'],
			'required' => true
		);
		// Run The Initial Form Function For This Field's Hook
		if($field['moduleHook']){
			$hookParts=explode('.',$field['moduleHook'],2);
			if(count($hookParts)===2){
				$moduleName=$moduleList[$hookParts[0]];
				$target='modules/'.$moduleName.'/'.$hookParts[0].'.dynamicForms.'.$hookParts[1].'.php';
			}elseif(isset($moduleList[$field['moduleHook']])){
				$moduleName = $moduleList[$field['moduleHook']];
				$target = 'modules/'.$moduleName.'/'.$moduleName.'.dynamicForms.php';
			}
			common_include($target);
			if(!isset($hookedModules[$field['moduleHook']])){
				// Field hasn't been hooked yet...therefore the initial form function hasn't been run yet
				$funcName = $moduleName.'_beforeForm';
				if(function_exists($funcName)){
					$formContinue = $funcName($data,$db);
					if($formContinue === FALSE){
						$data->action['error'] = 'BeforeFormExit';
						return;
					}
				}
				$hookedModules[$field['moduleHook']] = $moduleName;
			}
			// Now Are We Editing This Field?
			if(isset($data->output['rowId'])||!empty($data->output['editingField'])){
				// Check To See What Function We Can Run
				$shortName = common_generateShortName($field['name'],TRUE);
				$fieldFunction = $moduleName.'_load'.$shortName.'Value';
				$generalFunction = $moduleName.'_loadDynamicFormFieldValue';
				if(function_exists($fieldFunction)){
					$f['value'] = $fieldFunction($data,$db,$field);
				} else {
					$f['value'] = $generalFunction($data,$db,$field);
				}
			}
		}
		switch($field['type']){
			case 'textbox':
				$f['tag'] = 'input';
				$f['params'] = array('type' => 'text');
				$f['required'] = ($field['required'] == '0') ? false : true;
				$f['validate'] = ($field['isEmail'] == '1') ? 'eMail' : '';
				$f['eMailFailMessage'] = 'Invalid E-Mail Address';
				break;
			case 'textarea':
				$f['tag'] = 'textarea';
				$f['required'] = ($field['required'] == '0') ? false : true;
				$f['params'] = array('cols' => 40,'rows' => 12);
				$f['validate'] = ($field['isEmail'] == '1') ? 'eMail' : '';
				break;
			case 'checkbox':
				$f['tag'] = 'input';
				$f['params'] = array('type' => 'checkbox');
				$f['required'] = ($field['required'] == '0') ? false : true;
				$f['validate'] = ($field['isEmail'] == '1') ? 'eMail' : '';
				break;
			case 'select':
				$f['tag'] = 'select';
				$f['required'] = ($field['required'] == '0') ? false : true;
				// Get Options //
				$statement = $db->prepare('getOptionsByFieldForForm','dynamicForms');
				$statement->execute(array(
					':formId' => $form['id'],
					':fieldId' => $field['id']
				));
				$optionList = $statement->fetchAll(PDO::FETCH_ASSOC);
				foreach($optionList as $optionItem){
					$f['options'][] = $optionItem;
				}
				$f['validate'] = ($field['isEmail'] == '1') ? 'eMail' : '';
				break;
			case 'timezone':	
				$f['tag'] = 'select';
				$f['required'] = ($field['required'] == '0') ? false : true;
				$f['type'] = 'timezone';
				break;
			case 'password':
				$f['tag'] = 'input';
				$f['params'] = array('type' => 'password');
				$f['required'] = ($field['required'] == '0') ? false : true;
				break;
			case 'userid':
				$f['tag'] = 'input';
				$f['params'] = array('type' => 'hidden');
				$f['required'] = ($field['required'] == '0') ? false : true;
				$f['value'] = (isset($data->user['id'])?$data->user['id']:0);
				break;
			default:
				$f['tag'] = 'select';
				$f['type']= $field['type'];
				$f['required'] = ($field['required'] == '0') ? false : true;
				break;
		}
		if($field['compareTo'] > 0){
			$f['compareTo'] = $field['compareTo'];
			$f['compareFailMessage'] = 'The values do not match.';
		}
		$rawForm[$f['name']] = $f;
	}
	
	// Check For URL Replacement Or A Redirect -- from index.php -- modified
	$queryString = implode('/',array_filter($data->action));
	$statement = $db->prepare('findReplacement');
	$statement->execute(array(':url' => $queryString, ':hostname' => $data->hostname));
	if ($row=$statement->fetch(PDO::FETCH_ASSOC)) {
		$url = preg_replace('~' . $row['match'] . '~', $row['replace'], $queryString); // Our New URL
	} else {
		// No Remaps Found...Hmm....Check If This URL Is A Destination Of An Existing Remap
		$statement = $db->prepare("findReverseReplacementNoRedirect");
		$statement->execute(array(
			':url' => rtrim($queryString,"/"),
			':hostname' => $data->hostname
		));
		if($row=$statement->fetch(PDO::FETCH_ASSOC)){				
			// We Found One.
			$replacement = $row['match'];
			$replacement = str_replace('^','',$replacement);
			$replacement = str_replace('(/.*)?$','',$replacement);
			$replacement = $replacement.'\1';		
			$queryString = preg_replace('~'.$row['reverseMatch'].'~',$replacement,$queryString);
			$pos = strpos($_SERVER['REQUEST_URI'],'?');				
			$params = (!$pos) ? '' : substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'],'?'));
			$url = $queryString;
		}
	}
	if(empty($data->output['customFormFields'])){
		$data->output['customFormFields']=array();
	}
	if (isset($url)) {
		$data->output['customForm'] = new customFormHandler($rawForm, $form['shortName'], $form['title'], $data, false,$url,$data->output['customFormFields']);
	} else {
		$data->output['customForm'] = new customFormHandler($rawForm, $form['shortName'], $form['title'], $data, false,NULL,$data->output['customFormFields']);
	}
	$data->output['formFields']=&$rawFields;
	$data->output['customForm']->submitTitle = $data->output['form']['submitTitle'];
	common_parseDynamicValues($data,$data->output['form']['parsedContentBefore'],$db);
	common_parseDynamicValues($data,$data->output['form']['parsedContentAfter'],$db);
	if(isset($_POST['fromForm']) && ($_POST['fromForm'] == $data->output['customForm']->fromForm)){
		$data->output['customForm']->populateFromPostData();
		// Validate Form
		if($data->output['customForm']->validateFromPost()) {
			// Validate Using Module Hooks
			foreach($rawFields as $field){
				$fieldId = $field['id'];
				$fieldValue = $data->output['customForm']->sendArray[':'.$fieldId];
				// Is This Field Hooked And Is The Module Enabled?
				$hookParts=explode('.',$field['moduleHook'],2);
				if($field['moduleHook'] !== NULL && isset($moduleList[$hookParts[0]])){
					$moduleName=$moduleList[$hookParts[0]];
					// Load Phrases For The Module..
					if(!isset($data->phrases[$hookParts[0]])){
						common_loadPhrases($data,$db,$field['moduleHook']);
					}
					$shortName = common_generateShortName($field['name'],TRUE);
					$fieldFunction = $moduleName.'_validate'.$shortName;
					$generalFunction = $moduleName.'_validateDynamicFormField';
					if(function_exists($fieldFunction)){
						$fieldFunction($data,$db,$field,$fieldValue);
					}else{
						$generalFunction($data,$db,$field,$fieldValue);
					}
				}
			}
		}
		// No Errors...Start Saving...
		if($data->output['customForm']->error==FALSE){
			$statement = $db->prepare('newValue', 'dynamicForms');
			$emailText = '';
			// Do We Have A Row Yet For This New Custom Form Data?
			if(!isset($rowId)){
				foreach($rawFields as $field){
					$fieldValue = $data->output['customForm']->sendArray[':'.$field['id']];
					if($fieldValue!==''){ // testing to see if this form entry requires a new row
						$newRow = $db->prepare('newRow', 'dynamicForms');
						$newRow->execute(array(':form' => $form['id']));
						$rowId = $db->lastInsertId();
						break;
					}
				}
			}
			foreach($rawFields as $field){
				$fieldId = $field['id'];
				$fieldValue = $data->output['customForm']->sendArray[':'.$fieldId];
				// Is This Field Hooked And Is The Module Enabled?
				$hookParts=explode('.',$field['moduleHook'],2);
				if($field['moduleHook'] !== NULL && isset($moduleList[$hookParts[0]])){
					$moduleName=$moduleList[$hookParts[0]];
					// Check To See What Function We Can Run
					$shortName = common_generateShortName($field['name'],TRUE);
					$fieldFunction = $moduleName.'_save'.$field['name'];
					$generalFunction = $moduleName.'_saveDynamicFormField';
					if(function_exists($fieldFunction)){
						$fieldFunction($data,$db,$field,$shortName,$fieldValue);
					} else {
						$generalFunction($data,$db,$field,$shortName,$fieldValue);
					}					
				} elseif($fieldValue!=='') {
					$statement->execute(array('row' => $rowId, 'field' => $fieldId, 'value' => $fieldValue));
					$emailText .= $field['name'] . ': ' . $data->output['customForm']->sendArray[':'.$fieldId] . "\n";
					$processedFields[$fieldId] = $field;
				}
			}
			// Call Hooks After Processing / Saving Form Fields
			foreach($hookedModules as $moduleShortName => $moduleName){
				$function = $moduleName.'_afterForm';
				if(function_exists($function)){
					$function($data,$db);
				}
			}
			// API Hook
			if(isset($form['api']{1}) && $form['api'] !== NULL) {
				common_loadPlugin($data,$form['api']);
				if(method_exists($data->plugins[$form['api']],'runFromCustomForm')) {
					$data->plugins[$form['api']]->runFromCustomForm($processedFields,$data->output['customForm']->sendArray);
				}
			}
			// Are We E-Mailing This?
			if(isset($form['eMail']{0})){
				$subject = $form['name'] . ' - Form Data';	
				$from = 'no-reply@fullambit.com';
				$header='From: '. $from . "\r\n";
				$recepients = explode(',',$form['eMail']);
				foreach($recepients as $index => $to){
					mail($to,$subject,wordwrap($emailText,70),$header);
				}
			}
			$data->output['success'] = html_entity_decode($form['parsedSuccessMessage'],ENT_QUOTES,'UTF-8');
		}
	}
}

function dynamicForms_content($data) {
	if(isset($data->action['error'])){
		theme_contentBoxHeader((empty($data->output['form']['title'])?$data->output['form']['name']:$data->output['form']['title']));
		switch($data->action['error']){	
			case 'notFound':
				echo "Not Found";
				break;
			case 'accessDenied':
				echo '<p>Sorry, but you must be logged in to use this form. Please <a href="',$data->linkRoot,'users/login">log in</a> or <a href="',$data->linkRoot,'users/register">register</a>.</p>';
			break;
			default:
				echo $data->output['responseMessage'];
			break;
		}
		theme_contentBoxFooter();
	}else if(isset($data->output['success'])){
		theme_contentBoxHeader((empty($data->output['form']['title'])?$data->output['form']['name']:$data->output['form']['title']));
		echo $data->output['success'];
		theme_contentBoxFooter();
	}else{
		theme_contentBoxHeader((empty($data->output['form']['title'])?$data->output['form']['name']:$data->output['form']['title']));
		echo html_entity_decode($data->output['form']['parsedContentBefore'],ENT_QUOTES,'UTF-8');
		$data->output['customForm']->build();
		echo html_entity_decode($data->output['form']['parsedContentAfter'],ENT_QUOTES,'UTF-8');
		theme_contentBoxFooter();
	}
}