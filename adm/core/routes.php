<?php
// Отрезаем завершающий слеш `/`
$_SERVER['REQUEST_URI'] = preg_replace('#/$#is', '', $_SERVER['REQUEST_URI']);
preg_match('/[\?].*$/', $_SERVER['REQUEST_URI'], $match);
$GLOBALS['GetString'] = !empty($match)?$match[0]:'';
preg_match_all('#/([^/]+)#is', $_SERVER['REQUEST_URI'], $ma);

if($ma[1][0] == 'adm'){
	//unset($ma[1]);
	array_shift($ma[1]);
}
/* Далее, если REQUEST_URI пуст - устанавливается контроллер по-умолчанию
 * если контроллер не найден среди файлов, то устанавливается контроллер 404 ошибки
 */
if(empty($ma[1])){
	$ma[1][0] = $GLOBALS['DefaultController'];
}elseif (!in_array(preg_replace('/(\?.+$)/', '', $ma[1][0]), $GLOBALS['Controllers'])){
	array_unshift($ma[1], '404');
}
$GLOBALS['CurrentController'] = preg_replace('/(\?.+$)/', '', $ma[1][0]);
$GLOBALS['REQAR'] = $ma[1];
//$GLOBALS['__graph'] = $tpl_graph;

if(!G::IsLogged()){
	$GLOBALS['CurrentController'] = 'login';
	$GLOBALS['REQAR'] = array();
}else{
	$Users->SetUser($_SESSION['member']) or exit('Ошибка пользователя.1');
	_acl::load($Users->fields['gid']);
}
/**
 * Для удобства некоторые переменные из REQUEST_URI объявляются в массиве $_GET
 */
foreach($ma[1] as $item){
	if(preg_match('/^p[0-9]+/', $item, $match)){
		$GLOBALS['Page_id'] = (integer) preg_replace('/^p/', '', $match[0]);
	}
	if(preg_match('#^p([\d]+)$#is', $item, $ma1)){
		$_GET['page_id'] = $ma1[1];
		// $GLOBALS['Page_id'] = $ma1[1];
	}
}
unset($ma);unset($ma1);
?>