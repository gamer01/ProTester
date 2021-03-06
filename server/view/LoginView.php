<?php
include_once 'View.php';
class LoginView extends View {
	protected $title = 'Login';
	protected function includes() {
		parent::includes ();
	}
	protected function content() {
		echo '<div class="row">';
		if (isset ( $_SESSION ['loginError'] )) {
			echo '<div class="col-md-6 col-md-offset-3 col-xs-8 col-xs-offset-2">
						<div class="alert alert-danger" role="alert">' . $_SESSION ['loginError'] . '</div>
								</div>';
			unset ( $_SESSION ['loginError'] );
		}
		echo '<div class="col-md-4 col-md-offset-4 col-xs-6 col-xs-offset-3">
				<form action="' . PATH . 'server/controller/LoginController.php" method="post">
		<div class="form-group">
		<label for="usrName">Username</label>
		<input class="form-control" id="inputUsrName" placeholder="Enter Username" name="usr">
		</div>
		<div class="form-group">
		<label for="inputPasswd">Password</label>
		<input type="password" class="form-control" id="inputPasswd" placeholder="Enter Password" name="pwd">
		</div>
		<button type="submit" class="btn btn-default pull-right">Login</button>
		</form></div>
		</div>';
	}
}
session_start();
if (isset ( $_SESSION ['ID'] )) {
	require_once (realpath ( dirname ( __FILE__ ) ) . '/../controller/LoginController.php');
} else {
	new LoginView ();
}
?>