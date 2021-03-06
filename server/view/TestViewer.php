<?php
include_once 'View.php';

abstract class TestViewer extends View {
	public function __construct($test) {
		parent::includes ();
		parent::header ();
		$this->content ( $test );
		parent::footer ();
	}
	
	/**
	 * @param @see Test $test
	 * @param unknown $question
	 */
	protected function printClosedQuestion($test, $question) {
		echo '
				<ul class="input-list">';
		
		// create 2 equal lengthed arrays of solutions and answer
		$studentAnswerSet = static::expandValuesToArrayLength ( explode ( ";;;", $test->getAnswer ( $question->getID () )->getAnswer () ), count ( $question->getAnswerSet () ) );
		$teacherAnswerSet = static::expandValuesToArrayLength ( $question->getSolutionSet (), count ( $question->getAnswerSet () ) );
		
		$j = 0;
		foreach ( $question->getAnswerSet () as $answer ) {
			echo '
					<li class="';
			
			// solution is picked, but wrong
			if ($teacherAnswerSet [$j] == $studentAnswerSet [$j]) {
				echo "bg-success";
			} elseif ($teacherAnswerSet [$j] && $studentAnswerSet [$j]) {
				echo "bg-warning";
			} else {
				echo "bg-danger";
			}
			echo '"><label><input type="checkbox" ';
			echo $studentAnswerSet [$j] ? "checked" : "";
			echo ' disabled> ' . $answer . '<label>
					</li>';
			$j ++;
		}
		echo '
				</ul>';
	}
	
	protected function getQuestionAnchor($i){
		$questionAnchor = '<a class="btn btn-default" href="#question' . $i . '">';
		$questionAnchor .= $i . '</a>';
		
		return $questionAnchor;
	}
	
	protected function printSidebar($questionanchors, $buttons) {
		echo '
<div class="col-md-3 col-xs-4" style="height: 200px;">
	<div style="position: absolute; top: 0; left: 0;">
		<div style="position: fixed;">
			<div>';
		echo $questionanchors . '
			</div>
		</div>
	</div>
	<div style="position: absolute; bottom: 0; left: 0;">
		<div style="position: fixed;">';
		echo $buttons . '
		</div>
	</div>
</div>';
	}
	
	//
	private static function expandValuesToArrayLength($values, $length) {
		$result = array_fill ( 0, $length, FALSE );
		
		foreach ( $values as $key => $value ) {
			$result [$value - 1] = TRUE;
		}
		return $result;
	}
	
	//
	protected static function getColorCode($answer, $question) {
		if ($answer->getPoints () == $question->getMaxPoints ()) {
			echo 'success';
		} elseif ($answer->getPoints () >= ($question->getMaxPoints () / 2)) {
			echo 'warning';
		} else {
			echo 'danger';
		}
	}
}