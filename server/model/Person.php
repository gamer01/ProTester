<?php
require_once (realpath ( dirname ( __FILE__ ) ) . '/../controller/DBController.php');
require_once (realpath ( dirname ( __FILE__ ) ) . '/Test.php');
// require_once(realpath(dirname(__FILE__)) . '/Course.php');

/**
 *
 * @access public
 * @author gamer01
 * @package Server.Model
 */
class Person {
	/**
	 * @AttributeType int
	 */
	private $_personID;
	/**
	 * @AttributeType String
	 */
	private $_name;
	private $_surname;
	
	/**
	 * @AssociationType Server.Model.Person
	 */
	public $_unnamed_Person_;
	/**
	 * @AssociationType Server.Model.Test
	 * @AssociationMultiplicity 1
	 */
	public $_tests = array ();
	/**
	 * @AssociationType Server.Model.Course
	 * @AssociationMultiplicity *
	 */
	public $_courses = array ();
	public function __construct($id) {
		$mysqli = DBController::getConnection ();
		if ($result = $mysqli->query ( 'SELECT * FROM Person WHERE PersonID="' . $id . '"' )) {
			$row = $result->fetch_array ( MYSQLI_ASSOC );
			
			$this->_personID = $row ['PersonID'];
			$this->_name = $row ['Name'];
			$this->_surname = $row ['Surname'];
			
			$result->free ();
		} else {
			echo "<script>console.log(\"" . __CLASS__ . "->" . __METHOD__ . " failed DB response\")</script>";
		}
		$mysqli->close ();
	}
	
	//
	public static function hasPermission($user, $password) {
		$mysqli = DBController::getConnection ();
		$exitcode = false;
		
		if (empty ( $user ) || empty ( $password )) {
			$_SESSION ['loginError'] = "Password or Username was empty. Please provide in all information";
		} else {
			if ($result = $mysqli->query ( "SELECT PersonID as id,name,discriminator FROM Person WHERE PersonID='" . $user . "' AND Password='" . $password . "';" )) {
				if ($result->num_rows == 0) {
					$_SESSION ['loginError'] = "Username or Password wrong.";
				} else {
					$row = $result->fetch_array ( MYSQLI_ASSOC );
					$_SESSION ['ID'] = $row ['id'];
					$_SESSION ['username'] = $row ['name'];
					if (strpos ( $row ['discriminator'], "Student" ) !== false) {
						$_SESSION ['isStudent'] = true;
					}
					if (strpos ( $row ['discriminator'], "Lecturer" ) !== false) {
						$_SESSION ['isLecturer'] = true;
					}
					if (strpos ( $row ['discriminator'], "Admin" ) !== false) {
						$_SESSION ['isAdmin'] = true;
					}
					
					$exitcode = true;
				}
				
				// free result set
				$result->free ();
				$mysqli->close ();
			} else {
				$_SESSION ['loginError'] = "Error with Database :/";
			}
		}
		
		return $exitcode;
	}
	
	//
	public function hasUnansweredTestsToday() {
		$testTemplates = $this->getScheduledTests ();
		$exitcode = false;
		foreach ( $testTemplates as $testTemplate ) {
			if (! $testTemplate->isAnsweredFrom ( $this ) && (strtotime ( $testTemplate->getDate () ) == strtotime('today'))) {
				$exitcode = true;
			}
		}
		return $exitcode;
	}
	
	/**
	 * returns a testarray to create ViewTestTab
	 */
	public function getWrittenTests() {
		
		// find all tests for this person
		$mysqli = DBController::getConnection ();
		
		$result = $mysqli->query ( 'SELECT TestID FROM Test,TestTemplate WHERE PersonID="' . $this->_personID . '" AND Test.TestTemplateID=TestTemplate.TestTemplateID ORDER BY date' );
		// delegate the test class to create test objects according testid (call the constructor in a loop)
		$tests = array ();
		while ( $row = $result->fetch_array ( MYSQLI_ASSOC ) ) {
			array_push ( $tests, new Test ( $row ['TestID'] ) );
		}
		// return this array of objects
		$result->free ();
		$mysqli->close ();
		
		return $tests;
	}
	
	//
	public function getCreatedTestTemplates() {
		$teachingCourses = $this->getTeachingCourses ();
		
		$testTemplates = array ();
		foreach ( $teachingCourses as $course ) {
			$testTemplates = array_merge ( $testTemplates, $course->getTestTemplates () );
		}
		// return this array of objects
		return $testTemplates;
	}
	
	//
	public function canEvaluate($testTemplate) {
		$testTemplates = $this->getCreatedTestTemplates ();
		
		foreach ($testTemplates as $testTempl){
			if ($testTempl->equals($testTemplate)){
				return TRUE;
			}
		}
		return FALSE;
	}
	
	//
	public function getScheduledTests() {
		$teachingCourses = $this->getHearingCourses ();
		
		$testTemplates = array ();
		foreach ( $teachingCourses as $course ) {
			$testTemplates = array_merge ( $testTemplates, $course->getTestTemplates () );
		}
		
		usort ( $testTemplates, 'TestTemplate::isLater' );
		// return this array of objects
		return $testTemplates;
	}
	
	//
	public function getTeachingCourses() {
		$mysqli = DBController::getConnection ();
		
		$result = $mysqli->query ( 'SELECT Course.CourseID,Course.GroupID FROM Course,Person_Course ' . 'WHERE PersonID="' . $this->_personID . '" ' . 'AND Course.CourseID=Person_Course.CourseID ' . 'AND Course.GroupID=Person_Course.GroupID ' . 'AND Discriminator="teaches" ' . 'ORDER BY Name,GroupID' );
		// delegate the test class to create test objects according testid (call the constructor in a loop)
		$courses = array ();
		while ( $row = $result->fetch_array ( MYSQLI_ASSOC ) ) {
			array_push ( $courses, new Course ( $row ['CourseID'], $row ['GroupID'] ) );
		}
		// return this array of objects
		$result->free ();
		$mysqli->close ();
		
		return $courses;
	}
	
	//
	public function getHearingCourses() {
		$mysqli = DBController::getConnection ();
		
		$result = $mysqli->query ( 'SELECT Course.CourseID,Course.GroupID FROM Course,Person_Course ' . 'WHERE PersonID="' . $this->_personID . '" ' . 'AND Course.CourseID=Person_Course.CourseID ' . 'AND Course.GroupID=Person_Course.GroupID ' . 'AND Discriminator="hears" ' . 'ORDER BY Name,GroupID' );
		// delegate the test class to create test objects according testid (call the constructor in a loop)
		$courses = array ();
		while ( $row = $result->fetch_array ( MYSQLI_ASSOC ) ) {
			array_push ( $courses, new Course ( $row ['CourseID'], $row ['GroupID'] ) );
		}
		// return this array of objects
		$result->free ();
		$mysqli->close ();
		
		return $courses;
	}
	
	//
	public function getID() {
		return $this->_personID;
	}
	
	//
	public function getFullName() {
		return $this->_name.' '.$this->_surname;
	}
	
	//
	public function equals($person) {
		return $this->_personID == $person->getID ();
	}
}
?>