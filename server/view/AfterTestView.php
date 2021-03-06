<?php
include_once 'View.php';

class AfterTestView extends View {
	protected $title = 'Test Finished';

	public function content() {
		echo '<div class="container">'."\n\t".'<div class="col-md-9 col-xs-8">';
		
		echo "\n\t\t".'<h3>Test has been finished because '.$_SESSION['FinishReason'].'</h3>';
		
		echo "\n\t\t".'<strong data-toggle="tooltip"title="Only for Closed and Gap questions">'.
				'Result: '.$_SESSION['ResultString'].'</strong>'.
				'<a class="btn btn-default" style="float: right" href="'. 
				PATH.'server/controller/LoginController.php">Back to Homepage</a>';
		echo "\n\t".'</div>'."\n".'</div>';
	}
}
session_start();
if (isset ( $_SESSION ['ID'] )) {
	new AfterTestView ();
	unset($_SESSION['ResultString']);
	unset($_SESSION['FinishReason']);
} else {
	require_once (realpath ( dirname ( __FILE__ ) ) . '/../controller/LoginController.php');
}
?>