<?php

namespace AdminModule;

abstract class AdminPresenter extends \Flame\Application\UI\SecuredPresenter
{

	public function beforeRender()
	{
		parent::beforeRender();
		$this->template->menuItems = $this->generateMainMenu();
	}

	protected function createSlug($name)
	{
		$url = preg_replace('~[^\\pL0-9_]+~u', '-', $name);
		$url = trim($url, "-");
		$url = iconv("utf-8", "us-ascii//TRANSLIT", $url);
		$url = strToLower($url);
		$url = preg_replace('~[^-a-z0-9_]+~', '', $url);

		return $url;
	}

	private function generateMainMenu()
	{
		$items = array(
			array('page' => 'Dashboard:', 'title' => 'Dashboard'),
			array('page' => 'Post:', 'title' => 'Posts'),
			array('page' => 'Category:', 'title' => 'Categories'),
			array('page' => 'Tag:', 'title' => 'Tags'),
			array('page' => 'Page:', 'title' => 'Pages'),
			array('page' => 'Image:', 'title' => 'Images'),
			array('page' => 'Comment:', 'title' => 'Comments'),
			array('page' => 'Newsreel:', 'title' => 'Newsreel'),
			array('page' => 'Option:', 'title' => 'Options'),
			array('page' => 'User:', 'title' => 'Users'),
			array('page' => 'Import:', 'title' => 'Import posts'),
		);

		$menu = array();

		foreach($items as $item){
			if(isset($item['page']) and isset($item['title'])){
				$parts = explode(':', $item['page']);

				$parts[1] = (empty($parts[1])) ? 'default' : $parts[1];
				
				$user = $this->getUser();
				if(count($parts) <= 2){
					if($user->isAllowed('Admin:' . $parts[0], $parts['1'])){
						$menu[] = (object) $item;
					}
				}else{
					if($user->isAllowed( $parts[0] . ':' . $parts[1], $parts['2'])){
						$menu[] = (object) $item;
					}
				}
			}
		}

		return $menu;
	}
}
