<?php

	$Customer = new Customers();

	$success = false;

	$Customer->SetFieldsById($Users->fields['id_user']);

	$SavedCity = new Cities();
	$SavedCity->GetSavedFields($Customer->fields['id_city']);

	$SavedContragent = new Contragents();
	$SavedContragent->GetSavedFields($Customer->fields['id_contragent']);

	$DeliveryMethod = new Delivery();
	$DeliveryMethod->SetDeliveryList();

	$SavedDeliveryMethod = new Delivery();
	$SavedDeliveryMethod->GetSavedFields($Customer->fields['id_delivery']);

	$Region = new Regions();
	if($Region->SetList())
		$tpl->Assign('regions', $Region->list);

	$Cities = new Cities();
	if($Cities->SetList())
		$tpl->Assign('cities', $Cities->list);

	$Contragent = new Contragents();
	if($Contragent->GetContragentList())
		$tpl->Assign('manager', $Contragent->GetContragentList());

	$DeliveryService = new DeliveryService();
	$Deliverys = new Delivery();

	if(isset($SavedCity->fields)){
		if($DeliveryService->SetListByRegion($SavedCity->fields['names_regions']))
			$tpl->Assign('delivery_services', $DeliveryService->list);
		if($Deliverys->SetFieldsByInput($SavedCity->fields['shipping_comp'], $SavedCity->fields['names_regions']))
			$tpl->Assign('delivery', $Deliverys->list);
	}else{
		if($DeliveryService->SetList())
			$tpl->Assign('delivery_services', $DeliveryService->list);
		if($Deliverys->SetList())
			$tpl->Assign('delivery', $Deliverys->list);
	}

	$Users->SetUser($_SESSION['member']);

	$tpl->Assign('User', $Users->fields);
	$tpl->Assign('Customer', $Customer->fields);
	$tpl->Assign('SavedCity', $SavedCity->fields);
	$tpl->Assign('SavedContragent', $SavedContragent->fields);
	$tpl->Assign('DeliveryMethod', $DeliveryMethod->list);
	$tpl->Assign('SavedDeliveryMethod', $SavedDeliveryMethod->fields);

	if(isset($_POST['apply'])){
		require_once ($GLOBALS['PATH_block'].'t_fnc.php'); // для ф-ции проверки формы
		$nocheck[] = 'keystring';
		if (isset($_POST['passwd']) && $_POST['passwd'] == ''){
			$nocheck[] = 'passwdconfirm';
			$nocheck[] = 'passwd';
		}
		list($err, $errm) = Register_form_validate($nocheck);
        if(!$err){
			$tpl->Assign('msg', 'Информация обновлена.');
			$Users->updateUser($_POST);
			$Customer->updateCustomer($_POST);
			$Customer->updateContPerson($_POST['cont_person']);
			$Customer->updatePhones($_POST['phones']);
			$Customer->updateContragent($_POST['id_manager']);
			$Customer->updateCity($_POST['id_delivery_department']);
			$Customer->updateDelivery($_POST['id_delivery']);
			if(!$Customer->fields['bonus_card']){
				$Customer->registerBonus($_POST);
			}else{
				$Customer->updateBonus($_POST['bonus_card']);
			}
			header("Location: ". _base_url."/cabinet");
        }else{
        	// показываем все заново но с сообщениями об ошибках
        	$tpl->Assign('msg', 'Информация не обновлена.');
            $tpl->Assign('errm', $errm);
            //print_r($errm);
        }
	}

	if(isset($_POST['cancel'])){
		header("Location: ". _base_url."/cabinet/");
	}

	$parsed_res = array('issuccess' => TRUE,
 						'html' 		=> $tpl->Parse($GLOBALS['PATH_tpl'].'cp_customer_cab_inf.tpl'));

?>
