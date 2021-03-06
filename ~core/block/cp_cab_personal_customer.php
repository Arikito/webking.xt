<?php

$GLOBALS['IERA_LINKS'][] = array(
	'title' => $header,
	'url' => _base_url.'/cabinet/personal/'
);
// Подключаем необходимые классы
$Customers = new Customers();
$Cities = new Cities();
$contragents = new Contragents();
$delivery = new Delivery();
$deliveryservice = new DeliveryService();
$regions = new Regions();
// Все классы подключены
$Address = new Address();
$addresses = $Address->GetListByUserId($_SESSION['member']['id_user']);
$tpl->Assign('addresses', $addresses);
/* selecting clear data */

// about customer
$Customers->SetFieldsById($Users->fields['id_user']);
$Customer = $Customers->fields;
$cont_person = explode(' ', $Customer['cont_person']);
// outside managers
$contragents->SetList();
$availablemanagers = $contragents->list;
// regions
$allregions = $Address->GetRegionsList();
// delivery methods
$delivery->SetDeliveryList();
$alldeliverymethods = $delivery->list;

/* selecting saved data */
// city
if($Customer['id_city'] > 0){
	$Cities->GetSavedFields($Customer['id_city']);
	$savedcity = $Cities->fields;
}else{
	$savedcity = false;
}
// delivery method
if($Customer['id_delivery'] > 0){
	$delivery->GetSavedFields($Customer['id_delivery']);
	$saveddeliverymethod = $delivery->fields;
}else{
	$saveddeliverymethod = false;
}
// manager
if($Customer['id_contragent'] > 0){
	$contragents->GetSavedFields($Customer['id_contragent']);
	$savedmanager = $contragents->fields;
}else{
	$savedmanager = false;
}
// temp manager
$tempmanager = false;
$_POST['tempmanager'] = 1;
if($availablemanagers){
	foreach($availablemanagers as $am){
		if(!$savedmanager || $savedmanager['id_user'] == $am['id_user']){
			$_POST['tempmanager'] = 0;
		}
	}
	if($_POST['tempmanager'] == 1){
		$tempmanager = $availablemanagers[array_rand($availablemanagers)];
	}
}
// Select array of available cities if customer's region was saved.
if(isset($savedcity)){
	$availablecities = $Cities->SetFieldsByInput($savedcity['region']);
	if(!$deliveryservice->SetFieldsByInput($savedcity['name'], $savedcity['region'])){
		unset($alldeliverymethods[3]);
	}
	$deliveryservice->SetListByRegion($savedcity['names_regions']);
	$availabledeliveryservices = $deliveryservice->list;
	$delivery->SetFieldsByInput($savedcity['shipping_comp'], $savedcity['name'], $savedcity['region']);
	$availabledeliverydepartment = $delivery->list;
}
/* output data */
$tpl->Assign('Customer', $Customer);
$tpl->Assign('allregions', $allregions);
$tpl->Assign('alldeliverymethods', $alldeliverymethods);
$tpl->Assign('availablecities', $availablecities);
$tpl->Assign('availabledeliveryservices', $availabledeliveryservices);
$tpl->Assign('availabledeliverydepartment', $availabledeliverydepartment);
$tpl->Assign('availablemanagers', $availablemanagers);
$tpl->Assign('savedcity', $savedcity);
$tpl->Assign('saveddeliverymethod', $saveddeliverymethod);
$tpl->Assign('savedmanager', $savedmanager);
$tpl->Assign('tempmanager', $tempmanager);


$success = false;
$tpl->Assign('User', $Users->fields);

if(isset($_POST['save_delivery'])){
	$region = $Address->GetRegionByTitle($_POST['region']);
	$city = $Address->GetCityByTitle($_POST['city'], $region['id']);
	$_POST['id_region'] = $city['id_region'];
	$_POST['id_city'] = $city['id'];
	if(!$Address->AddAddress($_POST)){
		$tpl->Assign('msg', array('type' => 'error', 'text' => 'Адрес не сохранен. Попробуйте повторить действие позже.'));
	}else{
		$tpl->Assign('msg', array('type' => 'success', 'text' => 'Адрес успешно сохранен.'));
	}
	header("Location: ".$_SERVER["HTTP_REFERER"]);
}
$tpl->Assign('content', $tpl->Parse($GLOBALS['PATH_tpl_global'].'cab_'.(isset($_GET['t'])?$_GET['t']:'contacts').'.tpl'));
$parsed_res = array(
	'issuccess'	=> true,
	'html'		=> $tpl->Parse($GLOBALS['PATH_tpl'].'cp_customer_cab_personal.tpl')
);
