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
$this->caption=$data->phrases['dynamic-forms']['captionFormFieldsAddField'];
$this->submitTitle=$data->phrases['dynamic-forms']['submitFormFields'];
$this->fields=array(
	'name' => array(
		'label' => $data->phrases['dynamic-forms']['labelFormFieldsName'],
		'required' => true,
		'tag' => 'input',
		'value' => isset($data->output['field']['name']) ? $data->output['field']['name'] : '',
		'params' => array(
			'type' => 'text',
			'size' => 256
		),
		'description' => '
			<p>
				<b>'.$data->phrases['dynamic-forms']['labelFormFieldsName'].'</b><br />
				'.$data->phrases['dynamic-forms']['descriptionFormFieldsName'].'
			</p>
		'
	),
	'description' => array(
		'label' => $data->phrases['dynamic-forms']['labelFormFieldsDescription'],
		'tag' => 'input',
		'params' => array(
			'type' => 'text'
		),
		'description' => '
			<p>
				<b>'.$data->phrases['dynamic-forms']['labelFormFieldsDescription'].'</b><br />
				'.$data->phrases['dynamic-forms']['descriptionFormFieldsDescription'].'
			</p>
		',
		'required' => false
	),
	'type' => array(
		'label' => $data->phrases['dynamic-forms']['labelFormFieldsType'],
		'tag' => 'select',
		'options' => array(
			'textbox',
			'textarea',
			'checkbox',
			'select',
			'timezone',
			'password',
			array('text'=>'User ID','value'=>'userid'),
		),
		'value' => isset($data->output['field']['type']) ? $data->output['field']['type'] : '',
		'params' => array(
			'type' => 'text',
		),
		'description' => '
			<p>
				<b>'.$data->phrases['dynamic-forms']['labelFormFieldsType'].'</b><br />
				'.$data->phrases['dynamic-forms']['descriptionFormFieldsType'].'
			</p>
		'
	),
	'apiFieldToMapTo' => array(
		'label' => $data->phrases['dynamic-forms']['labelFormFieldsAPIFieldToMapTo'],
		'required' => false,
		'tag' => 'input',
		'value' => isset($data->output['field']['apiFieldToMapTo']) ? $data->output['field']['apiFieldToMapTo'] : '',
		'params' => array(
			'type' => 'text',
			'size' => 256
		)
	),
	'required' => array(
		'label' => $data->phrases['dynamic-forms']['labelFormFieldsRequired'],
		'tag' => 'input',
		'params' => array(
			'type' => 'checkbox',
			'checked' => !empty($data->output['field']['required'])?'checked':'',
		)
	),
	'enabled' => array(
		'label' => $data->phrases['dynamic-forms']['labelFormFieldsEnabled'],
		'tag' => 'input',
		'params' => array(
			'type' => 'checkbox',
			'checked' => !empty($data->output['field']['enabled'])?'checked':'',
		)
	),
	'isEmail' => array(
		'label' => $data->phrases['dynamic-forms']['labelFormFieldsIsEmail'],
		'tag' => 'input',
		'params' => array(
			'type' => 'checkbox',
			'checked' => !empty($data->output['field']['isEmail'])?'checked':'',
		)
	),
	'compareTo' => array(
		'label' => $data->phrases['dynamic-forms']['labelFormFieldsCompareTo'],
		'tag' => 'select',
		'options' => $data->output['fieldList'],
		'value' => isset($data->output['field']['compareTo']) ? $data->output['field']['compareTo'] : '',
		'params' => array(
			'type' => 'text',
		),
		'description' => '
			<p>
				<b>'.$data->phrases['dynamic-forms']['labelFormFieldsCompareTo'].'</b><br />
				'.$data->phrases['dynamic-forms']['descriptionFormFieldsCompareTo'].'
			</p>
		'
	),
	'moduleHook' => array(
		'label' => $data->phrases['dynamic-forms']['labelFormFieldsModuleHook'],
		'tag' => 'select',
		'options' => array(
			array(
				'value' => NULL,
				'text' => 'No Hook'
			)
		),
		'value' => isset($data->output['field']['moduleHook']) ? $data->output['field']['moduleHook'] : '',
		'description' => '
			<p>
				<b>'.$data->phrases['dynamic-forms']['labelFormFieldsModuleHook'].'</b><br />
				'.$data->phrases['dynamic-forms']['descriptionFormFieldsModuleHook'].'
			</p>
		'
	),
	'group' => array(
		'label' => $data->phrases['dynamic-forms']['fieldGroup'],
		'tag' => 'select',
		'options' => array(
			array(
				'value' => 0,
				'text' => $data->phrases['dynamic-forms']['noGroup']
			)
		),
		'value' => isset($data->output['field']['fieldGroup']) ? $data->output['field']['fieldGroup'] : '',
	),
	'displayStyle' => array(
		'label' => $data->phrases['dynamic-forms']['labelDisplayStyle'],
		'tag' => 'select',
		'options' => array(
			array('value' => 0,'text' => $data->phrases['dynamic-forms']['labelDisplayStyleBlock']),
			array('value' => 1,'text' => $data->phrases['dynamic-forms']['labelDisplayStyleInline']),
		),
		'value' => isset($data->output['field']['displayStyle']) ? $data->output['field']['displayStyle'] : '',
	),
);
foreach($this->fields as $fieldName=>$field){
	if(empty($field['params']['checked'])){
		unset($this->fields[$fieldName]['params']['checked']);
	}
}
$hooks=glob('modules/*/*.dynamicForms*php');
foreach($hooks as $hook){
	$parts=explode('/',$hook);
	$hook=explode('.',end($parts));
	if(empty($data->output['moduleShortName'][$hook[0]])){
		continue;
	}
	if(count($hook)===3){
		$this->fields['moduleHook']['options'][] = array(
			'text' => $hook[0],
			'value' => $hook[0]
		);
	}elseif(count($hook)===4){
		$this->fields['moduleHook']['options'][] = array(
			'text' => $hook[0].'.'.$hook[2],
			'value' => $hook[0].'.'.$hook[2]
		);
	}
}
foreach($data->output['fieldGroups'] as $fieldGroup){
	$this->fields['group']['options'][]=array(
		'value' => $fieldGroup['id'],
		'text'  => $fieldGroup['groupName'],
	);
}