<section class="popular">
	<h2><?=$title?></h2>
	<div id="ca-container" class="ca-container">
		<div class="ca-wrapper">
			<?$n=1;
			foreach($pops as $i){?>
				<div class="ca-item ca-item-<?=$n?>">
					<div class="ca-item-main">
						<a href="<?=_base_url.'/product/'.$i['id_product'].'/'.$i['translit']?>">
							<img height="100" alt="<?=$i['name']?>" src="<?=file_exists($GLOBALS['PATH_root'].$i['img_1'])?_base_url.htmlspecialchars(str_replace("/efiles/image/", "/efiles/image/500/", $i['img_1'])):'/images/nofoto.png'?>">
							<h5><?=$i['name']?></h5>
						</a>
						<a href="<?=_base_url.'/product/'.$i['id_product'].'/'.$i['translit']?>" class="ca-more"><?=number_format($i['price_mopt'],2,",","")?> грн.</a>
					</div>
				</div>
			<?$n++;}?>
		</div>
	</div>
</section>
<script type="text/javascript">
	$('#ca-container').contentcarousel();
</script>
