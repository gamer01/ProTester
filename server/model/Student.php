<?php
require_once(realpath(dirname(__FILE__)) . '/../controller/FindAnswerableTestsManager.php');
require_once(realpath(dirname(__FILE__)) . '/Test.php');
require_once(realpath(dirname(__FILE__)) . '/Person.php');

/**
 * @access public
 * @author gamer01
 * @package Server.Model
 */
class Student extends Person {
	/**
	 * @AttributeType int
	 */
	private $_studentID;
	/**
	 * @AssociationType Server.Controller.FindAnswerableTestsManager
	 * @AssociationMultiplicity 1
	 */
	public $_unnamed_FindAnswerableTestsManager_;
	/**
	 * @AssociationType Server.Model.Test
	 * @AssociationMultiplicity 0..2
	 */
	public $_tests = array();
}
?>