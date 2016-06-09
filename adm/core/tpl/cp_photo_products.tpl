<h1><?=$header;?></h1>
<a href="#">Начать наполнение поставщика</a>
<div class="create_product row">
	<div class="supplier col-md-12">
		<label for="supplier">Поставщик</label>
		<input type="text" class="input-m" placeholder="Выберите поставщика" name="supplier" id="supplier" list="suppliers">
		<datalist id="suppliers">
			<option value="S100">1</option>
			<option value="A01">1</option>
			<option value="X11">2</option>
			<option value="A45">3</option>
			<option value="T56">4</option>
			<option value="Z05">5</option>
		</datalist>
	</div>
	<div class="prodName col-md-4">
		<label for="prodName">Название товара</label>
		<input type="text" id="prodName">
	</div>

	<div class="images col-md-4 hidden">
		<label for="images">Изображения</label>
		<div class="fallback">
			<input type="file" name="images" id="images" multiple />
		</div>
	</div>

	<div class="submit col-md-4">
		<button class="btn-m-default submit_js">Применить</button>
	</div>
	

	<div class="image_block_new drop_zone animate col-md-12">
		<div class="dz-default dz-message">Перетащите сюда фото или нажмите для загрузки.</div>
		<input type="file" multiple="multiple" class="dz-hidden-input" style="visibility: hidden; position: absolute; top: 0px; left: 0px; height: 0px; width: 0px;">
	</div>
	<div class="images_block"></div>
</div>
<div id="preview-template" class="hidden">
	<div class="image_block image_block_js dz-preview dz-file-preview">
		<!-- <div class="sort_handle"><span class="icon-font">s</span></div> -->
		<div class="image">
			<img data-dz-thumbnail />
			<span class="icon-font hide_photo_js" title="Скрыть/отобразить">v</span>
			<span class="icon-font del_photo_js" title="Удалить" data-dz-remove="">t</span>
		</div>
		<div class="name">
			<span class="dz-filename" data-dz-name></span>
			<span class="dz-size" data-dz-size></span>
		</div>
		<!-- <div class="visibility">
			<p><span class="icon-font hide_u_photo_js" title="Скрыть/отобразить">v</span></p>
		</div>
		<div class="controls">
			<p><span class="icon-font del_u_photo_js" title="Удалить">t</span></p>
		</div> -->
	</div>
</div>
<div class="prodList">
	<div class="prodListItem">
		<div class="nameProd">
			<span>Товар:</span>
			<span>Ручка для двери</span>			
		</div>
		<div class="createData">
			<span>Дата:</span>
			<span>09.06.2016</span>
		</div>
		<div class="prodImages">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">
		</div>		
	</div>
	<div class="prodListItem">
		<div class="nameProd">
			<span>Товар:</span>
			<span>Ручка для двери</span>			
		</div>
		<div class="createData">
			<span>Дата:</span>
			<span>09.06.2016</span>
		</div>
		<div class="prodImages">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">
		</div>		
	</div>
	<div class="prodListItem">
		<div class="nameProd">
			<span>Товар:</span>
			<span>Ручка для двери</span>			
		</div>
		<div class="createData">
			<span>Дата:</span>
			<span>09.06.2016</span>
		</div>
		<div class="prodImages">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">
			<img src="http://lorempixel.com/100/100">			
		</div>		
	</div>
</div>

<script>
	var url = URL_base+'productadd/';
	var dropzone = new Dropzone(".drop_zone", {
		method: 'POST',
		url: url+"?upload=true",
		clickable: true,
		/*previewsContainer: '.previews',*/
		previewsContainer: '.images_block',	
		previewTemplate: document.querySelector('#preview-template').innerHTML
	});

	$(window).load(function(){
		$('#supplier').val($.cookie('suppler'));
	});


	$(function(){
		$('#supplier').on('change', function(){
			currentSupplier = $('#supplier').val();			
			$.cookie('suppler', currentSupplier);
		});

		$('body').on('click', '.hide_photo_js', function(){			
			$(this).closest('.image').find('img').toggleClass('imgopacity');
		});


		$('.submit_js').on('click', function(){
			var ArtSupplier = $.cookie('suppler');
			var Name = $('#prodName').val();
			var Images = [];

			$('.images_block .image_block_js').each(function(){
				var name = $(this).find('.dz-filename').html();
				var visibility = $(this).find('img').hasClass('imgopacity');
				var curData = {src: name, visible: visibility};
				Images.push(curData);
			});

			/*ajax('ajaxproducts', 'AddPhotoProduct', {art: ArtSupplier, name: Name, images: Images}).done(function(data){
				console.log('сработало');
			});*/

			$.ajax({
				url: URL_base+'ajaxproducts',
				type: "POST",
				cache: false,
				dataType: "json",
				data: {
					action: 'AddPhotoProduct',
					art: ArtSupplier,
					name: Name,
					images: Images
				}
			}).done(function(data){
				console.log('сработало');
			});
			

			console.log(ArtSupplier);
			console.log(Name);
			console.log(Images);
		});		
	});

</script>



<!-- <table border="0" cellspacing="0" cellpadding="0" class="list">
	<thead>
		<tr>
			<th>Название</th>
			<th>Фото</th>
			<th>Дата создания</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Товар 1</td>
			<td><img src="" alt=""></td>
			<td></td>
		</tr>
		<tr>
			<td>Товар 2</td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>Товар 3</td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>Товар 4</td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>Товар 5</td>
			<td></td>
			<td></td>
		</tr>
	</tbody>
</table> -->
