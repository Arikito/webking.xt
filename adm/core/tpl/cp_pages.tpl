<h1><?=$h1?></h1>
<?if (isset($errm) && isset($msg)){?><div class="notification error"> <span class="strong">Ошибка!</span><?=$msg?></div><br>
<?}elseif(isset($msg)){?><div class="notification success"> <span class="strong">Сделано!</span><?=$msg?></div><br><?}?>
<?if(isset($list) && count($list)){?>
	<form action="<?=$GLOBALS['URL_request']?>" method="post">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list paper_shadow_1">
			<col width="80%">
			<col width="1%">
			<col width="250px">
			<thead>
	          <tr>
	            <td class="left">Название страницы</td>
	            <td class="center">&uarr; &darr;</td>
	            <td class="left">Управление</td>
	          </tr>
	        </thead>
			<tbody>
			<?foreach($list as $i){?>
				<tr class="animate">
					<td><?=!$i['visible']?'<span class="invisible">(скрытая) </span>':null?><a href="<?=$GLOBALS['URL_base'].'adm/pageedit/'.$i['id_page']?>"><?=$i['title']?></a></td>
					<td class="center np"><input type="text" name="ord[<?=$i['id_page']?>]" class="input-s" value="<?=$i['ord']?>"></td>
					<td class="left actions"><nobr>
						<a class="btn-m-green-inv" href="/adm/pageedit/<?=$i['id_page']?>">редактировать</a>
						<a class="btn-m-green-inv" href="/page/<?=$i['translit']?>/">смотреть</a>
						<a class="btn-m-red-inv" href="/adm/pagedel/<?=$i['id_page']?>" onclick="return confirm('Точно удалить?');">удалить</a>
						</nobr>
					</td>
				</tr>
			<?}?>
			</tbody>
			<tr><td>&nbsp;</td>
				<td class="center"><input class="btn-m-default-inv" type="submit" name="smb" id="form_submit" value="&uarr;&darr;"></td>
				<td>&nbsp;</td>
			</tr>

		</table>
	</form>
<?}else{?>
	<div class="notification warning"> <span class="strong">Новостей нет</span></div>
<?}?>