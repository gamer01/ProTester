<?php
include_once 'View.php';
class AnswerTestView extends View {
	protected $title = 'Answer Test';
	protected function includes() {
		parent::includes ();
	}
	public function __construct($test) {
		parent::includes ();
		parent::header ();
		$this->content ( $test );
		parent::footer ();
	}
	protected function content($test) {
		$person = new Person ( $_SESSION ['id'] );
		if ($test->answerableFor ( $person )) {
			// timerbar
			$questions = $test->getQuestions ();
			
			foreach ( $questions as $question ) {
				echo '<div class="container">
		<div class="row">
			<div class="col-md-9 col-xs-8">
				<div class="panel-group">';
				echo $question->getText () . '<br><br>';
			}
			// display the sorted questions
			// display sidebar with question anchors
			// echo modal definition
			echo '<form action="AfterTestController.php" method="POST">';
		} else {
			echo "<h1>You have no Permission to see this results, please login again</h1>";
		}
	}
}
session_start ();
require_once (realpath ( dirname ( __FILE__ ) ) . '/../model/TestTemplate.php');
if (isset ( $_GET ['TestTemplateID'] )) {
	new AnswerTestView ( new TestTemplate ( $_GET ['TestTemplateID'] ) );
} else {
	require_once (realpath ( dirname ( __FILE__ ) ) . '/../controller/LoginController.php');
}
?>

<!--<div class="progress" style="margin-top: -20px; height: 5px;">-->
<!-- 	<div class="progress-bar progress-bar-info" role="progressbar" -->
<!-- 		aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" -->
<!-- 		style="width: 30%;"	> -->
<!-- 		<span class="sr-only">30% Complete</span> -->
<!-- 	</div> -->
<!-- </div> -->


<div class="panel panel-info">
	<a class="panel-info" data-toggle="collapse" data-parent="#accordion1"
		href="#collapseOne"
	>
		<div class="panel-heading">
			<h4 class="panel-title">Open Question 1</h4>
		</div>
	</a>
	<div id="collapseOne" class="panel-collapse collapse">
		<div class="panel-body">
			<textarea name="answer[]" style="width: 100%">My open Answer</textarea>
		</div>
	</div>
</div>
<div class="panel panel-default">
	<a class="panel-default" data-toggle="collapse"
		data-parent="#accordion1" href="#collapse2"
	>
		<div class="panel-heading">
			<h4 class="panel-title">Closed Question 2</h4>
		</div>
	</a>
	<div id="collapse2" class="panel-collapse collapse">
		<div class="panel-body">
			<ul class="input-list">
				<li><label><input type="checkbox" id="chk1" name="answer[]"> Answer
						1<label></li>
				<li><label><input type="checkbox" id="chk2" name="answer[]"> Answer
						2</label></li>
			</ul>
		</div>
	</div>
</div>
<div class="panel panel-default">
	<a class="panel-default" data-toggle="collapse"
		data-parent="#accordion1" href="#collapse3"
	>
		<div class="panel-heading">
			<h4 class="panel-title">Question 3</h4>
		</div>
	</a>
	<div id="collapse3" class="panel-collapse collapse">
		<div class="panel-body">bla</div>
	</div>
</div>
<div class="panel panel-default">
	<a class="panel-default" data-toggle="collapse"
		data-parent="#accordion1" href="#collapse4"
	>
		<div class="panel-heading">
			<h4 class="panel-title">Question 4</h4>
		</div>
	</a>
	<div id="collapse4" class="panel-collapse collapse">
		<div class="panel-body">Content</div>
	</div>
</div>
</div>

<div class="panel-group">
	<div class="panel panel-default">
		<a class="panel-default" data-toggle="collapse"
			data-parent="#accordion2" href="#collapse5"
		>
			<div class="panel-heading">
				<h4 class="panel-title">Question 5</h4>
			</div>
		</a>
		<div id="collapse5" class="panel-collapse collapse">
			<div class="panel-body">Content</div>
		</div>
	</div>
	<div class="panel panel-info">
		<a class="panel-info" data-toggle="collapse" data-parent="#accordion2"
			href="#collapse6"
		>
			<div class="panel-heading">
				<h4 class="panel-title">Question 6</h4>
			</div>
		</a>
		<div id="collapse6" class="panel-collapse collapse">
			<div class="panel-body">Content</div>
		</div>
	</div>
	<div class="panel panel-default">
		<a class="panel-default" data-toggle="collapse"
			data-parent="#accordion2" href="#collapse7"
		>
			<div class="panel-heading">
				<h4 class="panel-title">Question 7</h4>
			</div>
		</a>
		<div id="collapse7" class="panel-collapse collapse">
			<div class="panel-body">Content</div>
		</div>
	</div>
	<div class="panel panel-default">
		<a class="panel-default" data-toggle="collapse"
			data-parent="#accordion2" href="#collapse8"
		>
			<div class="panel-heading">
				<h4 class="panel-title">Another question</h4>
			</div>
		</a>
		<div id="collapse8" class="panel-collapse collapse">
			<div class="panel-body">Content</div>
		</div>
	</div>
</div>
</div>
<div class="col-md-3 col-xs-4" style="height: 300px;">
	<div style="position: fixed;">
		<div>list of all questions</div>
	</div>
	<div style="position: absolute; bottom: 0; right: 0; width: 82px;">
		<div style="position: fixed;">
			<button type="button" class="btn btn-primary" data-toggle="modal"
				data-target="#submit"
			>submit</button>
		</div>
	</div>
</div>
</div>
</div>

<div class="modal fade" id="submit" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true"
>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">Modal title</h4>
			</div>
			<div class="modal-body">
				<p>You have still x:x Minutes time and y unanswered questions</p>
				<p>When you submit you will not be able to do any more changes on
					your answers.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Return
					to Test</button>
				<input type="submit" class="btn btn-primary"
					value="Submit Answers now"
				/>
			</div>
		</div>
	</div>
</div>
</form>