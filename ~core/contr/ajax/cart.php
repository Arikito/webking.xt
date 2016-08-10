<?php
if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
	if(!isset($_SESSION['Cart'])){
		$_SESSION['Cart']['products'] = array();
		$_SESSION['Cart']['sum'] = (float) 0;
	}
	if(G::IsLogged()){
		$User = new Users();
		$User->SetUser(G::GetLoggedData());
		$Customer = new Customers();
		$Customer->SetFieldsById($User->fields['id_user']);
		$personal_discount = $Customer->fields['discount'];
	}
	$cart = new Cart();
	if(isset($_POST['action'])){
		switch($_POST['action']){
			case 'duplicate':
				$cart->FillByOrderId($_POST['id_order'], (isset($_POST['add'])?1:''));
				$cart->DBCart();
				echo json_encode(true);
				break;
			case 'GetCartPage':
				unset($parsed_res);
				if(G::IsLogged()){
					$User = new Users();
					$User->SetUser(G::GetLoggedData());
					$tpl->Assign('User', $User->fields);
				}
				// Устанавливаем базовый ценовой режим если пользователь не является менеджером
				if($User->fields['gid'] != _ACL_MANAGER_){
					$_SESSION['price_mode'] = 3;
				}
				// Подключаем необходимые классы
				$cart = new Cart();
				$order = new Orders();
				$customers = new Customers();
				$cities = new Citys();
				$contragents = new Contragents();
				$delivery = new Delivery();
				$deliveryservice = new DeliveryService();
				$regions = new Regions();
				// Все классы подключены
				
				// выборка базовых данных

				// о покупателе
				$customers->SetFieldsById($User->fields['id_user']);
				$customer = $customers->fields;
				$cont_person = explode(' ', $customer['cont_person']);
				$customer['last_name'] = $cont_person[0];
				$customer['first_name'] = isset($cont_person[1])?$cont_person[1]:'';
				$customer['middle_name'] = isset($cont_person[2])?$cont_person[2]:'';
				$customer['phone'] = isset($phones)?$phones:'';
				$tpl->Assign('phone', isset($customer['phones'])?$customer['phones']:'');

				// список всех менеджеров
				$contragents->SetList(isset($_SESSION['member']) && $_SESSION['member']['gid'] == _ACL_CONTRAGENT_?true:false);
				$managers_list = $contragents->list;

				// список всех областей
				$regions->SetList();
				$regions_list = $regions->list;

				// список всех способов доставки
				$delivery->SetDeliveryList();
				$deliverymethods_list = $delivery->list;

				// выборка сохраненной информации

				// сохраненный город
				if(isset($customer['id_city']) && $customer['id_city'] > 0){
					$cities->GetSavedFields($customer['id_city']);
					$saved['city'] = $cities->fields;
				}else{
					$saved['city'] = false;
				}

				// способы доставки
				if(isset($customer['id_delivery']) && $customer['id_delivery'] > 0){
					$delivery->GetSavedFields($customer['id_delivery']);
					$saved['deliverymethod'] = $delivery->fields;
				}else{
					$saved['deliverymethod'] = false;
				}

				// сохраненный менеджер
				if(isset($customer['id_contragent']) && $customer['id_contragent'] > 0){
					$contragents->GetSavedFields($customer['id_contragent']);
					$saved['manager'] = $contragents->fields;
				}else{
					$saved['manager'] = false;
				}

				// временнный менеджер
				$tempmanager = false;
				$_POST['tempmanager'] = 1;
				if($managers_list){
					foreach($managers_list as $am){
						if(!$saved['manager'] || $saved['manager']['id_user'] == $am['id_user']){
							$_POST['tempmanager'] = 0;
						}
					}
					if($_POST['tempmanager'] == 1){
						$tempmanager = $managers_list[array_rand($managers_list)];
					}
				}

				// Выбор доступных городов, если у пользователя была сохранена область
				if(isset($saved['city'])){
					$cities_list = $cities->SetFieldsByInput($saved['city']['region']);
					if(!$deliveryservice->SetFieldsByInput($saved['city']['name'], $saved['city']['region'])){
						unset($deliverymethods_list[3]);
					}
					$deliveryservice->SetListByRegion($saved['city']['names_regions']);
					$deliveryservices_list = $deliveryservice->list;
					$delivery->SetFieldsByInput($saved['city']['shipping_comp'], $saved['city']['name'], $saved['city']['region']);
					$deliverydepartments_list = $delivery->list;
				}

				/* output data */
				$tpl->Assign('customer', $customer);
				$tpl->Assign('regions_list', $regions_list);
				$tpl->Assign('deliverymethods_list', $deliverymethods_list);
				$tpl->Assign('cities_list', $cities_list);
				$tpl->Assign('deliveryservices_list', $deliveryservices_list);
				$tpl->Assign('deliverydepartments_list', $deliverydepartments_list);
				$tpl->Assign('managers_list', $managers_list);
				$tpl->Assign('saved', $saved);
				$tpl->Assign('personal_discount', isset($_SESSION['cart']) && isset($_SESSION['cart']['personal_discount'])?$_SESSION['cart']['personal_discount']:1);

				/* Дествия */
				if(isset($GLOBALS['Rewrite']) && is_numeric($GLOBALS['Rewrite'])){
					if(isset($_POST['add_order'])){
						// Добавить к корзине товары из заказа
						$cart->FillByOrderId($GLOBALS['Rewrite'], true);
					}else{
						// заменить корзину товарами из заказа
						$cart->FillByOrderId($GLOBALS['Rewrite']);
					}
					if($User->fields['gid'] == _ACL_CONTRAGENT_){
						$customers->updateContPerson($_POST['cont_person']);
						$customers->updatePhones($_POST['phones']);
						$customers->updateCity($_POST['id_city']);
						$customers->updateDelivery($_POST['id_delivery']);
						if($_POST['bonus_card'] != '') {
							$_SESSION['member']['bonus_card'] = $_POST['bonus_card'];
						}else{
							unset($_SESSION['member']['bonus_card']);
						}
					}
					unset($_POST);
					header('Location: '._base_url.'/cart/');
					exit();
				}elseif(isset($GLOBALS['Rewrite']) && $GLOBALS['Rewrite'] == 'success'){
					$products = new Products();
					foreach($_SESSION['cart']['products'] as $id=>$p){
						$products->SetFieldsById($id);
						$product = $products->fields;
						$_SESSION['cart']['products'][$id]['name'] = $product['name'];
						$_SESSION['cart']['products'][$id]['art'] = $product['art'];
						$_SESSION['cart']['products'][$id]['id_category'] = $product['id_category'];
					}
					$tpl->Assign('cart', $_SESSION['cart']);
					$cart->ClearCart();
				}elseif(isset($GLOBALS['Rewrite']) && $GLOBALS['Rewrite'] == 'clear'){
					$cart->ClearCart();
					header('Location: '._base_url.'/cart/');
					exit();
				}

				/* проверка на ошибки */
				$errm = $warnings = array();
				$err = $warn = 0;
				// $cart->IsActualPrices($err, $warn, $errm, $warnings);
				if($err){
					if(isset($_SESSION['errm'])){
						$_SESSION['errm'] = array_merge($_SESSION['errm'], $errm);
					}else{
						$_SESSION['errm'] = $errm;
					}
				}
				unset($_SESSION['warnings']);
				if($warn){
					$_SESSION['warnings'] = $warnings;
				}

				/* collect cart information */
				$cart->RecalcCart();

				/* fill product list */
				if(!empty($_SESSION['cart']['products'])){
					$products = new Products();
					$arr = array();
					foreach($_SESSION['cart']['products'] as $id=>$p){
						$arr[] = $id;
					}
					$products->SetProductsListFromArr($arr, '');
					$list = $products->list;
					foreach($list as $key => &$value){
						if(isset($_SESSION['errm']['products'][$value['id_product']])){
							$value['err'] = 1;
							$value['errm'] = $_SESSION['errm']['products'][$value['id_product']];
						}else{
							$value['err'] = 0;
						}
						$errflag[$key] = $value['err'];
						$value['images'] = $products->GetPhotoById($value['id_product']);
					}
					//array_multisort($list, SORT_DESC, $errflag);
					$tpl->Assign('list', $list);
				}else{
					$tpl->Assign('list', false);
				}
				/* fill unavailable_product list */
				if(!empty($_SESSION['cart']['unavailable_products'])){
					$products = new Products();
					$arr = array();
					foreach($_SESSION['cart']['unavailable_products'] as $p){
						$products->SetFieldsById($p['id_product'], 1);
						$unlist[] = $products->fields;
					}
					$tpl->Assign('unlist', $unlist);
				}else{
					$tpl->Assign('unlist', false);
				}
				if(isset($_SESSION['cart']['id_customer'])){
					$customer_order = $customers->SetFieldsById($_SESSION['cart']['id_customer'], 1, true);
					$customer_order['last_order'] = $order->GetLastOrder($_SESSION['cart']['id_customer']);
					$tpl->Assign('customer_order', $customer_order);
				}
				$tpl->Assign('promo_info', 'Информация о введенном промокоде'); //Временный текст
				$tpl->Assign('msg', array('type' => 'info', 'text' => 'Если у Вас уже есть аккаунт на нашем сайте, воспользуйтесь <a href="#" class="btn_js" data-name="auth">формой входа</a>'));
				if(isset($success)){
					// $tpl->Assign('msg', "Заказ успешно сформирован.");
					$_SESSION['cart']['draft'] = $draft;
					$_SESSION['cart']['id_order'] = $id_order;
					$tpl->Assign('cart', $_SESSION['cart']);
					if($draft == 1){
						header('Location: '._base_url.'/cart/success/?type=draft');
					}else{
						header('Location: '._base_url.'/cart/success/?type=order');
					}
				}else{
					if(isset($GLOBALS['REQAR'][1]) && $GLOBALS['REQAR'][1] == 'makeorder' && $_SESSION['client']['user_agent'] == 'mobile'){
						$tpl->Assign('header', 'Оформление заказа');
						echo $tpl->Parse($GLOBALS['PATH_tpl'].'cp_cart_makeorder.tpl');
					}else{
						// Настройка панели действий ===============================
						$list_controls = array('layout');
						$tpl->Assign('list_controls', $list_controls);
						echo $tpl->Parse($GLOBALS['PATH_tpl'].'cp_cart.tpl');
					}
				}
				exit();
				break;
			case 'remove_from_cart':
				if(isset($_POST['id_prod_for_remove'])){
					$res = $cart->DBCart();
					if(count($_SESSION['cart']['products']) == 0){
						$cart->ClearCart(isset($_SESSION['cart']['id'])?$_SESSION['cart']['id']:null);
					}
					//$res = $cart->RemoveFromCart($_POST['id'], isset($_SESSION['cart']['id'])?$_SESSION['cart']['id']:false);
				}else{
					$res = null;
				}
				echo json_encode($res);
				break;
			// case 'update_qty':
				//				if(isset($_POST['opt']) && isset($_POST['id_product'])){
				//					$note_opt = isset($_POST['opt_note'])?$_POST['opt_note']:"";
				//					$note_mopt = isset($_POST['mopt_note'])?$_POST['mopt_note']:"";
				//					if(isset($_SESSION['member']['promo_code']) && $_SESSION['member']['promo_code'] != ''){
				//						if(checkNumeric($_POST, array('id_product', 'opt', 'order_mopt_qty', 'order_mopt_sum'))){
				//							$cart->UpdatePromoProduct($_POST['id_product'], $_POST['opt'], null, $_POST['order_mopt_qty'], $_POST['order_mopt_sum'], $note_opt, $note_mopt, null, null, isset($_POST['mopt_basic_price'])?$_POST['mopt_basic_price']:null);
				//						}else{
				//							exit();
				//						}
				//					}else{
				//						if($_POST['opt'] == 1){
				//							if(checkNumeric($_POST, array('id_product', 'opt', 'order_box_qty', 'order_opt_qty', 'order_opt_sum'))){
				//								$cart->UpdateProduct($_POST['id_product'], $_POST['opt'], $_POST['order_box_qty'], $_POST['order_opt_qty'], $_POST['order_opt_sum'], $note_opt, $note_mopt, null, $_POST['opt_correction'], $_POST['opt_basic_price']);
				//							}else{
				//								exit();
				//							}
				//						}else{
				//							if(checkNumeric($_POST, array('id_product', 'opt', 'order_mopt_qty', 'order_mopt_sum'))){
				//								$cart->UpdateProduct($_POST['id_product'], $_POST['opt'], null, $_POST['order_mopt_qty'], $_POST['order_mopt_sum'], $note_opt, $note_mopt, null, $_POST['mopt_correction'], $_POST['mopt_basic_price']);
				//							}else{
				//								exit();
				//							}
				//						}
				//					}
				//					$cart->SetTotalQty();
				//					$cart->SetAllSums();
				//
				//
				//					//	ob_start();
				//					//	print_r($_SESSION['Cart']);
				//					//	print_r($_POST);
				//					//	$t = ob_POST_clean();
				//					//	G::LogerE($t, "ajax.html", "w");
				//					$arr = array();
				//					$arr['id_product'] = $_POST["id_product"];
				//					$arr['error'] = false;
				//					$arr['opt'] = $_POST['opt'];
				//					$arr['sum'] = $_SESSION['Cart']['sum'];
				//					/***********************************************************/
				//					isset($note_opt)	?	$arr['note_opt'] = $note_opt	:	null;
				//					isset($note_mopt)	?	$arr['note_mopt'] = $note_mopt	:	null;
				//					if(isset($_SESSION['Cart']['sum'])){
				//						$cart->SetPersonalDiscount($personal_discount);
				//						$cart->SetSumDiscount();
				//						$cart->SetAllSums();
				//						$arr['sum_discount'] = $_SESSION['Cart']['sum_discount'];
				//					}
				//					/***********************************************************/
				//					$arr['string'] = $cart->GetString();
				//					$arr['total_quantity'] = $_SESSION['Cart']['prod_qty'];
				//					/***********************************************************/
				//					$arr['order_opt_sum'] = round($_SESSION['Cart']['order_opt_sum_default'], 2);
				//					$arr['order_mopt_sum'] = round($_SESSION['Cart']['order_mopt_sum_default'], 2);
				//					$arr['order_sum'] = $_SESSION['Cart']['order_sum'];
				//					/***********************************************************/
				//					$txt = json_encode($arr);
				//					echo $txt;
				//					exit();
				//				};
				//				break;
			case 'updateCartQty':
				if(isset($_POST['note'])){
					$_SESSION['cart']['products'][$_POST['id_product']]['note'] = $_POST['note'];
					$res = 'ok';
				}
				if(isset($_POST['quantity'])){
					$res = $cart->UpdateCartQty($_POST);
				}
				$cart->DBCart();
				echo json_encode($res);
				break;
			case 'GetCart':
				echo json_encode($_SESSION['cart']);
				break;
			case 'update_note':
				if(isset($_SESSION['cart']['products'][$_POST['id_product']]) && !empty($_SESSION['cart']['products'][$_POST['id_product']])){
					$_SESSION['cart']['products'][$_POST['id_product']]['note'] = $_POST['note'];
					$txt = 'ok';
				}else{
					$txt = 'not';
				}
				echo json_encode($txt);
				exit();
				break;
			case 'clearCart':
				// print_r($_SESSION['cart']['id']);
				$res = $cart->ClearCart(isset($_SESSION['cart']['id'])?$_SESSION['cart']['id']:null);
				echo json_encode($res);
				break;
			case 'getCustomerInfo':
				if(isset($_POST['phone'])){
					$phone = preg_replace('/[^\d]+/', '', $_POST['phone']);
					$Users = new Users();
					$id_user = $Users->CheckPhoneUniqueness($phone, false);
					if($id_user === true){
						$res = '<div class="no_results_info">
									<p>По данному номеру телефона '.$phone.' не найдено пользователей.</p>
									<p>Вы можете создать нового пользователя с таким номером.</p>
							   </div>';
					}else{
						$customer = new Customers();
						$order = new Orders();
						$customer_data = $customer->SetFieldsById($id_user, 1, true);
						$customer_data['last_order'] = $order->GetLastOrder($id_user);						
						$res = '<div class="customer_main_info">
									<input type="hidden" value="'.$id_user.'">
									<p><span>ФИО:</span> '.(!empty($customer_data['first_name']) || !empty($customer_data['last_name']) || !empty($customer_data['middle_name']) ?$customer_data['last_name'].' '.$customer_data['first_name'].' '.$customer_data['middle_name']:(!empty($customer_data['name'])?$customer_data['name']:null)).'</p>
									<p><span>email:</span> '.($customer_data['email']?$customer_data['email']:' --').'</p>
									<p><span>Баланс:</span> '.($customer_data['balance']?$customer_data['balance']:' 0,00').' грн.</p>
									<p><span>Последний заказ:</span> '.($customer_data['last_order']?$customer_data['last_order']:' --').'</p>
									<p><span>Активность:</span> '.($customer_data['active'] ==1?'Да':'Нет').'</p>
								</div>
								<div class="bonus_block">';
						if(!empty($customer_data['bonus_card'])){
							$res .= '<p><span>Бонусная карта:</span> №'.(!empty($customer_data['bonus_card'])?$customer_data['bonus_card']:' --').'</p>
									<p><span>Бонусный баланс:</span> '.(!empty($customer_data['bonus_balance'])?$customer_data['bonus_balance'].' грн.':' --').'</p>
									<p><span>Бонусный процент:</span> '.(!empty($customer_data['bonus_discount'])?$customer_data['bonus_discount'].' %':' --').'</p>';
						}else{
							$res .= 'Бонусная карта не активирована.';
						}
						$res .=	'</div>';
					}
				}else {
					$res = 'Номер телефона не введен.';
				}
				echo $res;
				break;
			case 'createCustomer':
				// создаем нового пользователя
				$Customers = new Customers();
				$Users = new Users();
				$data = array(
					'last_name' => isset($_POST['last_name'])?$_POST['last_name']:null,
					'first_name' => isset($_POST['first_name'])?$_POST['first_name']:null,
					'middle_name' => isset($_POST['middle_name'])?$_POST['middle_name']:null,
					'name' => (!empty($_POST['last_name']))?$_POST['last_name'].' '.$_POST['first_name'].' '.$_POST['middle_name']:'user_'.rand(),
					'passwd' => $pass = G::GenerateVerificationCode(6),
					'descr' => 'Пользователь создан менеджером при оформлении корзины',
					'phone' => $_POST['phone'],
					'id_contragent' => $_SESSION['member']['id_user']
				);
				// регистрируем нового пользователя
				if($id_customer = $Customers->RegisterCustomer($data)){
					$Users = new Users();
					$Users->SendPassword($data['passwd'], $data['phone']);
					$_SESSION['cart']['id_customer'] = $id_customer;
					$res['message'] = 'успех';
					$res['status'] = 1;
				}else {
					$res['message'] = 'Произошла ошибка, повторите попытку.';
					$res['status'] = 2;
				}
				echo json_encode($res);
				break;
			case 'bindingCustomerOrder':
				$Customers = new Customers();
				$_SESSION['cart']['id_customer'] = $_POST['id_customer'];
				if($Customers->SetSessionCustomerBonusCart($_POST['id_customer'])){
					$res['message'] = 'успех';
					$res['status'] = 1;
				}else{
					$res['message'] = 'Произошла ошибка, повторите попытку.';
					$res['status'] = 2;
				}
				echo json_encode($res);
				break;
			case 'settCustomerForOrder':
				if(isset($_POST['step']) && isset($_POST['date'])){
					switch($_POST['step']){
						case 'add_and_set':
							// создаем нового пользователя
							$Customers = new Customers();
							$data = array(
								'name' => 'user_'.rand(),
								'passwd' => $pass = G::GenerateVerificationCode(6),
								'descr' => 'Пользователь создан автоматически при оформлении корзины',
								'phone' => $_POST['date']
							);
							// регистрируем нового пользователя
							$id_customer = $Customers->RegisterCustomer($data);
							if(isset($id_customer)){
								$Users = new Users();
								$Users->SendPassword($data['passwd'], $data['phone']);
								$_SESSION['cart']['id_customer'] = $id_customer;
								$res['message'] = 'успех';
								$res['status'] = 1;
							}else {
								$res['message'] = 'Произошла ошибка, повторите попытку.';
								$res['status'] = 2;
							}
							break;
						case 'set':
							$_SESSION['cart']['id_customer'] = $_POST['date'];
							$res['message'] = 'успех';
							$res['status'] = 1;
							break;
					}
				}else{
					$res['message'] = 'Что-то не так. Данные отсутствуют.';
					$res['status'] = 3;
				}
				echo json_encode($res);
				break;
			case 'makeOrder':
				$_SESSION['cart']['id_contragent'] = isset($_POST['id_contragent']) && !empty($_POST['id_contragent'])?$_POST['id_contragent']:null;
				if(!G::IsLogged()){
					$Customers = new Customers();
					$Users = new Users();
					// Если покупатель не авторизован, получаем введенный номер телефона
					$phone = preg_replace('/[^\d]+/', '', $_POST['phone']);
					// проверяем уникальность введенного номера телефона
					$unique_phone = $Users->CheckPhoneUniqueness($phone);
					//print_r($unique_phone); die();
					if($unique_phone === true){
						$data = array(
							'name' => 'user_'.rand(),
							//'passwd' => $pass = substr(md5(time()), 0, 8),
							'passwd' => $pass = G::GenerateVerificationCode(6),
							'descr' => 'Пользователь создан автоматически при оформлении корзины',
							'phone' => $phone,
							'id_contragent' => isset($_POST['id_contragent']) && !empty($_POST['id_contragent'])?$_POST['id_contragent']:null
						);

						// регистрируем нового пользователя
						if($Customers->RegisterCustomer($data)){
							$Users->SendPassword($data['passwd'], $data['phone']);
						}
						$data = array(
							'email' => $phone,
							'passwd' => $pass
						);
						// авторизуем покупателя в его новый аккаунт
						if($Users->CheckUser($data)){
							G::Login($Users->fields);
							_acl::load($Users->fields['gid']);
							$res['new_user'] = true;
						}
					}else{
						$res['message'] = 'Пользователь с таким номером телефона уже зарегистрирован!';
						$res['status'] = 501;
					}
				}
				if(G::IsLogged()){
					// Если покупатель арторизован, получаем его данные
					$Orders = new Orders();
					// оформляем заказ
					if($id_order = $Orders->Add()){
						$cart = new Cart();
						$cart->clearCart(isset($_SESSION['cart']['id'])?$_SESSION['cart']['id']:null);
						$res['message'] = 'Заказ сформирован!';
						$res['status'] = 200;
						$_SESSION['member']['last_order'] = $id_order;
						// $Customers->updatePhones($phone);
					}else{
						$res['message'] = 'Ошибка формирования заказа!';
						$res['status'] = 500;
						// $Customers->updatePhones($phone);
					}
				}else{
					$res['message'] = 'Пользователь с таким номером телефона уже зарегистрирован!';
					$res['status'] = 501;
				}
				echo json_encode($res);
				break;
			case 'update_info':
				$customers = new Customers();
				$customers->updateInfoPerson($_POST);

				return json_encode(true);
				break;
			case 'CreateJointOrder':
				if(!$res['promo'] = $cart->CreatePromo('JO')){
					$res['promo'] = 'Ошибка формирования совместного заказа.';
				};
				echo json_encode($res['promo']);
				break;
			case 'CheckPromo':
				if(!$cart->CheckPromo($_POST['promo'])){
					$res['promo'] = false;
					$res['msg'] = 'Ошибка! Такого промокода не существует. Проверьте правильность ввода.';
				} else{
					$res['promo'] = true;
				}
				echo json_encode($res);
				break;
			case 'ReadyUserJO':
				if(!$cart->UpdateCart(false, false, false, 1, $_POST['id_cart'])){
					echo json_encode('no');
				};
				echo json_encode('ok');
				break;
			case 'DeletePromo':
				$echo = true;
				if(!$cart->UpdateCart(null, 0, 1, 0, $_POST['id_cart'])){
					$echo = false;
				}
				echo json_encode($echo);
				break;
			case 'SaveOrderNote':
				$echo = true;
				if(!$cart->UpdateCartNote($_POST['note'])){
					$echo = false;
				}
				echo json_encode($echo);
				break;
			default:
				break;
		}
	}
}
exit();
