<?php
include_once 'View.php';

abstract class TestViewer extends View {
	public function __construct($test) {
		parent::includes ();
		parent::header ();
		$this->content ( $test );
		parent::footer ();
	}
	
	protected function printSidebar($questionanchors,$buttons) {
		echo '<div class="col-md-3 col-xs-4" style="height: 300px;">
				<div style="position: fixed;"><div>';
		echo $questionanchors.'</div></div>
				<div style="position: absolute; bottom: 0; left: 0;">';
		echo $buttons.'</div></div>';
	}
}