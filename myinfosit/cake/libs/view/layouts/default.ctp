<?php
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.view.templates.layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php __('CakePHP: the rapid development php framework:'); ?>
		<?php echo $title_for_layout; ?>
	</title>
	<meta name="keywords" content="myinfosit，个人信息平台">
	<?php
		echo $this->Html->meta('icon');
		//echo $this->Html->css('cake.generic');
		echo $this->Html->css('base');
		echo $this->Html->script('jquery-1.9.1');
		echo $this->Html->script('mis_core');
		echo $scripts_for_layout;
	?>
	
</head>
<body>
<div id="wrap">
             <div id="header-wrap">
 	<div id="header">
 	<div class="logo">
 	<a href="http://www.dewen.org/">德问</a>
 	
 	</div>
 	<div class="headerArrow"></div>
	</div>	
</div>
<div class="clear"></div> 
	<div id="container">
		
		

			<?php //echo $this->Session->flash(); ?>

			<?php echo $content_for_layout; ?>

		
		<div id="footer">
			
		</div>
	</div>
	</div>
	
</body>

</html>