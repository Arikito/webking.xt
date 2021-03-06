<div class="agents_gifts">
	<h2>Подарки клиентам</h2>
	<div class="msg-info gifts_info">
		<div class="msg_icon">
			<i class="material-icons"></i>
		</div>
	    <p class="msg_title">!</p>
	    <p class="msg_text">Здесь вы можете выбрать подарки для ваших клиентов, которые они могут получить при первом заказе</p>
	</div>
	<div class="gifts_container">
		<input type="hidden" value="<?=$_SESSION['member']['id_user']?>" class="id_agent_js">
		<?if(isset($gifts)){
			foreach ($gifts as $product) {?>
				<div class="gift <?=in_array($product["id_product"], $selected_gifts)?'selected':null?>">
					<input type="hidden" value="<?=$product["id_product"]?>" class="id_gift_js">
					<?if(!empty($product['images'])){?>
						<img itemprop="image" src="<?=G::GetImageUrl($product['images'][0]['src'], 'medium')?>"/>
					<?}else if(!empty($product['img_1'])){?>
						<img itemprop="image" src="<?=G::GetImageUrl($product['img_1'], 'medium')?>"/>
					<?}else{?>
						<img itemprop="image" src="<?=G::GetImageUrl('/images/nofoto.png')?>"/>
					<?}?>
					<p class="gift_title"><?=$product["name"]?></p>
					<p class="gift_art">Артикул: <?=$product["art"]?></p>
					<p class="gift_selected"><span>Добавлено</span></p>
					<button class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent del_gift_btn gift_btn_js">удалить</button>
					<button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored add_gift_btn gift_btn_js">добавить</button>
				</div>
			<?}
		}?>
	</div>
</div>
