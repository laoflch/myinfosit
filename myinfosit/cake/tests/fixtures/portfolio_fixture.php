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
 * @since         CakePHP(tm) v 1.2.0.4667
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */

/**
 * Short description for class.
 *
 * @package       cake
 * @subpackage    cake.tests.fixtures
 */
class PortfolioFixture extends CakeTestFixture {

	/**
	 * name property
	 *
	 * @var string 'Portfolio'
	 * @access public
	 */
	var $name = 'Portfolio';

	/**
	 * fields property
	 *
	 * @var array
	 * @access public
	 */
	var $fields = array(
			'id' => array('type' => 'integer', 'key' => 'primary'),
			'seller_id' => array('type' => 'integer', 'null' => false),
			'name' => array('type' => 'string', 'null' => false)
	);

	/**
	 * records property
	 *
	 * @var array
	 * @access public
	*/
	var $records = array(
			array('seller_id' => 1, 'name' => 'Portfolio 1'),
			array('seller_id' => 1, 'name' => 'Portfolio 2'),
			array('seller_id' => 2, 'name' => 'Portfolio 1')
	);
}
