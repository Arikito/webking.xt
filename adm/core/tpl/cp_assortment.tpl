<!-- <h1><?=$h1?></h1> -->
<div id="assortment" class="grid">
	<div class="row">
		<div class="col-md-12">
			<h2>Информация о поставщике</h2>
		</div>
		<div class="col-lg-4 col-md-6 col-sm-8 col-xs-12">
			<table class="list">
				<thead>
					<tr>
						<td colspan="2">Акты сверки</td>
					</tr>
				</thead>
				<tr class="animate">
					<td>Акт сверки цен поставщика</td>
					<td>
						<a href="<?=$GLOBALS['URL_base']?>adm/act_supplier/<?=$id_supplier?>" target="_blank" title="Откроется в новой вкладке" class="btn-m-lblue fr">Открыть</a>
					</td>
				</tr>
				<tr class="animate">
					<td>Новая с ценами</td>
					<td>
						<a href="<?=$GLOBALS['URL_base']?>adm/act_supplier/<?=$id_supplier?>?type=new&price=true" target="_blank" title="Откроется в новой вкладке" class="btn-m-lblue fr">Открыть</a>
					</td>
				</tr>
				<tr class="animate">
					<td>Новая без цен</td>
					<td>
						<a href="<?=$GLOBALS['URL_base']?>adm/act_supplier/<?=$id_supplier?>?type=new&price=false" target="_blank" title="Откроется в новой вкладке" class="btn-m-lblue fr">Открыть</a>
					</td>
				</tr>
				<tr class="animate">
					<td>Сверх-новая с ценами</td>
					<td>
						<a href="<?=$GLOBALS['URL_base']?>adm/act_supplier/<?=$id_supplier?>?type=wide" target="_blank" title="Откроется в новой вкладке" class="btn-m-lblue fr">Открыть</a>
					</td>
				</tr>
				<tr class="animate">
					<td>Многоразовая без цен</td>
					<td>
						<a href="<?=$GLOBALS['URL_base']?>adm/act_supplier/<?=$id_supplier?>?type=multiple" target="_blank" title="Откроется в новой вкладке" class="btn-m-lblue fr">Открыть</a>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="product_list row">
		<div class="col-md-12">
			<h2>Ассортимент</h2>
		</div>
		<div class="col-md-12">
			<table class="list">
				<thead>
					<tr class="animate">
						<td colspan="3">Название</td>
						<td>Наличие</td>
						<td>Минимальное кол-во</td>
						<td>Цена розн.</td>
						<td>Цена опт</td>
						<td>USD</td>
						<td>Арт. поставщика</td>
					</tr>
				</thead>
				<tbody>
					<?$wh = 'width="90px" height="90px"';
					foreach($list as $item){?>
						<tr class="animate" data-id="<?=$item['id_product'];?>">
							<td>
								<span class="icon-remove animate">n</span>
							</td>
							<td>
								<?if(!empty($item['images'])){?>
									<img <?=$wh?> class="lazy" data-original="<?=_base_url?><?=str_replace('original', 'small', $item['images'][0]['src'])?>" alt="<?=$item['name']?>">
									<noscript>
										<img <?=$wh?> src="<?=_base_url?><?=str_replace('original', 'small', $item['images'][0]['src'])?>" alt="<?=$item['name']?>">
									</noscript>
								<?}else{?>
									<img <?=$wh?> class="lazy" data-original="<?=_base_url?><?=str_replace("image/", "image/250/", $item['img_1'])?>"/>
									<noscript>
										<img <?=$wh?> src="<?=_base_url?><?=str_replace("image/", "image/250/", $item['img_1'])?>"/>
									</noscript>
								<?}?>
							</td>
							<td>
								<p><?=$item['name'];?></p>
								<p>Арт. <?=$item['art'];?></p>
							</td>
							<td class="center"><input type="checkbox" class="active" <?=$item['active']>0?'checked':null;?>></td>
							<td class="center"><?=$item['min_mopt_qty'];?><?=$item['units'];?></td>
							<td class="center">
								<input type="number" step="0.01" min="0" class="input-m price" data-mode="mopt" value="<?=$item['inusd'] == 1?$item['price_mopt_otpusk_usd']:$item['price_mopt_otpusk'];?>">
							</td>
							<td class="center">
								<input type="number" step="0.01" min="0" class="input-m price" data-mode="opt" value="<?=$item['inusd'] == 1?$item['price_opt_otpusk_usd']:$item['price_opt_otpusk'];?>">
							</td>
							<td class="center"><input type="checkbox" <?=$item['inusd'] == 1?'checked':null;?> class="currency"></td>
							<td class="center"></td>
						</tr>
					<?}?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	var id_supplier = <?=$id_supplier;?>;
	<?=$supplier['single_price']==0?'TogglePriceColumns("Off");':'TogglePriceColumns("On");';?>
	$(function(){
		// Клик по переключателю "Единая цена"
		$('.switch').click(function(){
			var single_price;
			if($(this).closest('#switcher').hasClass('Off')){
				if(window.confirm('Для каждого товара, вместо двух цен, будет установлена единая цена.\nПроверьте, пожалуйста, цены после выполнения.')){
					$(this).closest('#switcher').toggleClass('On').toggleClass('Off');
					if($(this).closest('#switcher').hasClass('On')){
						// document.cookie = "onlyprice=On;";
						TogglePriceColumns('On');
						single_price = 1;
					}else{
						// document.cookie = "onlyprice=Off;";
						TogglePriceColumns('Off');
						single_price = 0;
					}

				}
			}else{
				$(this).closest('#switcher').toggleClass('On').toggleClass('Off');
				if($(this).closest('#switcher').hasClass('On')){
					// document.cookie = "onlyprice=On;";
					TogglePriceColumns('On');
					single_price = 1;
				}else{
					// document.cookie = "onlyprice=Off;";
					TogglePriceColumns('Off');
					single_price = 0;
				}
			}
			$.ajax({
				url: '/ajaxsuppliers',
				type: "POST",
				dataType : "json",
				data:({
					"action": 'toggle_single_price',
					"id_supplier": '<?=$supplier['id_user'];?>',
					"single_price": single_price
				}),
			});
		});
		// Переключение активности записи ассортимента
		$('.active').on('change', function(){
			var parent = $(this).closest('tr'),
				active = 0;
			if(this.checked){
				active = 1;
			}
			var data = {
				id_product: parent.data('id'),
				id_supplier: id_supplier,
				active: active
			}
			ajax('product', 'UpdateAssort', data, 'json').done(function(data){
			});
		});
		// Переключение валюты
		$('.currency').on('change', function(){
			var parent = $(this).closest('tr'),
				inusd = 0;
			if(this.checked){
				inusd = 1;
			}
			var data = {
				id_product: parent.data('id'),
				id_supplier: id_supplier,
				inusd: inusd
			}
			ajax('product', 'UpdateAssort', data, 'json').done(function(data){
				parent.find('.price').each(function(){
					var mode = $(this).data('mode');
					if(data.inusd == 1){
						$(this).val(data['price_'+mode+'_otpusk_usd']);
					}else{
						$(this).val(data['price_'+mode+'_otpusk']);
					}
				});
			});
		});
		// Ручное изменение цены
		$('.price').on('keyup, change', function(){
			var parent = $(this).closest('tr'),
				data = {
					id_product: parent.data('id'),
					id_supplier: id_supplier,
					mode: $(this).data('mode'),
					price: $(this).val(),
					comment: 'comment example'
				};
			ajax('product', 'UpdateAssort', data, 'json').done(function(data){
			});
		});
		// Клик по "Удалить из ассортимента"
		$('.icon-remove').on('click', function(e){
			if(confirm('Действительно удалить?')){
				e.preventDefault();
				var parent = $(this).closest('tr'),
					id = parent.data('id');
				ajax('product', 'DelFromAssort', {id: id, id_supplier: id_supplier}).done(function(){
					parent.fadeOut().remove();
				});
			}
		});
	});
	// Функция переключения "Единой цены"
	function TogglePriceColumns(action){
		if(action == 'On'){
			$('.price_1').css({
				"width": "20%"
			});
			$('th.price_1 p').text('Цена отпускная');
			$('.price_2').css({
				"display": "none"
			});
			$('.switcher_container').css({
				"width": "100%"
			});
			$.each($('td.price_1 input'), function(){
				var id = $(this).attr('id').replace(/\D+/g,"");
				if($('#price_opt_otpusk_'+id).val() !== $('#price_mopt_otpusk_'+id).val()){
					if($('#price_opt_otpusk_'+id).val() == '0,00'){
						$('#price_opt_otpusk_'+id).val($('#price_mopt_otpusk_'+id).val()).change();
					}else{
						$('#price_mopt_otpusk_'+id).val($('#price_opt_otpusk_'+id).val()).change();
					}
				}
			});
		}else{
			$('.price_1').css({
				"width": "10%"
			});
			$('th.price_1 p').text('Цена отпускная мин. к-ва');
			$('.price_2').css({
				"display": "table-cell"
			});
			$('.switcher_container').css({
				"width": "200%"
			});
			$.each($('td.price_1 input'), function(){
				var id = $(this).attr('id').replace(/\D+/g,"");
			});
		}
	}

	// Фиксация Заголовка таблицы
	// $(window).scroll(function(){
	// 		console.log($('.supplier_assort_table').offset().top - $('header').outerHeight());
	// 	if($(this).scrollTop() >= 86){
	// 		if(!$('.supplier_assort_table.thead').hasClass('fixed_thead')){
	// 			var width = $('.table_tbody').width();
	// 			$('.supplier_assort_table.thead').css("width", width).addClass('fixed_thead');
	// 			$('.table_tbody').css("margin-top", "65px");
	// 		}
	// 	}else{
	// 		if($('.supplier_assort_table.thead').hasClass('fixed_thead')){
	// 			$('.supplier_assort_table.thead').removeClass('fixed_thead');
	// 			$('.table_tbody').css("margin-top", "0");
	// 		}
	// 	}
	// });
</script>