<?php
if(isset($GLOBALS['Rewrite'])){
	$tpl->Assign('cabinet_page', $GLOBALS['Rewrite']);
}
foreach($Users->GetGroups() as $group){
	if($group['gid'] == $_SESSION['member']['gid']){
		$profile = $group['name'];
	}
}
switch($profile){
	case 'admin':
		$className = 'users';
		$profile = 'customer';
		break;
	case 'photographer':
		$className = 'users';
		$profile = 'customer';
		break;
	default:
		$className = $profile.'s';
		break;
}
if(class_exists($className)){
	$class = new $className();
	$class->SetFieldsById($_SESSION['member']['id_user']);
	$data = $class->fields;
	if($profile == 'supplier'){
		$data['active_products_cnt'] = $Products->GetProductsCntSupCab(
			array('a.id_supplier' => $class->fields['id_user'], 'a.active' => 1, 'p.visible' => 1),
			' AND a.product_limit > 0 AND (a.price_mopt_otpusk > 0 OR a.price_opt_otpusk > 0)'
		);
		$data['all_products_cnt'] = $Products->GetProductsCntSupCab(array('a.id_supplier'=>$class->fields['id_user'], 'p.visible' => 1));
		$data['moderation_products_cnt'] = count($Products->GetProductsOnModeration($class->fields['id_user']));
	}
	$tpl->Assign($profile, $data);
	$parsed_res = array(
		'issuccess'	=> true,
		'html'		=> $tpl->Parse($GLOBALS['PATH_tpl'].'sb_'.$profile.'_cabinet_navigation.tpl')
	);
}
