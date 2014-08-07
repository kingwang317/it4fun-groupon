<div id="banner_title">
<img src="<?php echo site_url() ?>templates/images/banner_title.jpg">
</div>

<div id="news">

<?php if (isset($news_results)): ?>

	<?php foreach ($news_results as $key => $row): ?>
	<!--roll-->
		<?php 
         $date = explode(" ", $row->date); 
         $date2 = $date[0];
         // echo $date2;

        ?>
		<div id="news_title_1" class="news_title">
		<h3><span style="color:red;"><?php echo $date2 ?></span>  <?php echo $row->title ?></h3>
		</div>
		<div id="news_text_1" class="news_text">
			<div id="newsleft">
			<a href="<?php echo site_url()."assets/".$row->img ?>" target="_blank"><img src="<?php echo site_url()."assets/".$row->img ?>" width="200px"></a>
			</div>
			<div id="newsright">
	 			<?php echo $row->content ?>
			</div>
			<div id="clear"></div>
		</div>
	<!--roll-->
		
	<?php endforeach ?>
	
<?php endif ?>



 </div>
<center>
<ul class="pagination">
      <!-- <li class="disabled"><a href="#">«</a></li>
      <li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>
      <li><a href="#">2</a></li>
      <li><a href="#">3</a></li>
      <li><a href="#">4</a></li>
      <li><a href="#">5</a></li>
      <li><a href="#">»</a></li> -->

		<?php echo $pagination?>
	 
</ul>
</center>
</div>