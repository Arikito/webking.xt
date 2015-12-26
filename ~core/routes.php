<?php
$request = $request_url = preg_replace('/\/$/', '', isset($_GET['request'])?$_GET['request']:preg_replace('/^.*/', '', $_SERVER['REQUEST_URI']));
// check if this is product url
$GLOBALS['CurrentController'] = 'main';
if(preg_match('/^.*\.html$/', $request)){
	$GLOBALS['CurrentController'] = 'product';
	$rewrite_arr = explode('/', $request);
	$GLOBALS['Rewrite'] = str_replace('.html', '', array_pop($rewrite_arr));
}else{
	if($request !== ''){
		// detecting page if exist
		if(preg_match('/\/p[0-9]+/', $request, $match)){
			$GLOBALS['Page_id'] = preg_replace('/^\/p/', '', $match[0]);
			$request = preg_replace('/\/p[0-9]+/', '', $request);
		}
		// detecting sort if exist
		if(preg_match('/\/sort=[^\/]+/', $request, $match)){
			$GLOBALS['Sort'] = preg_replace('/^\/sort=/', '', $match[0]);
			$request = preg_replace('/\/sort=[^\/]+/', '', $request);
		}
		$rewrite_arr = explode('/', $request);
		if(!in_array($rewrite_arr[0], $GLOBALS['Controllers']) || $rewrite_arr[0] == 'products'){
			$GLOBALS['CurrentController'] = 'products';
			$GLOBALS['Rewrite'] = array_shift($rewrite_arr);
		}else{
			$GLOBALS['CurrentController'] = array_shift($rewrite_arr);
			$GLOBALS['Rewrite'] = array_shift($rewrite_arr);
			$GLOBALS['Rewrite'] = $GLOBALS['Rewrite'] == $GLOBALS['CurrentController']?false:$GLOBALS['Rewrite'];
		}
		$GLOBALS['Filters'] = G::ParseUrlParams(array_pop($rewrite_arr));
	}
}
if(isset($rewrite_arr) && count($rewrite_arr) > 0){
	switch($GLOBALS['CurrentController']){
		case 'product':
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: http://".$_SERVER['SERVER_NAME'].'/'.str_replace(implode('/', $rewrite_arr).'/', '', $request_url));
			break;
		default:
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: http://".$_SERVER['SERVER_NAME'].'/'.str_replace(implode('/', $rewrite_arr).'/', '', $request_url).'/');
			break;
	}
}
unset($rewrite_arr, $request_url, $request);
?>