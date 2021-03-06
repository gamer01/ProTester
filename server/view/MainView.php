<?php
include_once 'View.php';
class MainView extends View{
	
	private $tabs = array();
	protected $title='Main';
	
	protected function includes(){
		parent::includes();
		include 'Tabs.php';
	}
	
	protected function content(){
		$this->tabs [] = new NewsTab ();
		
		if (isset($_SESSION['isStudent'])) {
			$this->tabs [] = new ViewResultTab ();
		}
		
		if (isset($_SESSION['isLecturer'])) {
			$this->tabs [] = new TestManagementTab ();
			$this->tabs [] = new EvaluateTestTab ();
		}
		
		if (isset($_SESSION['isAdmin'])) {
			$this->tabs [] = new CourseManagementTab ();
			$this->tabs [] = new AccountManagementTab ();
		}
		
		$navtabs = '
			<ul class="nav nav-tabs" role="tablist">';
		$i = 0;
		foreach ( $this->tabs as $tab ) {
		
			$navtabs = $navtabs . '
				<li role="presentation"' . ($i == 0 ? ' class="active"' : '') . '>
					<a href="#' . $tab->id . '" aria-controls="' . $tab->title . '" role="tab" data-toggle="tab">' . $tab->title . '
					</a>
				</li>';
			$i ++;
		}
		$navtabs = $navtabs . "
			</ul>";
		
		$navpanes = '<div class="tab-content">';
		$i = 0;
		foreach ( $this->tabs as $tab ) {
			$navpanes = $navpanes . '
				<div role="tabpanel" class="tab-pane' . ($i == 0 ? ' active' : '') . '" id="' . $tab->id . '">
						' . $tab->content . '
				</div>';
			$i ++;
		}
		
		$navpanes = $navpanes . '</div>';
		
		echo '<div class="container">
		<div role="tabpanel">' . $navtabs . $navpanes . '</div>
	</div>';
	}
}
new MainView();
?>
