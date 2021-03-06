<?php
class Customers extends Users {

	private $usual_fields;
	private $db_table = _DB_PREFIX_.'customer';
	private $db_fields;

	public function __construct(){
		parent::__construct();
		$this->usual_fields = [
			'id_user',
			'cont_person',
			'phones',
			'discount',
			'id_contragent',
			'id_city',
			'id_delivery',
			'bonus_card',
			'bonus_balance',
			'bonus_discount',
			'balance',
			'sex',
			'birthday',
			'address_ur',
			'b_year',
			'b_month',
			'b_day',
			'first_name',
			'middle_name',
			'last_name'
		];
		$this->db_fields = [
			'id_user',
			'address_ur',
			'last_name',
			'first_name',
			'middle_name',
			'cont_person',
			'phones',
			'discount',
			'id_contragent',
			'id_region',
			'id_city',
			'id_delivery',
			'bonus_card',
			'sex',
			'learned_from',
			'birthday',
			'buy_volume',
			'bonus_discount',
			'bonus_balance',
			'balance',
			'b_day',
			'b_month',
			'b_year',
		];
	}

	// Создание нового клиента
	public function Create($data){
		if(!isset($data['id_user'])){
			
			if(!$id_user = parent::Create($data)){
				return false;
			}
			$data['id_user'] = $id_user;
		}
		foreach($this->db_fields as $field){
			switch ($field) {
				case 'id_contragent':
					//рандомный выбор контрагента если он не был выбран
					if(!isset($data[$field]) || empty($data[$field])){
						global $Contragents;
						$Contragents->SetList();
						$f[$field] = $Contragents->list[array_rand($Contragents->list)]['id_user'];
					}else{
						$f[$field] = $data[$field];
					}
					break;
				default:
					if(isset($data[$field]) && $data[$field]){
						$f[$field] = $data[$field];
					}
					break;
			}
		}
		$this->db->StartTrans();
		if(!$this->db->Insert($this->db_table, $f)){
			$this->db->FailTrans();
			return false;
		}
		$this->db->CompleteTrans();
		return $id_user;
	}

	// Получаем данные о клиенте
	public function Read($id_user){
		if(!$res = parent::Read($id_user)){
			return false;
		}
		$sql = 'SELECT *
			FROM '.$this->db_table.'
			WHERE id_user = '.$id_user;
		return array_merge($res, (array) $this->db->GetOneRowArray($sql));
	}

	// Изменение данных клиента
	public function Update($data){
		if(!parent::Update($data)){
			return false;
		}
		var_dump('test');
		if(!$this->Read($data['id_user'])){
			var_dump('test2');
			if(!$this->Create($data)){
				var_dump('test3');
				return false;
			}
			var_dump('test4');
			return true;
		}
		var_dump('test5');
		foreach($this->db_fields as $field){
			switch ($field) {
				default:
					if(isset($data[$field]) && $data[$field]){
						$f[$field] = $data[$field];
					}
					break;
			}
		}
		$this->db->StartTrans();
		if(!$this->db->Update($this->db_table, $f, "id_user = ".$f['id_user'])){
			$this->db->FailTrans();
			return false;
		}
		$this->db->CompleteTrans();
		return true;
	}

	// Удаление клиента
	public function Delete($id_user){
		if(!parent::Delete($id_user)){
			return false;
		}
		/*
			Дополнительные процедуры очистки,например - очистка ассортимента
		*/
		return true;
	}

	// Покупатель по id
	public function SetFieldsById($id, $all = 0, $all_data = false){
		parent::SetFieldsById($id, $all);
		$user_fields = $this->fields;
		$sql = 'SELECT *
			FROM  '._DB_PREFIX_.'customer
			WHERE id_user = '.$this->db->Quote($id);
		if(!$this->fields = $this->db->GetOneRowArray($sql)){
			return false;
		}
		//Для вывода email и активности покупателя в корзине у менеджера
		if($all_data){
			$this->fields['active'] = $user_fields['active'];
			$this->fields['email'] = $user_fields['email'];
			$this->fields['phone'] = $user_fields['phone'];
			$this->fields['name'] = $user_fields['name'];
		}
		return $this->fields;
	}

	public function SetList($email_klient){
		$sql = "SELECT u.name, u.id_user, cu.cont_person, cu.phones
			FROM "._DB_PREFIX_."user u, "._DB_PREFIX_."customer cu
			WHERE email  LIKE \"%$email_klient%\"
			AND cu.id_user = u.id_user
			ORDER BY name";
		$kl=$this->db->GetArray($sql);
		return $kl;
	}

	//Получение списка избранных товаров
	public function GetListFavorites($id_user){
		$sql = "SELECT f.id_product, p.art, p.name,p.translit , p.img_1,
			p.price_opt, p.price_mopt
			FROM "._DB_PREFIX_."favorites AS f
			LEFT JOIN "._DB_PREFIX_."product AS p
				ON f.id_product = p.id_product
			WHERE f.id_user = '".$id_user."'
			ORDER BY f.id_product";
		if(!$arr = $this->db->GetArray($sql)){
			return false;
		}
		foreach($arr as &$value){
			$Products = new Products();
			$value['images'] = $Products->GetPhotoById($value['id_product']);
		}
		return $arr;
	}

	//Добавление Избранного товара
	public function AddFavorite($id_user, $id_product){
		$f['id_user'] = $id_user;
		$f['id_product'] = $id_product;
		$this->db->StartTrans();
		if(!$this->db->Insert(_DB_PREFIX_.'favorites', $f)){
			$this->db->FailTrans();
			G::DieLoger("SQL error - $sql");
			return false;
		}
		$this->db->CompleteTrans();
		return true;
	}

	//Удаление Избранного товара
	public function DelFavorite($id_user, $id_product){
		$sql = "DELETE FROM "._DB_PREFIX_."favorites
			WHERE id_user = '".$id_user."'
			AND id_product = '".$id_product."'";
		$this->db->StartTrans();
		if(!$this->db->Query($sql)){
			$this->db->FailTrans();
			G::DieLoger("SQL error - $sql");
			return false;
		}
		$this->db->CompleteTrans();
		return true;
	}
	//Получение списка ожидания
	public function GetWaitingList($id_user){
		$sql = "SELECT wl.id_product, p.art, p.name, p.translit , p.img_1,
			p.price_opt, p.price_mopt, p.old_price_opt, p.visible
			FROM "._DB_PREFIX_."waiting_list AS wl
			LEFT JOIN "._DB_PREFIX_."product AS p
				ON wl.id_product = p.id_product
			WHERE wl.id_user = '".$id_user."'
			ORDER BY wl.id_product";
		if(!$arr = $this->db->GetArray($sql)){
			return false;
		}
		foreach($arr as &$value){
			$Products = new Products();
			$value['images'] = $Products->GetPhotoById($value['id_product']);
		}
		return $arr;
	}

	//Добавление в список ожидания
	public function AddInWaitingList($id_user, $id_product){
		$f['id_user'] = $id_user;
		$f['id_product'] = $id_product;
		$this->db->StartTrans();
		if(!$this->db->Insert(_DB_PREFIX_.'waiting_list', $f)){
			$this->db->FailTrans();
			G::DieLoger("SQL error - $sql");
			return false;
		}
		$this->db->CompleteTrans();
		return true;
	}

	//Удаление из списка ожидания
	public function DelFromWaitingList($id_user, $id_product){
		$sql = "DELETE FROM "._DB_PREFIX_."waiting_list
			WHERE id_user = '".$id_user."'
			AND id_product = '".$id_product."'";
		$this->db->StartTrans();
		if(!$this->db->Query($sql)){
			$this->db->FailTrans();
			G::DieLoger("SQL error - $sql");
			return false;
		}
		$this->db->CompleteTrans();
		return true;
	}

	public function GetCustomersByContragent($id_contragent){
		$sql = "SELECT u.name, u.id_user, cu.cont_person, cu.phones
			FROM "._DB_PREFIX_."user u, "._DB_PREFIX_."customer cu
			WHERE cu.id_contragent = \"$id_contragent\"
			AND cu.id_user = u.id_user
			ORDER BY cont_person";
		$clients=$this->db->GetArray($sql);
		return $clients;
	}

	public function Klient($id_klient){
		$sql = "SELECT name
			FROM "._DB_PREFIX_."user
			WHERE id_user= \"$id_klient\"
			ORDER BY name";
		$kl1=$this->db->GetArray($sql);
		return $kl1;
	}

	public function updateContragentOnRegistration($contragent, $id_customer){
		if(!empty($contragent) && !empty($id_customer)){
			$f['id_contragent'] = trim($contragent);
			$f['id_user'] = trim($id_customer);
			if(!$this->db->Update(_DB_PREFIX_.'customer', $f, "id_user = ".$f['id_user'])){
				echo $this->db->ErrorMsg();
				$this->db->FailTrans();
				return false; //Если не удалось записать в базу
			}
			$this->db->CompleteTrans();
			return true;//Если все ок
		}else{
			return false; //Если имя пустое
		}
	}

	public function updateContragent($contragent){
		if(!empty($contragent)){
			$f['id_contragent'] = trim($contragent);
			$this->db->StartTrans();
			if(!$this->db->Update(_DB_PREFIX_.'customer', $f, "id_user = ".$_SESSION['member']['id_user'])){
				echo $this->db->ErrorMsg();
				$this->db->FailTrans();
				return false; //Если не удалось записать в базу
			}
			$this->db->CompleteTrans();
			return true;//Если все ок
		}else{
			return false; //Если имя пустое
		}
	}

	public function registerBonus($data){
		if(!$data['bonus_card'] || empty($data['bonus_card'])){
			return false; //Если номер карты пуст
		}
		$f['bonus_card'] = $data['bonus_card'];
		$f['bonus_balance'] = 20;
		$f['bonus_discount'] = 1;
		if(isset($data['sex'])){
			$f['sex'] = $data['sex'];
		}
		if(isset($data['learned_from'])){
			$f['learned_from'] = $data['learned_from'];
		}
		if(isset($arr['byear'])){
			$f['b_year'] = trim($arr['byear']);
		}
		if(isset($arr['bmonth'])){
			$f['b_month'] = trim($arr['bmonth']);
		}
		if(isset($arr['bday'])){
			$f['b_day'] = trim($arr['bday']);
		}
		if(isset($data['buy_volume'])){
			$f['buy_volume'] = $data['buy_volume'];
		}
		$this->db->StartTrans();
		if(!$this->db->Update(_DB_PREFIX_.'customer', $f, "id_user = ".$_SESSION['member']['id_user'])){
			echo $this->db->ErrorMsg();
			$this->db->FailTrans();
			return false; //Если не удалось записать в базу
		}
		$this->db->CompleteTrans();
		$this->SetSessionCustomerBonusCart($_SESSION['member']['id_user']);
		return true;//Если все ок
	}
	public function updateBonus($data){
		if(!$data['bonus_card'] || empty($data['bonus_card'])){
			return false; //Если номер карты пуст
		}
		$f['bonus_card'] = $data['bonus_card'];
		$this->db->StartTrans();
		if(!$this->db->Update(_DB_PREFIX_.'customer', $f, "id_user = ".$_SESSION['member']['id_user'])){
			echo $this->db->ErrorMsg();
			$this->db->FailTrans();
			return false; //Если не удалось записать в базу
		}
		$this->db->CompleteTrans();
		$this->SetSessionCustomerBonusCart($_SESSION['member']['id_user']);
		return true;//Если все ок
	}

	public function updateCity($city){
		if(!empty($city)){
			$f['id_city'] = trim($city);
			$this->db->StartTrans();
			if(!$this->db->Update(_DB_PREFIX_.'customer', $f, "id_user = ".$_SESSION['member']['id_user'])){
				echo $this->db->ErrorMsg();
				$this->db->FailTrans();
				return false; //Если не удалось записать в базу
			}
			$this->db->CompleteTrans();
			return true;//Если все ок
		}else{
			return false; //Если имя пустое
		}
	}

	public function updateDelivery($delivery){
		if(!empty($delivery)){
			$f['id_delivery'] = trim($delivery);
			$this->db->StartTrans();
			if(!$this->db->Update(_DB_PREFIX_.'customer', $f, "id_user = ".$_SESSION['member']['id_user'])){
				echo $this->db->ErrorMsg();
				$this->db->FailTrans();
				return false; //Если не удалось записать в базу
			}
			$this->db->CompleteTrans();
			return true;//Если все ок
		}else{
			return false; //Если имя пустое
		}
	}
	/* Регистрация пользователя
	 *
	 */
	public function RegisterCustomer($arr){
		//рандомный выбор контрагента
		if(!isset($arr['id_contragent']) || empty($arr['id_contragent'])){
			global $Contragents;
			$Contragents->SetList();
			$arr['id_contragent'] = $Contragents->list[array_rand($Contragents->list)]['id_user'];
		}
		return $this->AddCustomer($arr);
	}
	/* Добавление
	 *
	 */
	public function AddCustomer($arr){
		global $Users;
		$arr['gid'] = _ACL_CUSTOMER_;
		if(!$id_user = $Users->AddUser($arr)){
			return false;
		}
		$f['id_user'] = $id_user;
		$f['last_name'] = isset($arr['last_name'])?$arr['last_name']:null;
		$f['first_name'] = isset($arr['first_name'])?$arr['first_name']:null;
		$f['middle_name'] = isset($arr['middle_name'])?$arr['middle_name']:null;
		if(isset($arr['discount'])){
			$f['cont_person'] = trim($arr['cont_person']);
			$f['phones'] = trim($arr['phones']);
			$f['id_city'] = trim($arr['id_city']);
			$f['id_delivery'] = trim($arr['id_delivery']);
		}else{
			$f['phones'] = isset($arr['phone'])?trim($arr['phone']):'';
			$f['id_city'] = 0;
			$f['id_delivery'] = 0;
		}
		$f['id_contragent'] = $arr['id_contragent'];
		if(isset($arr['discount'])){
			$f['discount'] = trim($arr['discount']);
		}
		$this->db->StartTrans();
		if(!$this->db->Insert(_DB_PREFIX_.'customer', $f)){
			$this->db->FailTrans();
			return false;
		}
		$this->db->CompleteTrans();
		return $id_user;
	}

	public function AddContragentCustomer($arr){
		$and['email'] = $arr['email'];
		global $Users;
		$Users->UsersList(1, $and);
		$id_user = $Users->list[0]['id_user'];
		$arr['cont_person'] = $arr['phones'] = "";
		$arr['id_contragent'] = $arr['id_city'] = $arr['id_delivery'] = 0;
		$f['id_user'] = $id_user;
		if(isset($arr['discount']))
			$f['cont_person'] = trim($arr['cont_person']);
			$f['phones'] = trim($arr['phone']);
			$f['id_contragent'] = trim($arr['id_contragent']);
			$f['id_city'] = trim($arr['id_city']);
			$f['id_delivery'] = trim($arr['id_delivery']);

		if(isset($arr['discount'])){
			$f['discount'] = trim($arr['discount']);
		}
		$this->db->StartTrans();
		if(!$this->db->Insert(_DB_PREFIX_.'customer', $f)){
			echo $this->db->ErrorMsg();
			$this->db->FailTrans();
			return false;
		}
		$this->db->CompleteTrans();
		return true;
	}
	/* Обновление пользователя
	 *
	 */
	public function UpdateCustomer($arr){
		// global $Users;
		// $arr['gid'] = $Users->fields['gid'];
		// $arr['name'] = $arr['cont_person'];
		// $arr['phones'] = $arr['phone'];
		// if(!$Users->UpdateUser($arr)){
		// 	$this->db->FailTrans();
		// 	return false;
		// }
		// $id_user = $this->db->GetLastId();
		// unset($f);
		$f['id_user'] = isset($arr['id_user'])?$arr['id_user']:$_SESSION['member']['id_user'];
		if(isset($arr['cont_person'])){
			$f['cont_person'] = trim($arr['cont_person']);
		}
		if(isset($arr['first_name'])){
			$f['first_name'] = trim($arr['first_name']);
		}
		if(isset($arr['middle_name'])){
			$f['middle_name'] = trim($arr['middle_name']);
		}
		if(isset($arr['last_name'])){
			$f['last_name'] = trim($arr['last_name']);
		}
		if(isset($arr['gender'])){
			$f['sex'] = trim($arr['gender']);
		}
		if(isset($arr['year'])){
			$f['b_year'] = trim($arr['year']);
		}
		if(isset($arr['month'])){
			$f['b_month'] = trim($arr['month']);
		}
		if(isset($arr['day'])){
			$f['b_day'] = trim($arr['day']);
		}
		if(isset($arr['id_region'])){
			$f['id_region'] = trim($arr['id_region']);
		}
		if(isset($arr['id_city'])){
			$f['id_city'] = trim($arr['id_city']);
		}
		if(isset($arr['address'])){
			$f['address_ur'] = trim($arr['address']);
		}
		if(isset($arr['phone'])){
			$f['phones'] = trim($arr['phone']);
		}
		if(isset($arr['discount'])){
			$discount = str_replace(",",".",trim($arr['discount']));
			$f['discount'] = (1-$discount)*100;
		}
		if(isset($arr['id_contragent'])){
			$f['id_contragent'] = trim($arr['id_contragent']);
		}
		$this->db->StartTrans();
		if(!$this->db->Update(_DB_PREFIX_.'customer', $f, "id_user = ".$f['id_user'])){
			echo $this->db->ErrorMsg();
			$this->db->FailTrans();
			return false;
		}
		$this->db->CompleteTrans();
		return true;
	}
	// Удаление
	public function DelCustomer($id){
		$sql = "DELETE FROM "._DB_PREFIX_."customer WHERE `id_user` =  $id";
		$this->db->Query($sql) or G::DieLoger("<b>SQL Error - </b>$sql");
		$sql = "DELETE FROM "._DB_PREFIX_."user WHERE id_user=$id";
		$this->db->Query($sql) or G::DieLoger("<b>SQL Error - </b>$sql");
		return true;
	}

	public function GetOrders($order_by = false, $limit = '', $status = false){
		if(!$order_by) $order_by = 'o.target_date desc';
		$id_customer = $this->fields['id_user'];
		/*$sql = "SELECT o.target_date, o.id_order, o.skey, o.id_order_status, SUM(osp.opt_sum+osp.mopt_sum) AS sum,
				o.id_pretense_status, o.id_return_status, o.sum_discount, o.discount
				FROM "._DB_PREFIX_."order o, "._DB_PREFIX_."osp osp
				WHERE o.id_order = osp.id_order
				AND o.id_customer=$id_customer
				AND (o.id_order_status = 1 OR o.id_order_status = 3)
				GROUP BY id_order
				ORDER BY $order_by";*/
		$sql = "SELECT o.creation_date,o.target_date, o.id_order,
			o.id_order_status, o.note_customer, o.skey,
			SUM(osp.opt_sum+osp.mopt_sum) AS sum, o.id_pretense_status,
			o.id_return_status, o.note, o.sum_discount, o.discount,
			c.name_c as contragent, c.site as contragent_site, r.mark
			FROM "._DB_PREFIX_."order AS o
			LEFT JOIN "._DB_PREFIX_."osp AS osp
			ON o.id_order = osp.id_order
			LEFT JOIN "._DB_PREFIX_."user AS u
			ON o.id_customer = u.id_user
			LEFT JOIN "._DB_PREFIX_."contragent AS c
			ON o.id_contragent = c.id_user
			LEFT JOIN xt_rating r
			ON o.id_contragent = r.id_contragent
			AND r.id_author = '".$id_customer."'
			WHERE o.id_customer = '".$id_customer."'
			AND o.visibility = 1".$status."
			GROUP BY o.id_order
			ORDER BY ".$order_by.
			$limit;
		$arr = $this->db->GetArray($sql);

		//$date = time()+3600*24;
		//$date2 = time()-3600*24*60;//echo time()-3600*24*10;
		//$sql = "SELECT o.target_date, o.id_order, o.id_order_status, SUM(osp.opt_sum+osp.mopt_sum) AS sum,
		//	o.id_pretense_status, o.id_return_status, o.sum_discount, o.discount
		//	FROM "._DB_PREFIX_."order o, "._DB_PREFIX_."osp osp
		//	WHERE o.id_order = osp.id_order
		//	AND o.id_customer=$id_customer
		//	AND o.target_date>\"$date2\"
		//	/*AND o.target_date<\"$date\"*/
		//	AND o.id_order_status = 2
		//	GROUP BY id_order
		//	ORDER BY $order_by";
		/*$sql = "SELECT o.creation_date,o.target_date, o.id_order, o.id_order_status, o.skey, SUM(osp.opt_sum+osp.mopt_sum) AS sum,
			o.id_pretense_status, o.id_return_status, o.note,   o.sum_discount, o.discount, c.name_c as contragent, c.site as contragent_site
			FROM "._DB_PREFIX_."order o, "._DB_PREFIX_."osp osp, "._DB_PREFIX_."user u, "._DB_PREFIX_."contragent c
			WHERE o.id_order = osp.id_order
			AND o.target_date>\"$date2\"
			AND o.target_date<\"$date\"
			AND o.id_customer=$id_customer
			AND c.id_user = o.id_contragent
			AND o.id_order_status IN (2)
			AND o.id_customer = u.id_user
			GROUP BY id_order
			ORDER BY $order_by";*/
		//$arr2 = $this->db->GetArray($sql);
		//if ($order_by == "o.id_order_status asc")
		//	$arr = array_merge($arr2, $arr);
		//else
		//	$arr = array_merge($arr, $arr2);
		return $arr;

	}

	public function GetPromoOrders(){
		$id_customer = $this->fields['id_user'];
		$sql = "SELECT o.creation_date, o.target_date, o.id_order,
			o.id_order_status, o.note_customer, o.skey,
			SUM(osp.opt_sum+osp.mopt_sum) AS sum, COUNT(osp.id_product) AS qty, o.id_pretense_status,
			o.id_return_status, o.note, o.sum_discount, o.discount, o.descr
			FROM "._DB_PREFIX_."order AS o, "._DB_PREFIX_."osp AS osp, "._DB_PREFIX_."user u
			WHERE o.id_order = osp.id_order
			AND o.id_customer = $id_customer
			AND o.id_customer = u.id_user
			AND o.visibility = 1
			GROUP BY o.id_order
			ORDER BY o.creation_date DESC";
		$arr = $this->db->GetArray($sql);
		return $arr;
	}

	public function GetOrders_diler($order_by='o.creation_date desc'){
		$id_customer = $_SESSION['member']['id_user'];
		$sql = "SELECT o.creation_date, o.target_date, o.id_klient,
			o.id_order, o.id_order_status, o.skey, o.sum_discount,
			SUM(osp.opt_sum+osp.mopt_sum) AS sum, o.discount,
			o.id_return_status, o.note, o.note2, o.id_pretense_status,
			c.name_c as contragent, c.site as contragent_site,
			(SELECT name
			FROM "._DB_PREFIX_."user u, "._DB_PREFIX_."order o
			WHERE o.id_klient = u.id_user
			AND osp.id_order = o.id_order) AS name_klient
			FROM "._DB_PREFIX_."order o, "._DB_PREFIX_."osp osp, "._DB_PREFIX_."user u, "._DB_PREFIX_."contragent c
			WHERE o.id_order = osp.id_order
			AND o.id_customer = $id_customer
			AND c.id_user = o.id_contragent
			AND o.id_order_status <> 7
			AND o.id_customer = u.id_user
			GROUP BY id_order
			ORDER BY $order_by";
		$arr = $this->db->GetArray($sql);
		return $arr;
	}

	public function GetOrders_m_diler($order_by='o.target_date desc'){
		$date2 = time()-3600*24*60;
		$sql = "SELECT  o.target_date, o.id_klient,  o.id_order,
			o.id_order_status,  SUM(osp.opt_sum+osp.mopt_sum) AS sum,
			o.note, o.note2,  o.sum_discount, o.discount,
			(SELECT name
			FROM "._DB_PREFIX_."user u, "._DB_PREFIX_."order o
			WHERE u.id_user = o.id_klient
			AND osp.id_order = o.id_order ) AS name_klient,
			(SELECT email
			FROM "._DB_PREFIX_."user u, "._DB_PREFIX_."order o
			WHERE u.id_user = o.id_klient
			AND osp.id_order = o.id_order ) AS email_klient
			FROM "._DB_PREFIX_."order o, "._DB_PREFIX_."osp osp
			WHERE o.id_order = osp.id_order
			AND o.id_delivery= 2
			AND o.target_date>\"$date2\"
			AND o.id_order_status <> 3
			AND o.id_order_status <> 4
			AND o.id_order_status <> 5
			AND o.id_order_status <> 7
			GROUP BY id_order
			ORDER BY id_order desc";
		$arr = $this->db->GetArray($sql);
		return $arr;
	}

	public function GetOrdersTerminal($order_by = 'o.creation_date desc'){
		$id_customer = $_SESSION['member']['id_user'];
		// $id_customer = $GLOBALS['CONFIG']['demo_user'];
		$date = time()-3600*24*15;
		$sql = "SELECT o.creation_date, o.target_date,
			o.id_order, o.skey, o.id_order_status,
			SUM(osp.opt_sum+osp.mopt_sum) AS sum,
			o.sum_discount, c.name_c as contragent,
			o.discount, c.site as contragent_site,
			o.phones, o.descr, p.art
			FROM "._DB_PREFIX_."order AS o
			LEFT JOIN "._DB_PREFIX_."osp AS osp
				ON osp.id_order = o.id_order
			LEFT JOIN "._DB_PREFIX_."user AS u
				ON u.id_user = o.id_customer
			LEFT JOIN "._DB_PREFIX_."contragent AS c
				ON c.id_user = o.id_contragent
			LEFT JOIN "._DB_PREFIX_."product AS p
				ON p.id_product = osp.id_product
			WHERE o.id_customer = $id_customer
			AND o.creation_date > '".$date."'
			AND o.id_order_status IN (1,6)
			GROUP BY id_order
			ORDER BY $order_by";
		$arr = $this->db->GetArray($sql);
		if(!$arr){
			return false;
		}
		return $arr;
	}
	public function GetOrders_demo($order_by = 'o.creation_date desc'){
		$id_customer = $GLOBALS['CONFIG']['demo_user'];
		$date = time()+3600*24;
		$date2 = time()-3600*24*30;
		$sql = "SELECT o.creation_date, o.target_date, o.id_order,
			o.id_order_status, o.skey, c.site as contragent_site,
			o.sum_discount, o.discount, c.name_c as contragent,
			SUM(osp.opt_sum+osp.mopt_sum) AS sum
			FROM "._DB_PREFIX_."order AS o
			LEFT JOIN "._DB_PREFIX_."osp AS osp
				ON o.id_order = osp.id_order
			LEFT JOIN "._DB_PREFIX_."user AS u
				ON o.id_customer = u.id_user
			LEFT JOIN "._DB_PREFIX_."contragent AS c
				ON c.id_user = o.id_contragent
			WHERE o.creation_date > '".$date2."'
			AND o.id_order_status IN (1, 2, 4, 5, 6)
			GROUP BY o.id_order
			ORDER BY ".$order_by;
		$arr = $this->db->GetArray($sql);
		return $arr;
	}

	public function GetOrders_export($order_by){
		$date = $_POST['date3'];
		list($d,$m,$y) = explode(".", trim($date));
		$date = mktime(0, 0, 0, $m , $d, $y);
		$date = $date+3600*24*2;
		$sql = "SELECT o.target_date, o.creation_date, o.id_order, o.id_klient,
			o.id_order_status, o.skey, SUM(osp.opt_sum+osp.mopt_sum) AS sum,
			o.sum_discount, o.discount, c.name_c as contragent, c.site as contragent_site,
			o.phones, o.cont_person, o.id_delivery,  o.id_parking, o.id_city,
			o.id_delivery_service, o.descr, o.id_customer, o.note, o.otpusk_prices_sum,
				(SELECT email
				FROM "._DB_PREFIX_."user u, "._DB_PREFIX_."customer cu
				WHERE cu.id_user = u.id_user
				AND cu.id_user = o.id_customer) AS customer_email,
				(SELECT name
				FROM "._DB_PREFIX_."delivery del
				WHERE del.id_delivery = o.id_delivery ) AS customer_delivery,
				(SELECT name
				FROM "._DB_PREFIX_."delivery_service de
				WHERE de.id_delivery_service = o.id_delivery_service ) AS delivery
			FROM "._DB_PREFIX_."order o, "._DB_PREFIX_."osp osp, "._DB_PREFIX_."user u, "._DB_PREFIX_."contragent c
			WHERE o.id_order = osp.id_order
			AND o.target_date>=\"$date\"
			AND c.id_user = o.id_contragent
			AND o.id_order_status IN (1,2,6)
			AND o.id_customer = u.id_user
			GROUP BY id_order LIMIT 1000";
		$arr = $this->db->GetArray($sql);
		return $arr;
	}

	public function GetCustomer_export($date4){
		$date = $_POST['date4'];
		list($d,$m,$y) = explode(".", trim($date));
		$date = mktime(0, 0, 0, $m , $d, $y);
		$sql = "SELECT u.id_user, u.name, u.email, u.date_add,
			cu.cont_person, cu.phones
			FROM "._DB_PREFIX_."user u, "._DB_PREFIX_."customer cu
			WHERE u.id_user=cu.id_user
			AND u.date_add>=\"$date\"
			LIMIT 1000";
		$arr = $this->db->GetArray($sql);
		return $arr;
	}

	public function GetTovar_export($order2){
		$order = $_POST['order2'];
		$sql = "SELECT os.id_order, os.id_zapis, p.art, os.opt_qty, p.price_opt,
			p.price_mopt, os.mopt_qty, os.id_supplier, os.id_supplier_mopt,
			os.site_price_opt, os.site_price_mopt, os.price_opt_otpusk, os.price_mopt_otpusk,
			os.contragent_qty, os.contragent_mqty, o.discount
			FROM "._DB_PREFIX_."osp os, "._DB_PREFIX_."product p,  "._DB_PREFIX_."order o
			WHERE os.id_product=p.id_product
			AND os.id_order>=\"$order\"
			AND  os.id_order=o.id_order
			AND  o.id_order_status!=3
			AND  o.id_order_status!=7
			LIMIT 10000";
		$arr = $this->db->GetArray($sql);
		return $arr;
	}

	public function GetKatalog_export($order3){
		$order = $_POST['order3'];
		$sql = "SELECT p.id_product, p.art as art_tovar, p.name as name_tovar,
			p.descr, p.price_opt, p.price_mopt, p.inbox_qty, p.min_mopt_qty,
			p.qty_control, p.units, ca.name as name_katalog, ca.art as art_katalog
			FROM "._DB_PREFIX_."product p, "._DB_PREFIX_."category ca, "._DB_PREFIX_."cat_prod cat
			WHERE p.id_product = cat.id_product
			AND ca.id_category = cat.id_category
			AND p.art > $order
			GROUP BY p.art
			ORDER BY p.art ASC
			LIMIT 3000";
		$arr = $this->db->GetArray($sql);
		return $arr;
	}

	public function GetSupplier_export($date5){
		$date = $_POST['date5'];
		list($d,$m,$y) = explode(".", trim($date));
		$date = mktime(0, 0, 0, $m , $d, $y);
		$sql = "SELECT su.id_user, su.article, su.phones,
			su.place, u.name, u.email, u.date_add
			FROM "._DB_PREFIX_."supplier su, "._DB_PREFIX_."user u
			WHERE su.id_user = u.id_user
			AND u.date_add>=\"$date\"
			LIMIT 1000";
		$arr = $this->db->GetArray($sql);
		return $arr;
	}

	public function SaveInterviewResults($arr){
		$f['email'] = trim($arr['email']);
		$f['name'] = trim($arr['name']);
		$f['phone'] = trim($arr['phone']);
		$f['city'] = trim($arr['city']);
		$f['sex'] = trim($arr['sex']);
		$f['operator_quality'] = trim($arr['operator_quality']);
		$f['site_quality'] = trim($arr['site_quality']);
		$f['owned_shop_type'] = trim($arr['owned_shop_type']);
		$f['owned_shop_type'] = trim($arr['owned_shop_type']);
		$f['buy_reason'] = trim($arr['buy_reason']);
		$f['buy_frequency'] = trim($arr['buy_frequency']);
		$f['interested_in'] = trim($arr['interested_in']);
		$f['categories'] = trim($arr['categories']);
		$f['recomendations'] = trim($arr['recomendations']);
		if(!$this->db->Insert(_DB_PREFIX_.'interview_results', $f)){
			echo $this->db->ErrorMsg();
			$this->db->FailTrans();
			return false;
		}
		$this->db->CompleteTrans();
		return true;
	}

	public function updateInfoPerson($arrInfo){
		if(!empty($arrInfo)){

			if(isset($arrInfo['firstname'])){
				$f['name'] = $arrInfo['firstname'];
				$this->db->StartTrans();
				if (!$sql = $this->db->Update(_DB_PREFIX_ . 'user', $f, "id_user = ".$_COOKIE['id_user'])){
					echo $this->db->ErrorMsg();
					$this->db->FailTrans();
					return false; //Если не удалось записать в базу
				}
				$this->db->CompleteTrans();
			}
			if(isset($arrInfo['firstname']) || isset($arrInfo['middlename']) || isset($arrInfo['lastname'])){
				$f1['cont_person'] = $arrInfo['firstname'].' '.$arrInfo['middlename'].' '.$arrInfo['lastname'];
				$this->db->StartTrans();
				if(!$sql0 = $this->db->Update(_DB_PREFIX_.'customer', $f1, "id_user = ".$_COOKIE['id_user'])){
					echo $this->db->ErrorMsg();
					$this->db->FailTrans();
					return false; //Если не удалось записать в базу
				}
				$this->db->CompleteTrans();

			}
			if(isset($arrInfo['selected_city'])){
				$f2['id_city'] = "(SELECT id_city FROM xt_city WHERE name = '". $arrInfo['selected_city'] ."' LIMIT 1)";
				$this->db->StartTrans();


				if(!$sql1 = $this->db->Query(" UPDATE xt_customer  SET `id_city` = ". $f2['id_city'] ."  WHERE id_user = ".$_COOKIE['id_user'])){
					echo $this->db->ErrorMsg();
					$this->db->FailTrans();
					return false; //Если не удалось записать в базу
				}
				if(!$sql2 = $this->db->Query(" UPDATE xt_order  SET `id_city` = ". $f2['id_city'] ." WHERE id_order = ".$_COOKIE['id_order'])){
					echo $this->db->ErrorMsg();
					$this->db->FailTrans();
					return false; //Если не удалось записать в базу
				}
				$this->db->CompleteTrans();

			}
			if(isset($arrInfo['selected_post_office_id'])){
				$f3['id_city'] = $arrInfo['selected_post_office_id'];
				$f3['id_delivery'] = 3;
				$this->db->StartTrans();
				if(!$sql3 = $this->db->Update(_DB_PREFIX_.'customer', $f3, "id_user = ".$_COOKIE['id_user'])){
					echo $this->db->ErrorMsg();
					$this->db->FailTrans();
					return false; //Если не удалось записать в базу
				}
				$this->db->CompleteTrans();
				$this->db->StartTrans();
				if(!$sql4 = $this->db->Update(_DB_PREFIX_.'order', $f3, "id_order = ".$_COOKIE['id_order'])){
					echo $this->db->ErrorMsg();
					$this->db->FailTrans();
					return false; //Если не удалось записать в базу
				}
				$this->db->CompleteTrans();
			}
			if(isset($arrInfo['delivery_service'])){
				$f4['id_delivery_service'] = "(SELECT id_delivery_service FROM xt_delivery_service WHERE name = '". $arrInfo['delivery_service'] ."')";
				$this->db->StartTrans();
				if(!$this->db->Query(" UPDATE "._DB_PREFIX_."order  SET `id_delivery_service` = ". $f4['id_delivery_service'] ." WHERE id_order = ".$_COOKIE['id_order'])){
					echo $this->db->ErrorMsg();
					$this->db->FailTrans();
					return false; //Если не удалось записать в базу
				}
				$this->db->CompleteTrans();
			}
			if(isset($arrInfo['delivery_address'])){
				$f5['note2'] = $arrInfo['delivery_address'];
				$f5['id_delivery'] = 1;
				$this->db->StartTrans();
				if(!$this->db->Update(_DB_PREFIX_.'order', $f5, "id_order = ".$_COOKIE['id_order'])){
					echo $this->db->ErrorMsg();
					$this->db->FailTrans();
					return false; //Если не удалось записать в базу
				}
				$this->db->CompleteTrans();
				$this->db->StartTrans();
				if(!$this->db->Query(" UPDATE "._DB_PREFIX_."customer  SET `id_delivery` = 1 WHERE id_user = ".$_COOKIE['id_user'])){
					echo $this->db->ErrorMsg();
					$this->db->FailTrans();
					return false; //Если не удалось записать в базу
				}
				$this->db->CompleteTrans();
			}

			return true;//Если все ок
		}else{
			return false; //Если масив пустой
		}
	}

	public function SetSessionCustomerBonusCart($id_customer){
		$this->SetFieldsById($id_customer);
		if(!empty($this->fields['bonus_card'])){
			$_SESSION['member']['bonus']['bonus_card'] = $this->fields['bonus_card'];
			$_SESSION['member']['bonus']['bonus_discount'] = $this->fields['bonus_discount'];
			$_SESSION['member']['bonus']['bonus_balance'] = $this->fields['bonus_balance'];
			return true;
		}else{
			return false;
		}
	}
}