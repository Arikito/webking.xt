<?php
$News = new News();
unset($parsed_res);
if(isset($GLOBALS['REQAR'][1]) && is_numeric($GLOBALS['REQAR'][1])){
	$id = $GLOBALS['REQAR'][1];
}else{
	header('Location: '.$GLOBALS['URL_base'].'404/');
	exit();
}
if(!$News->SetFieldsById($id, false)){
	die('Ошибка при выборе новости.');
}
$header = 'Редактирование новости';
$tpl->Assign('h1', $header);
if(isset($_GET['upload']) == true){
	echo $Images->upload($_FILES, $GLOBALS['PATH_news_img'].'temp/');
	exit(0);
}
if(isset($_POST['smb'])){
	require_once ($GLOBALS['PATH_block'].'t_fnc.php'); // для ф-ции проверки формы
	list($err, $errm) = News_form_validate();

	//Удаление фото при нажатии на корзину
	if(isset($_POST['removed_images'])){
		foreach($_POST['removed_images'] as $del_image){
			unlink($GLOBALS['PATH_global_root'].$del_image);
		}
	}

	//Добавление фото
	if(isset($_POST['images'])){
		foreach($_POST['images'] as &$image){
			if(preg_match('/[А-Яа-яЁё]/u', $image)){
				$file = pathinfo($GLOBALS['PATH_global_root'].$image);
				$new_file = $file['dirname'].'/'.G::StrToTrans($file['filename']).'.'.$file['extension'];
				rename($GLOBALS['PATH_global_root'].$image, $new_file);
				$image = str_replace($GLOBALS['PATH_global_root'], '/', $new_file);
			}
		}
	}
	if(!$err){
		if($News->UpdateNews($_POST)){
			$News->UpdatePhoto($id, $_POST['images']);
			$tpl->Assign('msg', 'Новость обновлена.');
			if(isset($_POST['news_distribution']) && $_POST['news_distribution'] == 1){
				$Mailer = new Mailer();
				$Mailer->SendNewsToCustomers1($_POST);
				// $Mailer->SendNewsToCustomersInterview($_POST);
			}
			unset($_POST);
			if(!$News->SetFieldsById($id, false)) die('Ошибка при выборе новости.');
		}else{
			$tpl->Assign('msg', 'Ошибка при обновлении новости.');
			$tpl->Assign('errm', 1);
		}
	}else{
		// показываем все заново но с сообщениями об ошибках
		if(isset($_POST['date'])&&!isset($errm['date'])){
			list($d,$m,$y) = explode(".", trim($_POST['date']));
			$_POST['date'] = mktime(0, 0, 0, $m , $d, $y);
		}
		$tpl->Assign('msg', 'Новость не обновлена.');
		$tpl->Assign('errm', $errm);
	}
}
if(isset($_POST['test_distribution'])){
	$Mailer = new Mailer();
	$Mailer->SendNewsToCustomers1($_POST);
	// $Mailer->SendNewsToCustomersInterview($_POST);
}
if(!isset($_POST['smb'])){
	foreach($News->fields as $k=>$v){
		$_POST[$k] = $v;
	}
}
$parsed_res = array(
	'issuccess'	=> true,
	'html'		=> $tpl->Parse($GLOBALS['PATH_tpl'].'cp_news_ae.tpl')
);
$ii = count($GLOBALS['IERA_LINKS']);
$GLOBALS['IERA_LINKS'][$ii]['title'] = "Новости";
$GLOBALS['IERA_LINKS'][$ii++]['url'] = $GLOBALS['URL_base'].'adm/news/';
$GLOBALS['IERA_LINKS'][$ii]['title'] = $header;
if($parsed_res['issuccess'] == true){
	$tpl_center .= $parsed_res['html'];
}
?>