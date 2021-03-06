<?php
require_once (realpath ( dirname ( __FILE__ ) ) . '/../controller/DBController.php');
require_once (realpath ( dirname ( __FILE__ ) ) . '/TestTemplate.php');
require_once (realpath ( dirname ( __FILE__ ) ) . '/Person.php');
require_once (realpath ( dirname ( __FILE__ ) ) . '/Answer.php');

/**
 *
 * @access public
 * @author gamer01
 * @package Server.Model
 */
class Test {
	private $_iD;
	private $_result;
	private $_grade;
	private $_answerID;
	private $person;
	private $testTemplate;
	//
	public function __construct($id) {
		$mysqli = DBController::getConnection ();
		
		if ($result = $mysqli->query ( 'SELECT * FROM Test WHERE TestID="' . $id . '"' )) {
			$row = $result->fetch_array ( MYSQLI_ASSOC );
			
			$this->_iD = $row ['TestID'];
			$this->testTemplate = new TestTemplate ( $row ['TestTemplateID'] );
			$this->person = new Person ( $row ['PersonID'] );
			$this->_result = $row ['Result'];
			$this->_grade = $row ['Grade'];
			
			$result->free ();
		} else {
			echo "<script>console.log(\"" . __CLASS__ . "->" . __METHOD__ . " failed DB response\")</script>";
		}
		
		$mysqli->close ();
	}
	public function areAllAnswerpointsSet() {
		$resultcode = FALSE;
		$mysqli = DBController::getConnection ();
		
		if ($result = $mysqli->query ( 'SELECT * FROM Test JOIN Answer ON Test.TestID=Answer.TestID WHERE  Points IS NULL AND Test.TestID="' . $this->_iD . '"' )) {
			if ($result->num_rows == 0) {
				$resultcode = TRUE;
			}
			$result->free ();
		} else {
			echo "<script>console.log(\"" . __CLASS__ . "->" . __METHOD__ . " failed DB response\")</script>";
			return FALSE;
		}
		
		$mysqli->close ();
		return $resultcode;
	}
	
	//
	public function getTestTemplate() {
		return $this->testTemplate;
	}
	
	//
	public function getResult() {
		return $this->_result;
	}
	
	//
	public function getGrade() {
		return $this->_grade;
	}
	
	//
	public function isEvaluated() {
		return isset ( $this->_grade );
	}
	
	//
	public function getID() {
		return $this->_iD;
	}
	
	//
	public function getPerson() {
		return $this->person;
	}
	
	//
	public static function upload($testTemplate, $person, $grade = NULL, $result = NULL) {
		$mysqli = DBController::getConnection ();
		
		$query = 'INSERT INTO Test (TestTemplateID, PersonID, Grade, Result)
				VALUES (' . $testTemplate . ',' . $person . ',' . (isset ( $grade ) ? $grade : "NULL") . ',' . (isset ( $result ) ? $result : "NULL") . ');';
		if ($result = $mysqli->query ( $query )) {
			return $mysqli->insert_id;
		} else {
			// insert failed
			return $mysqli->error;
		}
	}
	
	//
	public static function updateResult($testID, $result) {
		$mysqli = DBController::getConnection ();
		
		$query = 'UPDATE Test SET Result ="' . $result . '" WHERE TestID ="' . $testID . '";';
		if ($result = $mysqli->query ( $query )) {
			return TRUE;
		} else {
			// insert failed
			return FALSE;
		}
	}
	
	//
	public static function updateGrade($testID, $grade) {
		$mysqli = DBController::getConnection ();
		
		$query = 'UPDATE Test SET Grade ="' . $grade . '" WHERE TestID ="' . $testID . '";';
		if ($result = $mysqli->query ( $query )) {
			return TRUE;
		} else {
			// insert failed
			return FALSE;
		}
	}
	
	//
	public function getAnswers() {
		$mysqli = DBController::getConnection ();
		
		if ($result = $mysqli->query ( 'SELECT AnswerID FROM Answer
				WHERE TestID="' . $this->_iD . '" ORDER BY QuestionID' )) {
			$answers = array ();
			while ( $row = $result->fetch_array ( MYSQLI_ASSOC ) ) {
				array_push ( $answers, new Answer ( $row ['AnswerID'] ) );
			}
			return $answers;
		} else {
			echo "<script>console.log(\"" . __CLASS__ . "->" . __METHOD__ . " failed DB response\")</script>";
		}
	}
	
	//
	public function getAnswer($questionID) {
		$mysqli = DBController::getConnection ();
		
		if ($result = $mysqli->query ( 'SELECT AnswerID FROM Answer
				WHERE QuestionID="' . $questionID . '" AND TestID="' . $this->_iD . '"' )) {
			$answer;
			if ($row = $result->fetch_array ( MYSQLI_ASSOC )) {
				$answer = new Answer ( $row ['AnswerID'] );
			}
			return $answer;
		} else {
			echo "<script>console.log(\"" . __CLASS__ . "->" . __METHOD__ . " failed DB response\")</script>";
		}
	}
	
	//
	public function ownedBy($person) {
		return $this->person->equals ( $person );
	}
	
	//
	public static function getTestContent($Testid) {
		return $tests;
	}
}
?>