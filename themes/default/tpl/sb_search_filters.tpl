<script>
	/** Фильтр по цене */
	$(function(){
		// Автопереключение на панель фильтров
		<?if($GLOBALS['Filters'] || isset($GLOBALS['Price_range'])){?>
			$('[data-nav="filter"]').click();
		<?}?>
		// Инициализация слайдера цен
		$("#slider_price").slider({
			range: true,
			min: <?=$min_price?>,
			max: <?=$max_price?>,
			values: [<?=isset($GLOBALS['Price_range'][0]) ? $GLOBALS['Price_range'][0] : $min_price?>,
					 <?=isset($GLOBALS['Price_range'][1]) ? $GLOBALS['Price_range'][1] : $max_price?>],
			step: <?=floor(($max_price - $min_price) * 0.01)?>,
			slide: function(event, ui) {
				$("#amount").html(ui.values[0]+" грн - "+Math.round(ui.values[1])+" грн");
				$('[data-nav="filter"]').addClass('active');
			},
			stop: function(event, ui) {
				var price_range = ui.values[0] + ',' + ui.values[1];
				$.cookie('price_range', price_range, {
					expires: 2,
					path: '/'
				});
				$("#applyFilter").click(function() {
					window.location.href = '<?=Link::Category($GLOBALS['Rewrite'], array('price_range' => "' + price_range + '"))?>';
				});

			/*window.location.href = '<?=Link::Category($GLOBALS['Rewrite'], array('price_range' => "' + price_range + '"))?>';*/
			}
		});
		$("#amount").append($("#slider_price").slider("values", 0)+" грн - "+$("#slider_price").slider("values", 1 )+" грн");





		//Очистить фильтры
		$("#clear_filter").click(function() {
          /*$.cookie('price_range', null);*/
			$(this).addClass('active');
			window.location.href = '<?=Link::Category($GLOBALS['Rewrite'], array('clear'=>true))?>';
		});

		$("#clear_filter_new").click(function() {
			$('label').removeClass('is-checked');
		});

		// Добавление/удаление элементов в массиве
		var filterLink = {};

		$('.filters input').on('change', function() {

			var dataSpec = $(this).data('spec');
			var dataValue = $(this).data('value');

			if (filterLink[dataSpec] === undefined) {
				filterLink[dataSpec] = [];
				filterLink[dataSpec].push(dataValue);
			}else if (filterLink[dataSpec] !== undefined) {
				for ( var key in filterLink) {
					if (key == dataSpec) {
						var chekElem = 0;
						for (var i = 0; i < filterLink[key].length; i++) {
							if (filterLink[key][i] === dataValue) {
								chekElem = 1;
								var searchElem = $.inArray(dataValue, filterLink[key]);
								filterLink[key].splice(searchElem, 1);
							}
						}
						if (chekElem === 0) {
							filterLink[dataSpec].push(dataValue);
						}
					}
				}
			}

			if (filterLink[dataSpec].length === 0) {
				delete filterLink[dataSpec];
			}
			console.log(filterLink);
		});



		//Сделать не активные ссылки у отключенных фильтров
//      $('.disabled').click(function(event) {
//          event.preventDefault();
//      });

	});
</script>
<div class="filters">
	<!-- <div class="filter_block">
		<p>Сбросить все фильтры:</p>
		<ul>
			<li id="clear_filter" >
				<input type="submit" value="Сбросить">
			</li>
		</ul>
	</div> -->

	<button id="clear_filter" class="mdl-button mdl-js-button mdl-button--raised">
		Сбросить все:
	</button>
	<button id="applyFilter" class="mdl-button mdl-js-button mdl-button--raised">
		Применить фильтр:
	</button>



	<div class="filter_block price_range_block">
		<p>Ценовой диапазон</p>
		<div id="priceFields">
			<div class="mdl-textfield mdl-js-textfield">
				<input id="minPrice" class="mdl-textfield__input" type="text" pattern="-?[0-9]*(\.[0-9]+)?" value="<?=$min_price?>">
				<label class="mdl-textfield__label" for="minPrice"><?=$min_price?></label>
			</div>
			<div class="priceValute"><p>грн -</p></div>
			<div class="mdl-textfield mdl-js-textfield">
				<input id="maxPrice" class="mdl-textfield__input" type="text" pattern="-?[0-9]*(\.[0-9]+)?" value="<?=$max_price?>">
				<label class="mdl-textfield__label" for="maxPrice"><?=$max_price?></label>
			</div>
			<div class="priceValute"><p>грн</p></div>
		</div>
		<ul>
			<li>
				<div id="amount"></div>
				<div id="slider_price"></div>
			</li>
		</ul>
	</div>
	<? if(isset($filter_cat) && is_array($filter_cat)){
		foreach($filter_cat as $spec){ ?>
			<div class="filter_block">
				<p><?=$spec['caption']?></p>
				<ul>
					<?foreach($spec['values'] as $value){
						$present = (isset($visible_fil) && !in_array($value['value'], $visible_fil)) ? false : true; ?>
						<li>
							<!-- <?=Link::Category($GLOBALS['Rewrite'], array('filter' => $value['id']))?> -->
							<a href="<?=Link::Category($GLOBALS['Rewrite'], array('filter' => $value['id']))?>">
								<label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect <?=$value['checked']?>">
									<input <?= ($present || in_array($value['id'][0], $id_filter)) ? "" : "disabled";?> type="checkbox" class="mdl-checkbox__input" data-spec="<?=$value['id'][0]?>" data-value="<?=$value['id'][1]?>" <?=$value['checked']?>>
									<span>
										<span class="mdl-checkbox__label"><?=$value['value']?> <?=$value['units']?></span>
										<!--<span class="qnt_products fright"><?= ($present || in_array($value['id'][0], $id_filter)) ? $value['count'] : ''?></span>-->
									</span>
								</label>
							</a>
						</li>

					<?}?>
				</ul>
			</div>
		<?}
	}?>
</div>
<!--
<?if(!empty($list) && (!isset($_SESSION['member']) || $_SESSION['member']['gid'] != _ACL_TERMINAL_)){?>
	<div class="sb_block">
		<h4>Фильтры</h4>
		<div class="sb_container">
			<form action="<?=_base_url."/".preg_replace("#^(.*?)/(p[0-9]+)(.*?)$#is", "\$1\$3/", preg_replace("#^/(.*?)$#i", "\$1", $_SERVER['REQUEST_URI']));?>" method="post" name="search_form" id="filters">
				<input type="hidden" name="query" value="<?=isset($_SESSION['search']['query'])?$_SESSION['search']['query']:null;?>"/>
				<?if(isset($_POST['category2search'])){?>
					<input type="hidden" name="category2search" value="<?=$_SESSION['search']['category2search']?>">
				<?}else{?>
					<input type="hidden" name="searchincat" value="<?=$curcat['id_category']?>">
				<?}?>
				<input type="hidden" name="minprice" id="minprice" value="<?=isset($_SESSION['filters']['minprice'])?$_SESSION['filters']['minprice']:null;?>"/>
				<input type="hidden" name="maxprice" id="maxprice" value="<?=isset($_SESSION['filters']['maxprice'])?$_SESSION['filters']['maxprice']:null;?>"/>
				<?if(isset($_SESSION['filters']['minprice']) && isset($_SESSION['filters']['maxprice']) && $_SESSION['filters']['minprice'] != $_SESSION['filters']['maxprice']){?>
					<div class="price_filter">
						<p>Цена,грн</p>
						<label for="price_from">от
							<input type="number" name="pricefrom" id="price_from" min="<?=$_SESSION['filters']['minprice'];?>" max="<?=$_SESSION['filters']['maxprice'];?>" value="<?=isset($_SESSION['filters']['pricefrom']) && $_SESSION['filters']['pricefrom'] != ''?$_SESSION['filters']['pricefrom']:0?>" required>
						</label>
						<label for="price_to">до
							<input type="number" name="priceto" id="price_to" min="<?=$_SESSION['filters']['minprice']+1;?>" max="<?=$_SESSION['filters']['maxprice']+1;?>" value="<?=isset($_SESSION['filters']['priceto']) && $_SESSION['filters']['pricefrom'] != ''?$_SESSION['filters']['priceto']:0?>" required>
						</label>
					</div>
				<?}?>
				<div class="manufacturer_filter hidden">
					<p class="filter_title">Производители<span class="icon-font">arrow_down</span></p>
					<ul class="filter_block">
						<li>
							<label for="manuf_atlant">
								<input id="manuf_atlant" type="checkbox">Atlant
							</label>
						</li>
						<li>
							<label for="manuf_bosh">
								<input id="manuf_bosh" type="checkbox">Bosh
							</label>
						</li>
						<li>
							<label for="manuf_intertool">
								<input id="manuf_intertool" type="checkbox">Intertool
							</label>
						</li>
					</ul>
				</div>
				<div class="color_filter hidden">
					<p class="filter_title">Цвет<span class="icon-font">arrow_down</span></p>
					<ul class="filter_block">
						<li>
							<label class="color_yellow selected">
								<input id="yellow" type="checkbox">
							</label>
						</li>
						<li>
							<label class="color_red">
								<input id="red" type="checkbox">
							</label>
						</li>
						<li>
							<label class="color_blue">
								<input id="blue" type="checkbox">
							</label>
						</li>
					</ul>
				</div>
				<a href="#" class="reset fright">Сбросить фильтр</a>
				<button type="submit" class="btn-m-green fleft">Применить</button>
			</form>
		</div>
	</div>
<?}?>
-->