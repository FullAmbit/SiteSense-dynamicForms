<?php
function form_buildContent($data,$db,$attributes) {
    common_include('modules/dynamicForms/dynamicForms.module.php');
	$data->output['fauxaction']=$attributes[0];
	dynamicForms_buildContent($data,$db);
}
function form_content($data,$attributes) {
	if(isset($data->action['error'])){
		switch($data->action['error']){	
			case 'notFound':
				echo "Not Found";
				break;
			case 'accessDenied':
				echo "Access Denied";
			break;
			default:
				echo $data->output['responseMessage'];
			break;
		}
	}else if(isset($data->output['success'])){
		echo $data->output['success'];
	}else{
		echo html_entity_decode($data->output['form']['parsedContentBefore'],ENT_QUOTES,'UTF-8');
		$data->output['customForm']->action=$_SERVER['REQUEST_URI'];
		$data->output['customForm']->build();
		echo html_entity_decode($data->output['form']['parsedContentAfter'],ENT_QUOTES,'UTF-8');
	}
}