<?php
$Address = new Address();
$Users = new Users();

$header = 'Пункты выдачи';
$ii = count($GLOBALS['IERA_LINKS']);
$GLOBALS['IERA_LINKS'][$ii]['title'] = $header;
$tpl->Assign('h1', $header);

$tpl->Assign('regions', $Address->GetRegionsList());

$tpl->Assign('cities', $Address->GetCitiesList());

$tpl->Assign('shipping_companies', $Address->GetShippingCompaniesList());

$tpl->Assign('dealers', $Users->GetDealersList());

if(isset($_GET['limit']) && is_numeric($_GET['limit'])){
	$GLOBALS['Limit_db'] = $_GET['limit'];
}
if((isset($_GET['limit']) && $_GET['limit'] != 'all') || !isset($_GET['limit'])){
	if(isset($_POST['page_nbr']) && is_numeric($_POST['page_nbr'])){
		$_GET['page_id'] = $_POST['page_nbr'];
	}
	$cnt = count($Address->GetWarehousesList());
	$GLOBALS['paginator_html'] = G::NeedfulPages($cnt);
	$limit = ' '.$GLOBALS['Start'].', '.$GLOBALS['Limit_db'];
}else{
	$GLOBALS['Limit_db'] = 0;
	$limit = '';
}


$tpl->Assign('list', $Address->GetWarehousesList(false, $limit));

$tpl_center .= $tpl->Parse($GLOBALS['PATH_tpl'].'cp_warehouses.tpl');
