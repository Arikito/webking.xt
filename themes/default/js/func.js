// Определение местоположения устройства из которого был осуществлен вход на сайт
function GetLocation(){
	var loc;
	navigator.geolocation.getCurrentPosition(function(position){
		var geocoder = new google.maps.Geocoder,
		latlng = {
				lat: position.coords.latitude,
				lng: position.coords.longitude
			};
		geocoder.geocode({'location': latlng}, function(results, status){
			if(status === google.maps.GeocoderStatus.OK){
				if(results[2]){
					$('.mainUserInf .userlocation').html(results[2].formatted_address);
				}else{
					$('.mainUserInf .userlocation').html(results[6].formatted_address);
				}
			}else{
				$('.mainUserInf .userlocation').addClass('hidden');
			}
		});
	});
	return loc;
}
// Получение корзины
function GetCartAjax(){
	$('#cart > .modal_container').html('');
	ajax('cart', 'GetCartPage', false, 'html').done(function(data){
		$('#cart > .modal_container').html(data);
		removeLoadAnimation('#cart');
		Position($('#cart'));
	});
}
// Получение опроса
function GetQuizAjax(params){
		data = {
			step: params.step === undefined?1:params.step
		};
		if(params.id_user !== undefined){
			data.id_user = params.id_user;
		}
		if(params.target_id_order !== undefined){
			data.target_id_order = params.target_id_order;
		}
	ajax('quiz', 'step', data, 'html').done(function(data){
		$('#quiz').html(data);
		componentHandler.upgradeDom();
		removeLoadAnimation('#quiz');
		Position($('#quiz'));
	});
}
// Получение списка подарков
function GetGiftSelectAjax(){
	$('#gift_select > .modal_container').html('');
	ajax('cart', 'giftSelect', false, 'html').done(function(data){
		$('#gift_select > .modal_container').html(data);
		removeLoadAnimation('#gift_select');
		Position($('#gift_select'));
	});
}
// Получение списка товаров в кабинете
function GetCabProdAjax(id_order, rewrite){
	$('.content').addClass('loading');
	ajax('cabinet', 'GetProdList', {'id_order': id_order, 'rewrite': rewrite}, 'html').done(function(data){
		$('.mdl-tabs__panel > #products').html(data);
		$('.content').removeClass('loading');
	});
}
// Получение списка товаров по каждомк заказу в кабинете совместныйх покупок
function GetCabCoopProdAjax(id_cart, rewrite){
	ajax('cabinet', 'GetProdListForJO', {'id_cart': id_cart, 'rewrite': rewrite}, 'html').done(function(data){
		if($('a[href^="#items_panel_"]').hasClass('getCabCoopProdAjax_js')){
			$('.products_cart_js').html(data);
		}else{
			$('.active_link_to_cart_js').closest('li').find('.products_cart_js').html(data);
			$('.list_in_cart_js').removeClass('active_link_to_cart_js');
		}
	});
}
function ModalDemandChart(id_category){
	ajax('product', 'OpenModalDemandChart', {id_category: id_category}, 'html').done(function(data){
		$('#demand_chart .modal_container').html(data);
		componentHandler.upgradeDom();
		openObject('demand_chart');
	});
}
function ajax(target, action, data, dataType, form_sent){
	if(form_sent){
		data.append('target', target);
		data.append('action', action);
	}else{
		if(typeof(data) == 'object'){
			data.target = target;
			data.action = action;
		}else{
			data = {target: target, action: action};
		}
	}
	dataType = dataType || 'json';
	var ajax = $.ajax({
		url: URL_base+'ajax',
		beforeSend: function(ajax){
			if(ajax_proceed === true){
				// ajax.abort();
			}
			ajax_proceed = true;
		},
		type: 'POST',
		dataType: dataType,
		data: data,
		processData: form_sent?false:true,
		contentType: form_sent?false:'application/x-www-form-urlencoded; charset=UTF-8'
	}).always(function(){
		ajax_proceed = false;
	});
	return ajax;
}
// Change sidebar aside height
function resizeAsideScroll(event) {
	var viewPort = $(window).height(); // высота окна
	var newMainWindow = $('.main').height();
	var main_nav = $('.main_nav').outerHeight();
	var scroll = $(this).scrollTop();
	var pieceOfFooter = (scroll + viewPort) - newMainWindow - 60 + main_nav;
	if((scroll + viewPort) > (CurentMainWindow + 60)){
		$('aside .catalog .second_nav').css('max-height', 'calc(100vh - 60px - '+(pieceOfFooter)+'px');
		$('aside .filters_container').css('max-height', 'calc(100vh - 60px - '+(pieceOfFooter + 43)+'px');
	}else{
		$('aside .catalog .second_nav').css('max-height', 'calc(100vh - '+(main_nav + 60)+'px');
		$('aside .filters_container').css('max-height', 'calc(100vh - '+(main_nav + 60 + 43)+'px');
	}
	return true;
}
// Change product view
function ChangeView(view){
	switch (view) {
		case 'list':
			$('#view_block_js').removeClass().addClass('list_view col-md-12 ajax_loading');
			break;
		case 'block':
			$('#view_block_js').removeClass().addClass('block_view col-md-12 ajax_loading');
			break;
		case 'column':
			$('#view_block_js').removeClass().addClass('column_view col-md-12 ajax_loading');
			break;
	}
	// ListenPhotoHover();
	document.cookie="product_view="+view+"; path=/";
}
// Preview Sliders
function ListenPhotoHover(){
	preview = $('.list_view .preview');
	previewOwl = preview.find('#owl-product_slide_js');
	$('body').on('mouseover', '.list_view .product_photo:not(hovered)', function(){
		if($(this).not('.hovered')){
			showPreview(false);
			$(this).addClass('hovered');
			rebuildPreview($(this));
		}
	}).on('mouseleave', '.list_view .product_photo:not(hovered)', function(e){
		if($('#view_block_js').hasClass('list_view')){
			var mp = mousePos(e),
				obj = $(this);
			if(obj.hasClass('hovered') && (mp.x <= obj.offset().left || mp.x >= obj.offset().left+obj.width() || mp.y <= obj.offset().top || mp.y >= obj.offset().top+obj.height())){
				hidePreview();
				obj.removeClass('hovered');
			}
		}
		return;
	});
	preview.on('mouseleave', function(e){
		if($('#view_block_js').hasClass('list_view')){
			mp = mousePos(e);
			obj = $('.product_photo.hovered');
			if(obj.hasClass('hovered') && (mp.x <= obj.offset().left || mp.x >= obj.offset().left+obj.width() || mp.y <= obj.offset().top || mp.y >= obj.offset().top+obj.height())){
				hidePreview();
				obj.removeClass('hovered');
			}
		}
	});
}
function hidePreview(){
	preview.hide().addClass('ajax_loading');
	if(previewOwl.data('owlCarousel')){
		previewOwl.data('owlCarousel').destroy();
	}
}
function rebuildPreview(obj){
	var position = obj.offset(),
		positionProd = $('#view_block_js').offset(),
		id_product = obj.closest('.card').data('idproduct'),
		// Calculating position of preview window
		viewportWidth = $(window).width(),
		viewportHeight = $(window).height(),
		pos = getScrollWindow(),
		correctionBottom = 0,
		correctionTop = 0,
		marginBottom = 15,
		marginTop = marginBottom+$('header').outerHeight(),
		ovftop = position.top - preview.height()/2 + obj.outerHeight()/2 - marginTop,
		ovfbotton = position.top + preview.height()/2 + obj.outerHeight()/2 + marginBottom;
	if(pos + viewportHeight < ovfbotton){
		correctionBottom = ovfbotton - (pos + viewportHeight);
	}else if(pos > ovftop){
		correctionTop = ovftop - pos;
	}
	preview.css({
		top: position.top - positionProd.top - preview.height()/2 + obj.height()/2 - correctionBottom - correctionTop,
		left: 80,
	});
	if(position.top - preview.offset().top + obj.height()/2 + preview.find('.triangle').height() > preview.height() ){
		preview.css('border-radius', '5px 5px 5px 0').find('.triangle').css({
			top: '50%',
			left: 20,
		});
	}else if(preview.offset().top > position.top + obj.height()/2 - preview.find('.triangle').height()){
		preview.css('border-radius', '0 5px 5px 5px').find('.triangle').css({
			top: '50%',
			left: 20,
		});
	}else{
		preview.css('border-radius', '5px').find('.triangle').css({
			top: position.top - preview.offset().top + obj.height()/2,
			left: -7,
		});
	}
	// Sending ajax for collectiong data about hovered product
	if(obj.hasClass('hovered')){
		ajax('product', 'GetPreview', {'id_product': id_product}, 'html').done(function(data){
			preview.find('.preview_content').html(data);
			componentHandler.upgradeDom();
			showPreview(true);
		});
	}else{
		preview.hide();
	}
}
/** Определение расстояния прокрутки страницы */
function getScrollWindow() {
	var html = document.documentElement;
	var body = document.body;
	var top = html.scrollTop || body && body.scrollTop || 0;
	top -= html.clientTop;
	return top;
}
function changeToTop(pos){
	var totop = $('#toTop');
	if(pos > $('html').height()/8){
		if(totop.hasClass('visible') === false){
			totop.addClass('visible');
		}
	}else{
		if(totop.hasClass('visible')){
			totop.removeClass('visible');
		}
	}
}
function showPreview(ajax){
	if(ajax){
		preview.removeClass('ajax_loading').find('#owl-product_slide_js').owlCarousel({
			center:			true,
			dots:			true,
			items:			1,
			margin:			20,
			nav:			true,
			video:			true,
			videoHeight:	300,
			videoWidth:		300,
			navText: [
				'<svg class="arrow_left"><use xlink:href="images/slider_arrows.svg#arrow_left_tidy"></use></svg>',
				'<svg class="arrow_right"><use xlink:href="images/slider_arrows.svg#arrow_right_tidy"></use></svg>'
			]
		});
	}else{
		preview.show();
	}
}
// get mouse position
function mousePos(e){
	var X = e.pageX; // положения по оси X
	var Y = e.pageY; // положения по оси Y
	return {"x":X, "y":Y};
}
/* Смена отображаемой цены */
function ChangePriceRange(column, manual){
	var a = column != $.cookie('sum_range')?false:true,
		text = '';
	if(manual == 1){
		$.cookie('sum_range', column, {path: '/'});
		$.cookie('manual', 1, { path: '/'});
		$('li.sum_range').removeClass('active');
		$('li.sum_range_'+column).addClass('active');
		if (column == $.cookie('sum_range')) {
			switch (column) {
				case 3:
					text = 'Без скидки!';
					break;
				case 2:
					text = 'При заказе от '+ columnLimits['2'] +' грн.';
					break;
				case 1:
					text = 'При заказе от '+ columnLimits['1'] +' грн.';
					break;
				case 0:
					text = 'При заказе от '+ columnLimits['0'] +' грн.';
					break;
			}
			$('.order_balance').text(text);
		}
	}else if($.cookie('manual') != 1){
		$.cookie('sum_range', column, {path: '/'});
		$('li.sum_range').removeClass('active');
		$('li.sum_range_'+column).addClass('active');
		column = column > 0?column-1:0;
	}else{
		column = $.cookie('sum_range');
	}
	addLoadAnimation('.order_balance');
	addLoadAnimation('#button-cart1');
	ajax('cart', 'GetCart').done(function(data){
		removeLoadAnimation('.order_balance');
		removeLoadAnimation('#button-cart1');
		currentSum = data.products_sum[3];
		newSum = columnLimits[column] - currentSum;
		// console.log(column);
		// console.log(data.cart_column);
		// var fafa = parseInt($.cookie('sum_range'));
		if($.cookie('manual') != 1){
			switch (data.cart_column) {
				case 3:
					text = 'Без скидки!';
					break;
				case 2:
					text = 'При заказе от '+ columnLimits['2'] +' грн.';
					break;
				case 1:
					text = 'При заказе от '+ columnLimits['1'] +' грн.';
					break;
				case 0:
					text = 'При заказе от '+ columnLimits['0'] +' грн.';
					break;
			}
			$('.order_balance').text(text);
		}
		// if(newSum < 0){
		// 	text = 'Заказано достаточно!';
		// }else{
		// 	if($.cookie('manual') == 1){
		// 		text = 'Дозаказать еще на '+newSum.toFixed(2).toString().replace('.',',')+' грн.';
		// 	}else{
		// 		text = 'До следующей скидки '+newSum.toFixed(2).toString().replace('.',',')+' грн.';
		// 	}
		// }
		$(':not(#cart) .product_buy').each(function(){ // отображение оптовой или малооптовой (розничной) цены товара в каталоге
			var minQty = parseInt($(this).find('.minQty').val()),
				currentQty = parseInt($(this).find('.qty_js').val()),
				price = parseFloat($(this).find('.price'+(currentQty >= minQty?'Opt':'Mopt')+column).val()).toFixed(2).toString().replace('.',','),
				base_price = $(this).find('.basePrice'+(currentQty >= minQty?'Opt':'Mopt')+column).val();
			if(currentQty >= minQty){
				$(this).find('.priceMoptInf').addClass('hidden');
			}else{
				$(this).find('.priceMoptInf').removeClass('hidden');
			}
			$(this).find('.price').html(price);
			$(this).find('.base_price').html(base_price);
		});
		// Подсветка цен товаров для привлечения внимания
		if(a === true){
			setTimeout(function(){
				$('.product_buy .product_price *').stop(true,true).css({
					color: '#FF5722'
				}).delay(1000).animate({
					color: '#444444'
				}, 3000);

			}, 300);
		}

		if($('#cart').hasClass('opened')){
			var min_sum_order = parseInt($('#cart .cart_buttons .min_sum_order').val());
			var current_user = parseInt($('#cart .cart_buttons .current_user').val());
			if (data.products_sum[3] < min_sum_order && current_user != 4) {
				$('#button-cart1 button').addClass('hidden');
				$('#button-cart1 p').removeClass('hidden');
			}else{
				$('#button-cart1 button').removeClass('hidden');
				$('#button-cart1 p').addClass('hidden');
			}
		}
	});
}
function openObject(id, params){
	switch(id){
		case 'gift_select':
			GetGiftSelectAjax(params);
			break;
		case 'cart':
			GetCartAjax(params);
			break;
		case 'quiz':
			if(params === undefined){
				params = {reload: false};
			}
			GetQuizAjax(params);
			break;
	}
	if(params === undefined || params.reload !== true){
		var object = $('#'+id),
			type = object.data('type');
		if($('html').hasClass('active_bg')){
			$('.opened:not([id="'+object.attr('id')+'"])').each(function(index, el) {
				closeObject($(this).attr('id'));
			});
		}
		if(object.hasClass('opened') && type != "search"){
			closeObject(object.attr('id'));
			DeactivateBG();
		}else{
			if(id=="cart"){
				addLoadAnimation('#'+id);
			}
			if(id == 'phone_menu'){
				$('[data-name="phone_menu"] i').text('close');
			}
			if(type == 'modal'){
				object.find('.modal_container').css({
					'max-height': $(window)*0.8
				});
				Position(object.addClass('opened'));
			}else{
				object.addClass('opened');
			}
			ActivateBG();
		}
	}
}
function closeObject(id){
	if(id === undefined){
		$('.opened').each(function(index, el){
			closeObject($(this).attr('id'));
		});
	}else{
		$('#'+id).removeClass('opened');
		if(id == 'phone_menu'){
			$('[data-name="phone_menu"] i').text('menu');
		}
	}
	DeactivateBG();
}
function Position(object){
	object.css({
		'top': ($(window).height() + 52 - object.outerHeight())/2,
		'left': ($(window).width() - object.outerWidth())/2
	});
}
//Активация подложки
function ActivateBG(){
	$('html').addClass('active_bg');
}
//Деактивация подложки
function DeactivateBG(){
	$('html').removeClass('active_bg');
}
//Закрытие Панели мобильного меню
function closePanel(){
	$('.menu').html('menu');
	$('.panel').slideUp();
}
//Установка выбранного рейтинга
function changestars(rating){
	$('.set_rating').each(function(){
		$(this).next('i').text(parseInt($(this).val()) <= parseInt(rating)?'star':'star_border');
	});
}
/** Валидация пароля **/
function ValidatePass(passwd, el){
	var protect = 0,
		result,
		parent = el.closest('.forPassStrengthContainer_js'),
		small = new RegExp("^(?=.*[a-zа-я]).*$", "g");
	if(small.test(passwd)){
		protect++;
	}
	var big = new RegExp("^(?=.*[A-ZА-Я]).*$", "g");
	if(big.test(passwd)){
		protect++;
	}
	var numb = new RegExp("^(?=.*[0-9]).*$", "g");
	if(numb.test(passwd)){
		protect++;
	}
	var vv = new RegExp("^(?=.*[!,@,#,$,%,^,&,*,?,_,~,-,=]).*$", "g");
	if(vv.test(passwd)){
		protect++;
	}
	if(protect == 1){
		$('#passwd + .mdl-textfield__error').empty();
		parent.find('.ps_lvl_js').addClass('bad').removeClass('better').removeClass('ok').removeClass('best');
		result = false;
	}
	if(protect == 2){
		parent.find('.ps_lvl_js').addClass('better').removeClass('ok').removeClass('best');
		result = false;
	}
	if(protect == 3){
		parent.find('.ps_lvl_js').addClass('ok').removeClass('best');
		result = false;
	}
	if(protect == 4){
		parent.find('.ps_lvl_js').addClass('best');
		result = false;
	}
	if(passwd.length < 1){
		$('#passwd + .mdl-textfield__error').empty();
		parent.find('.ps_lvl_js').removeClass('bad').removeClass('better').removeClass('ok').removeClass('best');
		$('[name="new_passwd"], [name="passwd"]').closest('.mdl-textfield').find('.mdl-textfield__error').text('Введите пароль');
	}else if(passwd.length >= 1 && passwd.length < 4) {
		$('#passwd + .mdl-textfield__error').empty();
		$('[name="new_passwd"], [name="passwd"]').closest('.mdl-textfield').addClass('is-invalid').find('.mdl-textfield__error').text('Пароль слишком короткий');
	}
	return result;
}
/** Валидация подтверждения пароля **/
function ValidatePassConfirm(passwd, passconfirm){
	if(passconfirm !== passwd || !passconfirm){
		$('[name="passwdconfirm"]').closest('.mdl-textfield').addClass('is-invalid').find('.mdl-textfield__error').text('Пароли не совпадают').css({'visibility': 'visible', 'color': '#D50000'});
		$('.verification_btn').attr('disabled', 'disabled');
	}else{
		$('[name="passwdconfirm"]').closest('.mdl-textfield').removeClass('is-invalid').find('.mdl-textfield__error').text('Пароли совпали').css({'visibility': 'visible', 'color': '#018b06'});
		if($('[name="passwdconfirm"]').val().length >= 4) $('.verification_btn').removeAttr('disabled');
		return false;
	}
}
/** Валидация email **/
function ValidateEmail(fields, type){
	ajax('auth', 'register', fields).done(function(data){
		removeLoadAnimation('#registration');
		return data;
	});
}
/** Валидация имени **/
function ValidateName(name){
	if(name.length < 3){
		$('#name ~ .mdl-textfield__error').empty();
		$('#name').closest('.mdl-textfield ').addClass('is-invalid');
		if(name.length === 0){
			$('#name ~ .mdl-textfield__error').append('Введите имя');
		}else{
			$('#name ~ .mdl-textfield__error').append('Имя слишком короткое');
		}
	}else{
		$('#name ~ .mdl-textfield__error').empty();
		return false;
	}
}
/** Завершить валидацию после проверки email */
function CompleteValidation(name, email, passwd, passconfirm){
	var fin = 0,
		res = false;
	res = ValidateName(name);
	if(res){
		$('#regs .mdl-textfield__error').closest('#name .mdl-textfield').text(res);
		fin++;
	}
	if(email){
		$('#regs .mdl-textfield__error').closest('#email .mdl-textfield').text(email);
		fin++;
	}
	res = ValidatePass(passwd);
	if(res){
		$('#regs .mdl-textfield__error').closest('#passwd .mdl-textfield').text(res);
		fin++;
	}
	res = ValidatePassConfirm(passwd, passconfirm);
	if(res){
		$('#regs .mdl-textfield__error').closest('#passwdconfirm .mdl-textfield').text(res);
		fin++;
	}
	if(fin > 0){
		return false;
	}
	return true;
}
function moveObjects(){
	var modals = $('div:not(.modals) [data-type="modal"]');
	modals.each(function(key, value){
		$('.modals').append(value);
	});
	var panels = $('div:not(.panels) [data-type="panel"]');
	panels.each(function(key, value){
		$('.panels').append(value);
	});
}
// Удаление товара из ассортимента поставщика в кабинете
function DelFromAssort(obj){
	var id = obj.closest('.products_list_item').data('id-product');
	ajax('product', 'DelFromAssort', {id_product: id}).done(function(){
		obj.closest('.products_list_item').slideUp();
	});
}
// Добавление или обновление товара в ассортименте
function toAssort(id, opt, nacen, comment){
	var inusd = $('.inusd'+id).prop('checked'),
		currency_rate = $('#currency_rate').val(),
		mode = opt == 1?'opt':'mopt',
		a = parseFloat($('#price_'+mode+'_otpusk_'+id).val().replace(',','.')),
		b = parseFloat($('#price_'+mode+'_otpusk_'+id).val().replace(',','.')),
		c = parseFloat($('#product_limit_mopt_'+id).val()),
		active = 0;
	if($('#balance_info-'+id+' input').is(':checked')){
		active = 1;
	}
	ajax('supplier', 'updateAssort', {mode: mode, id_product: id, price: a, active: active, comment: comment, inusd: inusd === false?0:1});
	// if(inusd === true){
	// 	a = a*currency_rate;
	// 	b = b*currency_rate;
	// }
	// $('#product_limit_mopt_'+id).val(c);
	// if(c > 0){
	// 	if(opt){
	// 		var po = parseFloat($('#price_opt_'+id).val()),
	// 			pom = Number(po - po*parseFloat($('#price_delta_otpusk').val())*0.01).toFixed(2);
	// 		if(po !== 0 && a > pom){
	// 			alert('Предлагаемая Вами крупнооптовая цена не позволяет продавать данный товар на сайте.');
	// 		}
	// 		var pop = Number(po + po*parseFloat($('#price_delta_recom').val())*0.01).toFixed(2);
	// 		pom = Number(po - po*parseFloat($('#price_delta_recom').val())*0.01).toFixed(2);
	// 		if(po !== 0 && (b > pop || b < pom)){
	// 			alert('Предлагаемая Вами среднерыночная цена значительно отличается от цены сайта (более '+parseFloat($('#price_delta_recom').val())+'%).');
	// 		}
	// 	}else{
	// 		var pm = parseFloat($('#price_mopt_'+id).val()),
	// 			pmm = Number(pm - pm*parseFloat($('#price_delta_otpusk').val())*0.01).toFixed(2);
	// 		if(pm !== 0 && a > pmm){
	// 			alert('Предлагаемая Вами мелкооптовая цена не позволяет продавать данный товар на сайте.');
	// 		}
	// 		var pmp = Number(pm + pm*parseFloat($('#price_delta_recom').val())*0.01).toFixed(2);
	// 		pmm = Number(pm - pm*parseFloat($('#price_delta_recom').val())*0.01).toFixed(2);
	// 		if(pm !== 0 && (b > pmp || b < pmm)){
	// 			alert('Предлагаемая Вами среднерыночная цена значительно отличается от цены сайта (более '+parseFloat($('#price_delta_recom').val())+'%).');
	// 		}
	// 	}
	// 	active = 1;
	// 	if(parseFloat($('#price_opt_otpusk_'+id).val()) === 0 || parseFloat($('#price_mopt_otpusk_'+id).val()) === 0){
	// 		active = 0;
	// 		alert('Необходимо заполнить цены.');
	// 	}
	// }
	// if(active == 1){
	// 	$('#tr_opt_'+id+', #tr_mopt_'+id).removeClass('notavailable notprice').addClass('available');
	// }else{
	// 	$('#tr_opt_'+id+', #tr_mopt_'+id).removeClass('available').addClass('notavailable');
	// 	$('#product_limit_opt_'+id+', #product_limit_mopt_'+id).val(0);
	// }
	// if(a <= 0 || b <= 0){
	// 	$('#tr_opt_'+id+', #tr_mopt_'+id).removeClass('available').addClass('notavailable notprice');
	// }
	// if(a < 0){
	// 	a = 0;
	// 	$('#price_opt_otpusk_'+id).val(a);
	// }
	// ajax('supplier', 'updateAssort', {mode: mode, id_product: id, price: a, active: active, comment: comment, inusd: inusd === false?0:1});
}
/*Добавить/Удалить товар а ассортименте у конкретного поставщика*/
function AddDelProductAssortiment(obj, id){
	ajax('product', obj.checked?'AddToAssort':'DelFromAssort', {id_product: id});
}
// Установить куки
function setCookie(name, value){
	var valueEscaped = escape(value),
		expiresDate = new Date();
	if(valueEscaped.length <= 4000){
		expiresDate.setTime(expiresDate.getTime() + 365 * 24 * 60 * 60 * 1000); // срок - 1 год
		var expires = expiresDate.toGMTString();
		document.cookie = name+'='+valueEscaped+'; path=/; expires='+expires+";";
	}
}
// Получить куки
function getCookie(name){
	var prefix = name+'=',
		cookieStartIndex = document.cookie.indexOf(prefix);
	if(cookieStartIndex == -1){
		return null;
	}
	var cookieEndIndex = document.cookie.indexOf(';', cookieStartIndex+prefix.length);
	if(cookieEndIndex == -1){
		cookieEndIndex = document.cookie.length;
	}
	return unescape(document.cookie.substring(cookieStartIndex+prefix.length, cookieEndIndex));
}
// Включение анимации ожидания отклика от сервера
function addLoadAnimation(obj){
	if(typeof(obj) == 'object' && obj.has('.loadBlock').length === 0){
		obj.append('<div class="loadBlock"><div class="mdl-spinner mdl-spinner--single-color mdl-js-spinner is-active loadAnimation"></div></div>');
	}else if($(obj).has('.loadBlock').length === 0){
		$(obj).append('<div class="loadBlock"><div class="mdl-spinner mdl-spinner--single-color mdl-js-spinner is-active loadAnimation"></div></div>');
	}
	componentHandler.upgradeDom();
}
// Отключение анимации ожидания отклика от сервера
function removeLoadAnimation(obj) {
	if(typeof(obj) == 'object' && obj.has('.loadBlock').length > 0){
		obj.find('.loadBlock').remove();
	}else if($(obj).has('.loadBlock').length > 0){
		$(obj).find('.loadBlock').remove();
	}
	componentHandler.upgradeDom();
}

// Добавление/Удаление товара в избранное
function toggleFavorite(id_product, targetEl, is_prod){

}
//Добавление товара в избранное
function AddFavorite(id_product, targetEl, is_prod){
	console.log(is_prod);
	console.log(targetEl);

	ajax('product', 'add_favorite', {id_product: id_product}).done(function(data){
		var res = {};
		if(data.answer == 'login'){
			openObject('auth');
			removeLoadAnimation('#auth');
		}else if(data.answer == 'already'){
			res = {message: 'Товар уже находится в избранном'};
		}else{
			if(data.answer == 'ok'){
				$('.userChoiceFav').text('('+data.fav_count+')');
				res = {message: 'Товар добавлен в избранное'};
				// if(is_prod){
				// 	targetEl.find('.favorite_js').empty().text('favorite').removeClass('notfavorite').addClass('isfavorite');
				// 	targetEl.find('span').empty().text('В избранном');
				// }else{
					targetEl.attr('title', 'Убрать из избранного').find('i').html('favorite');
					// targetEl.empty().text('favorite').removeClass('notfavorite').addClass('isfavorite');
				// 	targetEl.next().empty().html('Товар уже <br> в избранном');
				// }
			}else{
				if(data.answer == 'wrong user group'){
					res = {message: 'Данный функционал доступен только для клиентов'};
				}
			}
		}
		var snackbarContainer = document.querySelector('#snackbar');
		snackbarContainer.MaterialSnackbar.showSnackbar(res);
	}).fail(function(data){
		alert('Error');
	});
	return false;
}
//Удаление товара из избранных
function RemoveFavorite(id_product, targetEl, is_prod){
	ajax('product', 'del_favorite', {id_product: id_product}).done(function(data){
		var res = {};
		if(data.answer == 'login'){
			openObject('auth');
			removeLoadAnimation('#auth');
		}else{
			if(data.answer == 'ok'){
				$('.userChoiceFav').text('('+data.fav_count+')');
				res = {message: 'Товар удален из избранного'};
				// if(is_prod){
					// targetEl.find('.favorite_js').empty().text('favorite_border').addClass('notfavorite').removeClass('isfavorite');
					// targetEl.find('span').empty().text('В избранное');
				// }else{
					targetEl.attr('title', 'В избранное').find('i').html('favorite_border');//.addClass('notfavorite').removeClass('isfavorite');
					// targetEl.next().empty().html('Добавить товар <br> в избранное');
				// }
			}else{
				if(data.answer == 'wrong user group'){
					res = {message: 'Данный функционал доступен только для клиентов'};
				}
			}
		}
		var snackbarContainer = document.querySelector('#snackbar');
		snackbarContainer.MaterialSnackbar.showSnackbar(res);
	}).fail(function(data){
		alert('Error');
	});
	return false;
}
//Добавление товара в список ожидания
function AddInWaitingList(id_product, id_user, email, targetClass){
	var data = {id_product: id_product, id_user: id_user, email: email};
	ajax('product', 'add_in_waitinglist', data).done(function(data){
		var res = {};
		if(data.answer == 'login'){
			openObject('auth');
			removeLoadAnimation('#auth');
		}else if(data.answer == 'already'){
			res = {message: 'Товар уже в списке ожидания'};
		}else{
			if(data.answer == 'ok'){
				$('.userChoiceWait').text('('+data.fav_count+')');
				res = {message: 'Товар добавлен в список ожидания'};
				targetClass.attr('title', 'Не следить за ценой');
				// targetClass.closest('.fortrending').next().empty().html('Товар в <br> списке ожидания');
				// targetClass.closest('.fortrending').find('.mdl-tooltip').html('Товар в <br> списке ожидания');
				// $('#specCont').find('.fortrending_info_tooltip').html('Товар в <br> списке ожидания');
			}else{
				if(data.answer == 'wrong user group'){
					res = {message: 'Данный функционал доступен только для клиентов'};
				}
			}
		}
		var snackbarContainer = document.querySelector('#snackbar');
		snackbarContainer.MaterialSnackbar.showSnackbar(res);
	}).fail(function(data){
		var res = {message: 'Данный функционал доступен только для клиентов'},
			snackbarContainer = document.querySelector('#snackbar');
		snackbarContainer.MaterialSnackbar.showSnackbar(res);
	});
	return false;
}
//Удаление товара из списка ожидания
function RemoveFromWaitingList(id_product, id_user, email, targetClass){
	var data = {id_product: id_product, id_user: id_user, email: email};
	ajax('product', 'del_from_waitinglist', data).done(function(data){
		var res = {};
		if(data.answer == 'login'){
			openObject('auth');
			removeLoadAnimation('#auth');
		}else{
			if(data.answer == 'ok'){
				$('.userChoiceWait').text('('+data.fav_count+')');
				res = {message: 'Товар удален из списка ожидания'};
				targetClass.attr('title', 'Следить за ценой');
				// targetClass.closest('.fortrending').next().empty().html('Следить за ценой');
				// targetClass.closest('.fortrending').find('.mdl-tooltip').html('Следить за ценой');
				// $('#specCont').find('.fortrending_info_tooltip').html('Следить за ценой');
			}else{
				if(data.answer == 'wrong user group'){
					res = {message: 'Данный функционал доступен только для клиентов'};
				}
			}
		}
		var snackbarContainer = document.querySelector('#snackbar');
		snackbarContainer.MaterialSnackbar.showSnackbar(res);
	}).fail(function(data){
		var res = {message: 'Данный функционал доступен только для клиентов'},
			snackbarContainer = document.querySelector('#snackbar');
		snackbarContainer.MaterialSnackbar.showSnackbar(res);
	});
	return false;
}
function segmentOpen(id){
	$('[data-id="'+id+'"]').each(function(){
		if($(this).find('ul').length === 0){
			addLoadAnimation('.catalog');
			ajax('segment', 'segmid', {idsegment: id}, 'html').done(function(data){
				$('.second_nav li').removeClass('active');
				$('[data-id="'+id+'"]').append(data);
				$('[data-id="'+id+'"]').find('.link_wrapp').find('span').addClass('more_cat');
				$('[data-id="'+id+'"]').find('ul').find('li').removeClass('active').find('ul').stop(true, true).slideUp();
				var lvl = $('[data-id="'+id+'"]').find('ul').data('lvl'),
					parent = $('[data-id="'+id+'"]'),
					parent_active = parent.hasClass('active');
				if(!parent_active){
					parent.addClass('active').find('> ul').stop(true, true).slideDown();
				}
				removeLoadAnimation('.catalog');
			});
		}
	});
}
function addressSelect(obj){
	var parent = obj.closest('form'),
		address = obj.val();
	ajax('location', 'addressSelect', {address: address}, 'html').done(function(response){
		parent.find('.address_preview').html(response);
	});
}
function regionSelect(obj){
	var parent = obj.closest('form'),
		region = obj.val();
	addLoadAnimation(parent);
	if(region !== ''){
		ajax('location', 'regionSelect', {region: region}, 'html').done(function(data){
			parent.find('select:not(#region) option').remove();
			parent.find('#city').html(data).prop('disabled', false);
			parent.find('#delivery_service, #insurance, #delivery_department').closest('div.mdl-cell').addClass('hidden');
			removeLoadAnimation(parent);
		});
	}else{
		parent.find('#city').html('').prop('disabled', true);
		removeLoadAnimation(parent);
	}
}
function citySelect(obj){
	var parent = obj.closest('form'),
		city = obj.val(),
		region = parent.find('#region').val();
	addLoadAnimation(parent);
	if(city !== '' && region !== ''){
		ajax('location', 'citySelect', {city: city, region: region}, 'html').done(function(data){
			parent.find('select:not(#region, #city) option').remove();
			parent.find('#id_delivery').html(data).prop('disabled', false);
			parent.find('#delivery_service, #insurance, #delivery_department').closest('div.mdl-cell').addClass('hidden');
			removeLoadAnimation(parent);
		});
	}else{
		removeLoadAnimation(parent);
	}
}
function deliverySelect(obj){
	var parent = obj.closest('form'),
		id_delivery = obj.val(),
		city = parent.find('#city').val(),
		region = parent.find('#region').val();
	addLoadAnimation(parent);
	ajax('location', 'deliverySelect', {city: city, region: region, id_delivery: id_delivery}, 'html').done(function(data){
		parent.find('#id_delivery_service option').remove();
		parent.find('#id_delivery_service').html(data).prop('required', true).prop('disabled', false);
		parent.find('.id_delivery_service').closest('div.mdl-cell').removeClass('hidden');
		switch(id_delivery){
			case '1':
				parent.find('.delivery_department').closest('div.mdl-cell').removeClass('hidden');
				parent.find('.address').closest('div.mdl-cell').addClass('hidden');
			break;
			case '2':
				parent.find('.delivery_department').closest('div.mdl-cell').addClass('hidden');
				parent.find('#address').prop('required', true).prop('disabled', false).closest('div.mdl-cell').removeClass('hidden');
			break;
		}
		removeLoadAnimation(parent);
	});
}

function deliveryServiceSelect(obj){
	var parent = obj.closest('form'),
		region = parent.find('#region').val(),
		city = parent.find('#city').val(),
		id_delivery = parent.find('#id_delivery').val(),
		shipping_comp = obj.val(),
		ref = obj.find('option:selected').data('ref');
	addLoadAnimation(parent);
	if(id_delivery == 1){
		ajax('location', 'deliveryServiceSelect', {city: city, region: region, shipping_comp: shipping_comp, ref: ref, id_delivery: id_delivery}, 'html').done(function(data){
			parent.find('#delivery_department option').remove();
			parent.find('#delivery_department').html(data).prop({required: true, disabled: false});
			parent.find('.delivery_department').closest('div.mdl-cell').removeClass('hidden');
			removeLoadAnimation(parent);
		});
	}else{
		removeLoadAnimation(parent);
	}
}
function UpdateProductsList(page, arr){
	ajax('products', 'getmoreproducts', arr, 'html').done(function(data){
		removeLoadAnimation('.products');
		page.find('.products').html(data);
		componentHandler.upgradeDom();
		$("img.lazy").lazyload({
			effect : "fadeIn"
		});
		resizeAsideScroll('show_more');
	});
}
function SortProductsList(obj){
	location.href = obj.val();
}
// Блок кода для выделения ошибок на канвасе
function GetScreenshot(){
	var detachEl = $('.err_msg_as_js').detach(),
		detachSnack = $('#snackbar').detach();
	if($(document).outerWidth() < 450){
		$('.waiting_block_for_img_canvas_js').css('top', '0');
		$('#header_js').css('top', '60px');
	}else{
		$('.waiting_block_for_img_canvas_js').css('top', '15px');
	}
	html2canvas(document.body, {
		onrendered: function(canvas){
			canvas.id = 'canvasImg';
			var url = canvas.toDataURL("image/jpeg");
			// window.location = canvas.toDataURL();
			$('.err_msg_as_wrap_js').css('display', 'none').append(detachEl);
			$('.modals').append(detachSnack);
			// Находим элемент <img>
			var imageCopy = document.getElementById("savedImageCopy");
			// Отображаем данные холста в элементе <img>
			imageCopy.src = canvas.toDataURL();
			// Показываем элемент <div>, делая изображение видимым
			var imageContainer = document.getElementById("savedCopyContainer");
			imageContainer.style.display = "block";
			$('.err_msg_as_wrap_js').css('display', 'block');
			$('.waiting_block_for_img_canvas_js').css('top', '-60px');
			if($(document).outerWidth() < 450){
				$('#header_js').css('top', '0');
				$('.err_msg_as_form_js .mdl-textfield').css('height', 'calc(100vh - 345px)');
			}
			$('.err_msg_as_js').addClass('shown').css('top', '0');
			$('.err_msg_as_form_js').find('textarea').focus();
			$('#err_canvas').attr({'width':'20','height':'20'});
			clear_canvas();
		}
	});
}
var canvas, context, canvaso, contexto, tool,
	tool_default = 'line'; // По умолчанию линия - инструмент по умолчанию
function init(color, tool_type){
	canvaso = document.getElementById('err_canvas');
	if(!canvaso){
		alert('Ошибка! Canvas элемент не найден!');
		return;
	}
	if(!canvaso.getContext){
		alert('Ошибка! canvas.getContext не найден!');
		return;
	}
	contexto = canvaso.getContext('2d');
	if(!contexto){
		alert('Ошибка! Не могу получить getContext!');
		return;
	}
	var container = canvaso.parentNode;
	canvas = document.createElement('canvas');
	if(!canvas){
		alert('Ошибка! Не могу создать canvas элемент!');
		return;
	}
	canvas.id = 'imageTemp';
	canvas.width = canvaso.width;
	canvas.height = canvaso.height;
	container.appendChild(canvas);
	context = canvas.getContext('2d');
	context.strokeStyle = color;
	// Получаем инструмент
	switch(tool_type){
		case 'rect':
			tool = new tools['rect']();
			break;
		case 'fillrect':
			tool = new tools['fillrect']();
			break;
		case 'pencil':
			tool = new tools['pencil']();
			context.lineWidth = 4;
			break;
		case 'eraser':
			tool = new tools['eraser']();
			context.lineWidth = 20;
			break;
		default:
			break;
	}
	canvas.addEventListener('mousedown', ev_canvas, false);
	canvas.addEventListener('mousemove', ev_canvas, false);
	canvas.addEventListener('mouseup', ev_canvas, false);
}
function ev_canvas(ev){
	ev._x = ev.offsetX;
	ev._y = ev.offsetY;
	var func = tool[ev.type];
	if(tool[ev.type]){
		func(ev);
	}
}
// Эта функция вызывается каждый раз после того, как пользователь
// завершит рисование. Она очищает imageTemp.
function img_update(){
	contexto.drawImage(canvas, 0, 0);
	context.clearRect(0, 0, canvas.width, canvas.height);
}
function clear_canvas(){
	contexto.clearRect(0, 0, canvaso.width, canvaso.height);
	$('#canvas_mark_wrapper').find('canvas:not(#err_canvas)').remove();
}
// Содержит реализацию каждого инструмента рисования
var tools = {};
// Карандаш
tools.pencil = function(){
	var tool = this;
	this.started = false;

	// Рисуем карандашом
	this.mousedown = function(ev){
		context.beginPath();
		context.moveTo(ev._x, ev._y);
		tool.started = true;
	};
	this.mousemove = function(ev){
		if(tool.started){
			context.lineTo(ev._x, ev._y);
			context.stroke();
		}
	};
	this.mouseup = function(ev){
		if(tool.started){
			tool.mousemove(ev);
			tool.started = false;
			img_update();
		}
	};
};
// Прямоугольник
tools.rect = function(){
	var tool = this;
	this.started = false;
	this.mousedown = function(ev){
		tool.started = true;
		tool.x0 = ev._x;
		tool.y0 = ev._y;
	};
	this.mousemove = function(ev){
		if(!tool.started){
			return;
		}
		var x = Math.min(ev._x,  tool.x0),
			y = Math.min(ev._y,  tool.y0),
			w = Math.abs(ev._x - tool.x0),
			h = Math.abs(ev._y - tool.y0);
		context.clearRect(0, 0, canvas.width, canvas.height);
		if(!w || !h){
			return;
		}
		context.lineWidth = 3;
		context.strokeRect(x, y, w, h);
	};
	this.mouseup = function(ev){
		if(tool.started){
			tool.mousemove(ev);
			tool.started = false;
			img_update();
		}
	};
};
// Прямоугольник закрашенный
tools.fillrect = function(){
	var tool = this;
	this.started = false;
	this.mousedown = function(ev){
		tool.started = true;
		tool.x0 = ev._x;
		tool.y0 = ev._y;
	};
	this.mousemove = function(ev){
		if(!tool.started){
			return;
		}
		var x = Math.min(ev._x,  tool.x0),
			y = Math.min(ev._y,  tool.y0),
			w = Math.abs(ev._x - tool.x0),
			h = Math.abs(ev._y - tool.y0);
		context.clearRect(0, 0, canvas.width, canvas.height);
		if(!w || !h){
			return;
		}
		context.fillRect(x, y, w, h);
	};
	this.mouseup = function(ev){
		if(tool.started){
			tool.mousemove(ev);
			tool.started = false;
			img_update();
		}
	};
};
// Линия
tools.line = function(){
	var tool = this;
	this.started = false;
	this.mousedown = function(ev){
		tool.started = true;
		tool.x0 = ev._x;
		tool.y0 = ev._y;
	};
	this.mousemove = function(ev){
		if(!tool.started){
			return;
		}
		context.clearRect(0, 0, canvas.width, canvas.height);
		context.beginPath();
		context.moveTo(tool.x0, tool.y0);
		context.lineTo(ev._x,   ev._y);
		context.stroke();
		context.closePath();
	};
	this.mouseup = function(ev){
		if(tool.started){
			tool.mousemove(ev);
			tool.started = false;
			img_update();
		}
	};
};
// Ластик
tools.eraser = function(){
	var tool = this;
	this.started = false;
	this.mousedown = function(ev){
		context.beginPath();
		context.moveTo(ev._x, ev._y);
		tool.started = true;
	};
	this.mousemove = function(ev){
		if(tool.started){
			context.lineTo(ev._x, ev._y);
			context.stroke();
		}
	};
	this.mouseup = function(ev){
		if(tool.started){
			tool.mousemove(ev);
			tool.started = false;
			img_update();
		}
	};
};


// Отслеживание изменений рангов на графике спроса в разделах товаров.
function СhangeValue(id){
	// $('#'+id).data('prevnum', $('input').val().closest('.slider_wrap').find('.range_num').text($('#'+id).val());

	  if($('#'+id).data('prevnum') < $('#'+id).val() ){
		// $('#'+id).closest('.slider_wrap').find('.range_num').css('bottom', $('#'+id).val()*10 +'%');
		$('#'+id).closest('.slider_wrap').find('.range_num').css('bottom', '+=10%');
	  }else if($('#'+id).data('prevnum') > $('#'+id).val()){
		// $('#'+id).closest('.slider_wrap').find('.range_num').css('bottom', $('#'+id).val()*10 +'%');
		$('#'+id).closest('.slider_wrap').find('.range_num').css('bottom', '-=10%');
	  }


	$('#'+id).data('prevnum', $('#'+id).val()).closest('.slider_wrap').find('.range_num').text($('#'+id).val());

// 	  $('input').data('prevnum', $('input').val());
// 	  $('.range_num_wrap p').text($('input').val());
}

/* Функция отправки отзыва о продукте */
function onCommentSubmit(){
	alert("Коментарий о позиции будет отображен на сайте после премодерации.");
}

function GetOriginalPhoto(photo){
	var img_src = photo.find('img').attr('data-original-photo'),
		img_alt = photo.find('img').attr('alt');
	$('#big_photo img').attr({src: img_src, alt: img_alt});
	setTimeout(function(){
		openObject('big_photo');
		$('#big_photo img').css('height', $('#big_photo[data-type="modal"]').outerHeight() + "px");
	}, 100);
}

// Функция добавления примечания в карточку товара
// Параметр element - объект, по которому был осуществлен клик.
// Класс out_card_js - метка (на странице продукта), что кнопка находится не в блоке с классом card, а не в блоке с классом specCont_js.
function AddNoteArea(element){
	var parent;
	if (element.hasClass('out_card_js')) {
		parent = element.closest('.specCont_js').find('.note');
	}else{
		parent = element.closest('.card').find('.note');
	}
	parent.removeClass('hidden');
	if(parent.hasClass('note_control')){
		parent.addClass('activeNoteArea');
	}
}

// функция для валидации mdl-input'ов с информацией о пользователе
//
// Для использования данной функции, input'у, который требует валидации надо дать:
// 		класс "input_validator_js";
// 		атрибут "data-input-validate" со строковым значением, которым может быть один из ключей ниже представленного switch-case.
//
// В качестве аргумента параметра el метод ожидает объект (input), который потерял фокус.
// Переменные:
// 		string str - значение атрибута "value" el;
// 		string type - значение атрибута "data-input-validate" el;
// 		object parent - контейнер, который содержит в себе input
//		mdl-textfield, is-dirty, is-invalid - классы mdl-библиотеки
function userInfoValidator(el){
	var str = el.val(),
		type = el.data('input-validate'),
		parent = el.closest('div.mdl-textfield'),
		temp;
	var name_reg = /^[\'А-Яа-я-ЇїІіЁё ]+$|^[\'A-Za-z- ]+$/gi,
		email_reg = /(^([\w\.]+)@([\w]+)\.([\w]+)$)|(^$)/gi,
		phone_reg = /\+38 \(\d{3}\) \d{3}-\d{2}-\d{2}/,
		day_reg = /^(0?[1-9])$|^([1-2]\d)$|^(3[0-1])$/,
		year_reg = /^(19|20)\d{2}$/;

	switch(type){
		case 'name':
			temp = name_reg.test(str);
		break;
		case 'email':
			temp = email_reg.test(str);
		break;
		case 'phone':
			str.replace(/\D/g, "").length > 3 ? parent.addClass('is-dirty') : parent.removeClass('is-dirty');
			temp = phone_reg.test(str);
		break;
		case 'day':
			temp = day_reg.test(str);
		break;
		case 'year':
			temp = year_reg.test(str);
		break;
		case 'file':
			temp = str != '' ? true : false;
		break;
	}

	return temp ? parent.removeClass('is-invalid') : parent.addClass('is-invalid');
}
