<?php
require_once(realpath(dirname(__FILE__)) . '/TestController.php');
require_once(realpath(dirname(__FILE__)) . '/../model/Test.php');

/**
 * @access public
 * @author gamer01
 * @package Server.Controller
 */
class TestManager {
	/**
	 * @AssociationType Server.Controller.TestController
	 */
	public $_delegates_;
	/**
	 * @AssociationType Server.Model.Test
	 * @AssociationMultiplicity *
	 */
	public $_unnamed_Test_ = array();
}
?>