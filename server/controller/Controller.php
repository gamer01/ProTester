<?php
class Controller {

	protected function includes(){
		@session_start();
		include '../controller/settings.php';
	}
}