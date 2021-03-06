<?php
G::GetUserInfo();
G::DefineBaseURL();
G::DefineRootDirectory();
$root = _root.'adm'.DIRSEP;
require(_root.'config.php');
// ******************************** Начальное конфигурирование *************************************
$baseUrl = '//'.$_SERVER['SERVER_NAME'].'/';
/*define('_base_url', $baseUrl);*/
G::ToGlobals(array(
	'URL_base'				=> $baseUrl,
	'URL_request'			=> 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'],
	'URL_img'				=> $baseUrl.'adm/img/',
	'URL_css'				=> $baseUrl.'adm/css/',
	'URL_js'				=> $baseUrl.'adm/js/',
	'PATH_root'				=> $root,
	'PATH_global_root'		=> _root,
	'PATH_core'				=> $root.'core'.DIRSEP,
	'PATH_sys'				=> _root.'~core'.DIRSEP.'sys'.DIRSEP,
	'PATH_model'			=> _root.'~core'.DIRSEP.'model'.DIRSEP,
	'PATH_product_img'		=> _root.'product_images'.DIRSEP,
	'PATH_block'			=> $root.'core'.DIRSEP.'block'.DIRSEP,
	'PATH_contr'			=> $root.'core'.DIRSEP.'contr'.DIRSEP,
	'PATH_tpl'				=> $root.'core'.DIRSEP.'tpl'.DIRSEP,
	'PATH_tpl_global'		=> $root.'core'.DIRSEP.'tpl'.DIRSEP.'_global'.DIRSEP,
	'PATH_global_tpl_global'=> _root.'~core'.DIRSEP.'tpl'.DIRSEP.'_global'.DIRSEP,
	'PATH_news_img'			=> _root.'news_images'.DIRSEP,
	'PATH_post_img'			=> _root.'post_images'.DIRSEP,
));
unset($config);

$GLOBALS['DefaultController'] = 'main';
//$GLOBALS['__graph'] = $tpl_graph;
$GLOBALS['MainTemplate'] = 'main.tpl';
$GLOBALS['NoSidebarTemplControllers'] = array('msg', 'srv');
// Массив ссылок иерархии (используются также в хлебных крошках)
$GLOBALS['IERA_LINKS'] = array();
$GLOBALS['IERA_LINKS'][] = array(
	'title' => 'Главная',
	'url' => _base_url.'/adm/'
);
// Лимит ссылок в навигации
$GLOBALS['Limit_nav'] = 10; // ???
// сколько брать записей из таблицы (при использовании пагинатора)
$GLOBALS['Limit_db'] = 30;
$GLOBALS['Start'] = 0;
$GLOBALS['Limits_db'] = array(30, 60, 100);

// ********************************** Подключение и инициализация классов  **********************************
// Функция подключения файлов "на лету"
spl_autoload_register(function ($className){
	if(strpos($className, 'PHPExcel_') === 0){
		$filename = str_replace('_', DIRECTORY_SEPARATOR, str_replace('PHPExcel', '', $className)).'.php';
		$path = $GLOBALS['PATH_sys'].'PHPExcel';
	}else{
		// Если вошло название класса из примера simple_html_dom оно преобразовывается в simplehtmldom Заменой всех "_" на пустую строку
		// Причем, strtolower переводит все символы в нижний регистр т.е. SimPle станет simple
		// после этого конкатенируется строка '_c.php' так заканчиваются все классы. Неписаное правило =)
		$filename = strtolower(str_replace('_', '', $className)).'_c.php';
		// Определяем путь:
		// Сначала ищем файл в папке ~core/sys/, Если он там есть, берем ~core/sys/ как путь. Если его там не нашли берем ~core/model/ как путь
		$path = file_exists($GLOBALS['PATH_sys'].$filename)?$GLOBALS['PATH_sys']:$GLOBALS['PATH_model'];
	}
	// Еще раз проверяем наличие файла в выбранной папке
	if(file_exists($path.$filename)){
		// Если он есть, Выполняем его подключение
		// @ - значит игнорировать ошибки возникшие при попытке подключения файла
		@require_once($path.$filename);
	}else{
		// Иначе ругаемся 
		// "Не могу найти файл 'такой-то' с класом 'таким-то' по пути 'такому-то'"
		die("<br>Can't find file '$filename' with class '$className' in '$path'");
	}
});
// Подключаем файл с функциями инициализации simple_html_dom
require($GLOBALS['PATH_core'].'html_dom_helpers.php');
// Вместо этой хрени. Когда на каждой странице подключаются все файлы срауз, вне зависимости от того, используются они или нет
// require($GLOBALS['PATH_sys'].'tpl_c.php');
// require($GLOBALS['PATH_sys'].'link_c.php');
// require($GLOBALS['PATH_sys'].'db_c.php');
// require($GLOBALS['PATH_sys'].'dbtree_c.php');
// require($GLOBALS['PATH_sys'].'paginator_c.php');
// require($GLOBALS['PATH_sys'].'acl_c.php');
// require($GLOBALS['PATH_sys'].'mailer_c.php');
// require($GLOBALS['PATH_sys'].'status_c.php');
// require($GLOBALS['PATH_sys'].'images_c.php');
// require($GLOBALS['PATH_sys'].'sfYaml.php');
// require($GLOBALS['PATH_sys'].'sfYamlParser.php');
// require($GLOBALS['PATH_sys'].'cron_c.php');
// including configuration file
// require(_root.'config.php');
// connection to mysql server
$db = new db($GLOBALS['DB']['HOST'], $GLOBALS['DB']['USER'], $GLOBALS['DB']['PASSWORD'], $GLOBALS['DB']['NAME']);
// Когда мы пишем такую конструкцию "$var = new classname()"
// Автоматически запускается функция spl_autoload_register, в которую передается аргументом classname
$GLOBALS['db'] =& $db;
$sql = "SELECT * FROM "._DB_PREFIX_."profiles";
$profiles = $db->GetArray($sql);
$admin_controllers = G::GetControllers(str_replace('~core', 'adm'.DIRSEP.'core', $GLOBALS['PATH_contr']));
foreach($profiles as $profile){
	define('_ACL_'.strtoupper($profile['name']).'_', $profile['id_profile']);
}
// $ACL_PERMS = array(
// 	// default rights
// 	'rights' => $admin_controllers,
// 	// groups
// 	'groups' => $profiles
// );
G::ToGlobals(array(
	'ACL_PERMS' => array(
		// default rights
		'rights' => array(
			'admin_panel',
			'anonim_cab',
			'catalog',
			'configs',
			'contragent_cab',
			'customer_cab',
			'diler_cab',
			'duplicates',
			'locations',
			'manager_cab',
			'manufacturers',
			'moderation_edit_product',
			'news',
			'orders',
			'pages',
			'posts',
			'pricelist',
			'photo_products',
			'product',
			'product_moderation',
			'product_report',
			'remitters',
			'slides',
			'specifications',
			'supplier_cab',
			'units',
			'wishes',
			'segmentations',
			'supplier_prov',
			'monitoring',
			'seotext',
			'orders_category',
			'order',
			'guestbook',
			'graphics',
			'users',
			'customers',
			'contragents',
			'suppliers',
			'parser'
		),
		// groups
		'groups' => array(
			0 => array(
				'name' => 'guest',
				'caption' => 'Все',
				'permissions' => 0 // disallow all
			),
			1 => array(
				'name' => 'admin',
				'caption' => 'Администратор',
				'permissions' => 1 // allow all
			),
			2 => array(
				'name' => 'moderator',
				'caption' => 'Администратор наполнения',
				'permissions' => array(
					'admin_panel',
					'catalog',
					'product',
					'news',
					'product_report',
					'product_moderation',
					'moderation_edit_product',
					'pages',
					'pageedit',
					'slides',
					'duplicates',
					'specifications',
					'units',
					'wishes',
					'segmentations',
					'pricelist',
					'supplier_prov',
					'orders_category',
					'monitoring',
					'guestbook',
					'graphics',
					'suppliers',
					'parser',
					'photo_products'
				)
			),
			3 => array(
				'name' => 'supplier',
				'caption' => 'Поставщик',
				'permissions' => array(
					'supplier_cab',
					'product'
				)
			),
			4 => array(
				'name' => 'contragent',
				'caption' => 'Контрагент',
				'permissions' => array(
					'contragent_cab'
				)
			),
			5 => array(
				'name' => 'customer',
				'caption' => 'Покупатель',
				'permissions' => array(
					'customer_cab'
				)
			),
			6 => array(
				'name' => 'manager',
				'caption' => 'Менеджер',
				'permissions' => array(
					'manager_cab'
				)
			),
			7 => array(
				'name' => 'diler',
				'caption' => 'Дилер',
				'permissions' => array(
					'diler_cab'
				)
			),
			8 => array(
				'name' => 'anonim',
				'caption' => 'Покупатель аноним',
				'permissions' => array(
					'anonim_cab'
				)
			),
			9 => array(
				'name' => 'SEO_optimizator',
				'caption' => 'СЕО - оптимизатор',
				'permissions' => array(
					'admin_panel',
					'pages',
					'pageedit',
					'news',
					'catalog',
					'product',
					'product_moderation',
					'moderation_edit_product',
					'slides',
					'duplicates',
					'specifications',
					'units',
					'wishes',
					'segmentations',
					'product_report',
					'monitoring',
					'seotext',
					'orders_category',
					'order',
					'guestbook',
					'graphics'
				)
			),
			10 => array(
				'name' => 'm_diler',
				'caption' => 'M-Дилер',
				'permissions' => array(
					'm_diler_cab'
				)
			),
			11 => array(
				'name' => 'terminal',
				'caption' => 'Терминальный клиент',
				'permissions' => array(
					'terminal_cab'
				)
			),
			12 => array(
				'name' => 'supplier_manager',
				'caption' => 'Менеджер поставщиков',
				'permissions' => array(
					'supplier_manager_cab'
				)
			),
			13 => array(
				'name' => 'photographer',
				'caption' => 'Фотограф',
				'permissions' => array(
					'admin_panel',
					'catalog',
					'product',
					'news',
					'product_report',
					'product_moderation',
					'moderation_edit_product',
					'pages',
					'pageedit',
					'slides',
					'duplicates',
					'specifications',
					'units',
					'wishes',
					'segmentations',
					'pricelist',
					'supplier_prov',
					'photo_products'
				)
			),
			14 => array(
				'name' => 'remote_content',
				'caption' => 'Удаленный контент-менеджер',
				'permissions' => array(
					'admin_panel',
					'catalog',
					'product',
				)
			)
		)
	)
));
$tpl = new tpl();
$GLOBALS['tpl'] =& $tpl;
// получение всех настроек с БД
$sql = "SELECT name, value FROM "._DB_PREFIX_."config WHERE sid = 1";
$arr = $db->GetArray($sql);
// формирование глобального массива настроек
foreach ($arr as $i){
	$GLOBALS['CONFIG'][$i['name']] = $i['value'];
}
unset($sql, $arr);

// почтовая конфигурация
$GLOBALS['MAIL_CONFIG']['from_name'] = $GLOBALS['CONFIG']['mail_caption']; // from (от) имя
$GLOBALS['MAIL_CONFIG']['from_email'] = $GLOBALS['CONFIG']['mail_email']; // from (от) email адрес
// На всякий случай указываем настройки
// для дополнительного (внешнего) SMTP сервера.
$GLOBALS['MAIL_CONFIG']['smtp_mode'] = 'disabled'; // enabled or disabled (включен или выключен)
$GLOBALS['MAIL_CONFIG']['smtp_host'] = null;
$GLOBALS['MAIL_CONFIG']['smtp_port'] = null;
$GLOBALS['MAIL_CONFIG']['smtp_username'] = null;
