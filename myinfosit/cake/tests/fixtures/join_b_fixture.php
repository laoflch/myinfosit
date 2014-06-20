<?php
/**
 * Short description for file.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) Tests <http://book.cakephp.org/view/1196/Testing>
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 *  Licensed under The Open Group Test Suite License
 *  Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://book.cakephp.org/view/1196/Testing CakePHP(tm) Tests
 * @package       cake
 * @subpackage    cake.tests.fixtures
 * @since         CakePHP(tm) v 1.2.0.6317
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */

/**
 * Short description for class.
 *
 * @package       cake
 * @subpackage    cake.tests.fixtures
 */
class JoinBFixture extends CakeTestFixture {

	/**
	 * name property
	 *
	 * @var string 'JoinB'
	 * @access public
	 */
	var $name = 'JoinB';

	/**
	 * fields property
	 *
	 * @var array
	 * @access public
	 */
	var $fields = array(
			'id' => array('type' => 'integer', 'key' => 'primary'),
			'name' => array('type' => 'string', 'default' => ''),
			'created' => array('type' => 'datetime', 'null' => true),
			'updated' => array('type' => 'datetime', 'null' => true)
	);

	/**
	 * records property
	 *
	 * @var array
	 * @access public
	*/
	var $records = array(
			array('name' => 'Join B 1', 'created' => '2008-01-03 10:55:01', 'updated' => '2008-01-03 10:55:01'),
			array('name' => 'Join B 2', 'created' => '2008-01-03 10:55:02', 'updated' => '2008-01-03 10:55:02'),
			array('name' => 'Join B 3', 'created' => '2008-01-03 10:55:03', 'updated' => '2008-01-03 10:55:03')
	);
}
