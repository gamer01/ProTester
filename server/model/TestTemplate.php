<?php
require_once(realpath(dirname(__FILE__)) . '/Course.php');
// require_once(realpath(dirname(__FILE__)) . '/../controller/AnswerPointsManager.php');
// require_once(realpath(dirname(__FILE__)) . '/Test.php');
// require_once(realpath(dirname(__FILE__)) . '/Question.php');

/**
 * @access public
 * @author gamer01
 * @package Server.Model
 */
class TestTemplate {
	/**
	 * @AttributeType int
	 */
	private $_testTemplateID;
	/**
	 * @AttributeType int
	 */
	private $_duration;
	/**
	 * @AttributeType Date
	 */
	private $_date;
	/**
	 * @AssociationType Server.Model.Course
	 * @AssociationMultiplicity 0..1
	 */
	public $_course;
	/**
	 * @AssociationType Server.Controller.AnswerPointsManager
	 */
	public $_unnamed_AnswerPointsManager_;
	/**
	 * @AssociationType Server.Model.Test
	 * @AssociationMultiplicity *
	 */
	public $_tests = array();
	/**
	 * @AssociationType Server.Model.Question
	 * @AssociationMultiplicity *
	 * @AssociationKind Aggregation
	 */
	public $_questions = array();

	/**
	 * @access public
	 */
	public function maxpoints() {
		// Not yet implemented
	}
	
	public function __construct($id) {
		$mysqli = DBController::getConnection ();
		$result = $mysqli->query ( "SELECT * FROM TestTemplate WHERE TestTemplateID=" . $id );
		$row = $result->fetch_array ( MYSQLI_ASSOC );
	
		$this->_testTemplateID = $row ['TestTemplateID'];
		$this->_course = new Course($row ['CourseID'],$row ['GroupID']);
		$this->_duration = $row ['Duration'];
		$this->_date = $row ['Date'];
		
		$result->close();
	}
	
	// read from testtemplate
	public function getExactDate() {
		$mysqli = DBController::getConnection ();
	
		$result = $mysqli->query ( "SELECT date FROM TestTemplate WHERE TestTemplateID=" . $this->_testTemplateID );
		$row = $result->fetch_array ( MYSQLI_ASSOC )['date'];
		$result->close();
		return $row;
	}
	
	// read from testtemplate
	public function getMonthYear() {
		$mysqli = DBController::getConnection ();
	
		$result = $mysqli->query ( "SELECT DATE_FORMAT(date,'%M %Y') as date FROM TestTemplate WHERE TestTemplateID=" . $this->_testTemplateID );
		$row = $result->fetch_array ( MYSQLI_ASSOC )['date'];
		$result->close();
		return $row;
	}
	
// read from testtemplate
	public function getDayMonth() {
		$mysqli = DBController::getConnection ();
	
		$result = $mysqli->query ( "SELECT DATE_FORMAT(date,'%D %b') as date FROM TestTemplate WHERE TestTemplateID=" . $this->_testTemplateID );
		$row = $result->fetch_array ( MYSQLI_ASSOC )['date'];
		$result->close();
		return $row;
	}
	
	// read from testtemplate
	public function getMaxPoints() {
		return "not implemented";
	}
	
	public function getCourse(){
		return $this->_course;
	}
}
?>