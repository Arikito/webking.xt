<?php
$News = new News();
$Page = new Page();
$Page->PagesList();
$tpl->Assign('list_menu', $Page->list);
if(isset($GLOBALS['Rewrite'])){
	if(!$News->SetFieldsByRewrite($GLOBALS['Rewrite'])){
		header('Location: '._base_url.'/404/');
		exit();
	}
	$news = $News->fields;
	$header = $news['title'];
	$tpl->Assign('header', $header);
	$tpl->Assign('data', $news);
	$GLOBALS['IERA_LINKS'][] = array(
		'title' => 'Новости оптового торгового центра xt.ua',
		'descr' => strip_tags($news['descr_short']),
		'url' => Link::Custom('news')
	);
	$GLOBALS['IERA_LINKS'][] = array(
		'title' => $header,
		'url' => Link::Custom('news', $news['translit'])
	);
	$template = 'cp_news.tpl';
}else{
	$header = 'Новости оптового торгового центра xt.ua';
	$tpl->Assign('header', $header);
	$GLOBALS['IERA_LINKS'][] = array(
		'title' => $header,
		'url' => Link::Custom('news')
	);
	if($News->NewsList()){
		$tpl->Assign('list', $News->list);
	}
	$template = 'cp_newslist.tpl';
}
$parsed_res = array(
	'issuccess'	=> true,
	'html'		=> $tpl->Parse($GLOBALS['PATH_tpl'].$template)
);
if(TRUE == $parsed_res['issuccess']){
	$tpl_center .= $parsed_res['html'];
}
?>