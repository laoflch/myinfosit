<?php
class WeixinMessage extends AppModel
{
	
	var $name = 'WeixinMessage';
	var $primaryKey="message_id";
	/* var $hasOne = array('WeixinMessageText' =>
			array('className'    => 'WeixinMessageText',
					'conditions'   => '',
					'order'        => '',
					'dependent'    =>  false,
					'foreignKey'   => 'message_id'
			)
	); */
}
?>