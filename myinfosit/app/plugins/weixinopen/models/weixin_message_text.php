<?php
class WeixinMessageText extends AppModel
{	
	
	var $name = 'WeixinMessage';
	var $primaryKey="message_id";
	var $hasOne = array('WeixinMessageTextOnly' =>
			array('className'    => 'weixinopen.WeixinMessageTextOnly',
					'conditions'   => '',
					'order'        => '',
					'dependent'    =>  false,
					'foreignKey'   => 'message_id'
			)
	);
}
?>