<?php
if(!isset($_POST['art_product']) or $_POST['art_product'] == 0){
	header('Location: '. _base_url.'/404/');
	exit();
}
$art = $_POST['art_product'];
$invoice = new Invoice();
$res = $invoice->GetSuppliersList($art,$arr);
$tpl->Assign("result", $res);
echo $tpl->Parse($GLOBALS['PATH_tpl'].'supplier_search.tpl');
exit(0);
