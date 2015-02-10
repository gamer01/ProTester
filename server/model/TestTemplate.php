<?php
require_once (realpath ( dirname ( __FILE__ ) ) . '/Course.php');
require_once (realpath ( dirname ( __FILE__ ) ) . '/Question.php');

/**
 *
 * @access public
 * @author gamer01
 * @package Server.Model
 */
class TestTemplate {
	/**
	 * @AttributeType int
	 */
	private $_testTemplateID;
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
	public $tests = array ();
	/**
	 * @AssociationType Server.Model.Question
	 * @AssociationMultiplicity *
	 * @AssociationKind Aggregation
	 */
	private $questions = array ();
	public function __construct($id) {
		$mysqli = DBController::getConnection ();
		
		$query = 'SELECT * FROM TestTemplate WHERE TestTemplateID="' . $id . '";';
		if ($result = $mysqli->query ( $query )) {
			
			$row = $result->fetch_array ( MYSQLI_ASSOC );
			$this->_testTemplateID = $row ['TestTemplateID'];
			$this->_course = new Course ( $row ['CourseID'], $row ['GroupID'] );
			$this->_duration = $row ['Duration'];
			$this->_date = $row ['Date'];
		} else {
			echo "<script>console.log(\"" . __CLASS__ . "->" . __METHOD__ . " failed DB response\")</script>";
		}
		
		$result->free ();
		$mysqli->close ();
	}
	
	//
	public function getQuestions() {
		$mysqli = DBController::getConnection ();
		$query = 'SELECT QuestionID FROM Question	WHERE TestTemplateID="' . $this->_testTemplateID . '" ORDER BY QuestionID';
		
		$questions = array ();
		if ($result = $mysqli->query ( $query )) {
			while ( $row = $result->fetch_array ( MYSQLI_ASSOC ) ) {
				array_push ( $questions, Question::getQuestion ( $row ['QuestionID'] ) );
			}
			$result->free ();
		} else {
			echo "<script>console.log(\"" . __CLASS__ . "->" . __METHOD__ . " failed DB response\")</script>";
		}
		$mysqli->close ();
		
		return $questions;
	}
	
	//
	public function getTests() {
		$mysqli = DBController::getConnection ();
		$query = 'SELECT TestID FROM Test WHERE TestTemplateID="' . $this->_testTemplateID . '" ORDER BY TestID';
		
		$tests = array ();
		if ($result = $mysqli->query ( $query )) {
			while ( $row = $result->fetch_array ( MYSQLI_ASSOC ) ) {
				array_push ( $tests, new Test ( $row ['TestID'] ) );
			}
			$result->free ();
		} else {
			echo "<script>console.log(\"" . __CLASS__ . "->" . __METHOD__ . " failed DB response\")</script>";
		}
		$mysqli->close ();
		
		return $tests;
	}
	public function getTest($person) {
		foreach ( $this->getTests () as $test ) {
			if ($test->ownedBy ( $person )) {
				return $test;
			}
		}
		return NULL;
	}
	
	//
	public function isAnsweredFrom($person) {
		$exitcode = false;
		foreach ( $this->getTests () as $test ) {
			if ($test->ownedBy ( $person )) {
				$exitcode = true;
			}
		}
		return $exitcode;
	}
	
	//
	public function isEvaluated() {
		foreach ( $this->getTests () as $test ) {
			if (!$test->isEvaluated ()) {
				return FALSE;
			}
		}
		return TRUE;
	}
	
	//
	public function answerableFor($person) {
		$mysqli = DBController::getConnection ();
		
		$result = $mysqli->query ( 'SELECT PersonID FROM Person_Course as p JOIN TestTemplate as t
				ON p.CourseID=t.CourseID AND p.GroupID=t.GroupID
				WHERE TestTemplateID="' . $this->_testTemplateID . '" AND Discriminator="hears" AND PersonID="' . $person->getID () . '"' );
		if ($row = $result->fetch_array ( MYSQLI_ASSOC )['PersonID']) {
			return true;
		} else {
			return false;
		}
	}
	
	// read from testtemplate
	public function getDate() {
		return $this->_date;
	}
	
	// read from testtemplate
	public function getMonthYear() {
		$time = strtotime ( $this->_date );
		
		return date ( "F Y", $time );
	}
	
	// read from testtemplate
	public function getDayMonth() {
		$time = strtotime ( $this->_date );
		
		return date ( "jS M", $time );
	}
	
	//
	public function getMaxPoints() {
		$questions = $this->getQuestions ();
		$maxPoints = 0;
		foreach ( $questions as $question ) {
			$maxPoints += $question->getMaxPoints ();
		}
		return $maxPoints;
	}
	
	// return the course
	public function getCourse() {
		return $this->_course;
	}
	
	//
	public function equals($template) {
		return $this->_testTemplateID == $template->getID ();
	}
	
	//
	public static function isLater($a, $b) {
		if (strtotime ( $a->getDate () ) == strtotime ( $b->getDate () )) {
			return 0;
		}
		return (strtotime ( $a->getDate () ) < strtotime ( $b->getDate () )) ? - 1 : 1;
	}
	
	//
	public function getID() {
		return $this->_testTemplateID;
	}
}
?>