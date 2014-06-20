 
 
<div>

<?php
	$i = 0;
	foreach ($message_list as $message):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<ul<?php echo $class;?>>
		<li><div><?php echo $message['text']?>&nbsp;</div><div><img src="<?php echo $message['thumbnail_pic'] ?> /></div></li>		
	</ul>
<?php endforeach; ?>


                    </div>
              
          