<?php

function languages_dynamicForms_admin_en_us(){
	return array(
		'core' => array(
			'forms'                            => 'Forms',
			'permission_dynamicForms_access'   => 'Access Forms',
			'permission_dynamicForms_add'      => 'Add Forms',
			'permission_dynamicForms_delete'   => 'Delete Forms',
			'permission_dynamicForms_edit'     => 'Edit Forms',
			'permission_dynamicForms_viewData' => 'View Submitted Data'
		),
		'returnToForms'                       => 'Return To Forms',
		'returnToFields'                      => 'Return To Fields',
		'deleteFormRejectHeading'             => 'Form Deletion Error',
		'deleteFormRejectMessage'             => 'There was an error deleting the form.',
		'deleteFormCancelledHeading'          => 'Form Deletion Cancelled',
		'deleteFormSuccessMessage'            => 'This form and all associated data has been deleted.',
		'deleteFormConfirmHeading'            => 'Are you sure you want to delete this form?',
		'deleteFieldCancelledHeading'         => 'Field Deletion Cancelled',
		'deleteFieldSuccessHeading'           => 'Field Deleted',
		'deleteFieldSuccessMessage'           => '',
		'deleteFieldConfirmHeading'           => 'Are you sure you want to delete this field?',
		'deleteOptionCancelledHeading'        => 'Option Deletion Cancelled',
		'deleteOptionConfirmHeading'          => 'Are you sure you want to delete this option?',
		'newForm'                             => 'New Form',
		'noFormsExist'                        => 'No forms exist',
		'manageFormsHeading'                  => 'Manage Forms',
		'requireLogin'                        => 'Require Login?',
		'manageFields'                        => 'Manage Fields',
		'viewData'                            => 'View Data',
		'type'                                => 'Type',
		'options'                             => 'Options',
		'addOption'                           => 'Add Option',
		'addField'                            => 'Add Field',
		'noOptionsFound'                      => 'No Options Found',
		'captionFormFieldsAddField'           => 'Add Field',
		'labelFormFieldsName'                 => 'Name',
		'descriptionFormFieldsName'           => 'What is the field called?',
		'labelFormFieldsDescription'          => 'Description',
		'descriptionFormFieldsDescription'    => 'This is the text that will appear in this sidebar. It is not required.',
		'labelFormFieldsType'                 => 'Type',
		'descriptionFormFieldsType'           => 'What kind of input field is it?',
		'labelFormFieldsAPIFieldToMapTo'      => 'API Field',
		'labelFormFieldsRequired'             => 'Required',
		'labelFormFieldsEnabled'              => 'Enabled',
		'labelFormFieldsIsEmail'              => 'E-Mail Validation?',
		'labelFormFieldsCompareTo'            => 'Compare To',
		'descriptionFormFieldsCompareTo'      => 'Does the field have to match another?',
		'labelFormFieldsModuleHook'           => 'Module Hook',
		'descriptionFormFieldsModuleHook'     => 'Should another module process the data from this field?',
		'submitFormFields'                    => 'Save Field',
		'labelFormsName'                      => 'Name',
		'descriptionFormsName'                => 'Descriptions',
		'labelFormsTitle'                     => 'Title',
		'descriptionFormsTitle'               => 'The title of the form displayed at the top.',
		'labelFormsSubmitTitle'               => 'Submit Title',
		'descriptionFormsSubmitTitle'         => 'The text displayed on the submit button.',
		'labelFormsEmail'                     => 'E-Mail',
		'descriptionFormsEmail'               => 'Specify an email address to send submitted form data to. Multiple addresses should be seperated by commas with no spaces.',
		'labelFormsAPI'                       => 'API Hook',
		'labelFormsShowOnMenu'                => 'Create Menu Link',
		'descriptionFormsShowOnMenu'          => 'Adds this static page to the main menu',
		'labelFormsMenuTitle'                 => 'Menu Title',
		'descriptionFormsMenuTitle'           => 'If this element is to be shown on the menu, this is the text will be shown inside it\'s anchor.',
		'labelFormsRawContentBefore'          => 'Raw Content Before',
		'descriptionFormsRawContentBefore'    => 'What will the user read before the form?',
		'labelFormsRawContentAfter'           => 'Raw Content After',
		'descriptionFormsRawContentAfter'     => 'What will the user read after the form?',
		'labelFormsRawSuccessMessage'         => 'Success Message',
		'descriptionFormsRawSuccessMessage'   => 'What do you want to display to the user when the form has been submitted?',
		'labelFormsRequireLogin'              => 'Require Login?',
		'descriptionFormsRequireLogin'        => 'Do you want the browser to redirect? Otherwise the user will see the original url but a different page.',
		'labelFormsTopLevel'                  => 'Top Level',
		'descriptionFormsTopLevel'            => 'Check this if you want the url /form-name as an alternative to /forms/form-name',
		'labelFormsEnabled'                   => 'Enabled',
		'submitFormsTitle'                    => 'Submit Title',
		'captionFormsEditForm'                => 'Edit Form',
		'labelOptionsText'                    => 'Text',
		'labelOptionsValue'                   => 'Value',
		'submitOptions'                       => 'Save Option',
		'saveFormSuccessHeading'              => 'Form Saved Successfully',
		'returnToOptions'                     => 'Return To Options',
		'saveOptionSuccessHeading'            => 'Option Saved Successfully',
		'saveFieldSuccessHeading'             => 'Field Saved Successfully',
		'captionFormsFieldGroups'             => 'Add/Edit Field Group',
		'submitSaveGroup'                     => 'Save Field Group',
		'labelGroupName'                      => 'Group Name',
		'labelGroupLegend'                    => 'Group Legend',
		'descriptionGroupName'                => 'The name of the group, for reference in the admin control panel.',
		'descriptionGroupLegend'              => 'The Group Legend, to be displayed on the frontend.',
		'labelDisplayStyle'                   => 'Display Style',
		'labelDisplayStyleInline'             => 'Inline (checkboxes only)',
		'labelDisplayStyleBlock'              => 'Block (regular)',
		'addFieldGroup'                       => 'Add Field Group',
		'fieldGroups'                         => 'Field Groups',
		'editFieldGroup'                      => 'Edit Field Group',
		'deleteFieldGroup'                    => 'Delete Field Group',
		'listFieldGroups'                     => 'List Field Groups',
		'fieldGroup'                          => 'Field Group',
		'noGroup'                             => 'No Group',
		'parameterFormFieldLabel'             => 'Parameter',
		'formFieldParamsLabel'                => 'Parameters',
		'valueFormFieldLabel'                 => 'Value',
		'paramAlreadyExists'                  => 'Parameter Already Exists',
		'paramAlreadyExistsMessage'           => 'This parameter already exists for this form field. Please try modifying the existing record.',
		'addFieldParam'                       => 'Add Parameter',
		'fieldParamSaveSuccessMessage'        => 'Field paramater saved successfully.',
		'modifyFieldParam'                    => 'Modify Parameter'
		'saveParam'                           => 'Save Parameter'
	);
}
?>