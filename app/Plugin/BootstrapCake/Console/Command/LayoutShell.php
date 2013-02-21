<?php
/*
 * Layout Shell Script for CakePHP Console to create custom layout files based on Twitter Bootstrap  
 *
 * Copyright 2012, visionred (http://visionred.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author 		  Florian Nitschmann (f.nitschmann@visionred.org) 
 * @copyright     Copyright 2012, visionred (http://visionred.org)
 * @link          http://visionred.org, visionred Web Solutions
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('File', 'Utility'); 
App::uses('Folder', 'Utility');

class LayoutShell extends AppShell {

	private $default_name = 'default';

	private $layout_types = array('fixed', 'fluid'); 

	private $layout_files; 
	
	private $layout_folder;

	/*
	 * initialize() - Init shell 
	 *
	 * @access public
	 * @return void 	
	 */
	public function initialize() {
		//Colors:
		$this->stdout->styles('green', array('text' => 'green'));
		$this->stdout->styles('red', array('text' => 'red'));
		$this->stdout->styles('yellow', array('text' => 'yellow'));
		//Custom layout files
		$this->layout_files = array(
			'fixed' => ''.APP::pluginPath('BootstrapCake').'View'.DS.'Layouts'.DS.'fixed.ctp',
			'fluid' => ''.APP::pluginPath('BootstrapCake').'View'.DS.'Layouts'.DS.'fluid.ctp',
		);
		//App layout folder:
		$this->layout_folder = ''.APP.'View'.DS.'Layouts'.DS; 
	}


	/*
	 * main() - The main function for the LayoutShell 
	 *
	 * @access public
	 * @return mixed 
	 */
	public function main() {
		$this->out(__d('cake_console', '<green>Welcome to Twitter Bootstrap 2.0 for CakePHP 2.x!</green>'));
		$this->out("\n");
		//Option parsers: 
		$name = $this->params['name'];
		$layout_type = $this->params['type'];

		if($this->layoutFileExists($name)) {
			$this->out(__d('cake_console', '<red>Layout `'.$name.'.ctp` already exists!</red>'));
			$override = strtoupper($this->in(__d('cake_console', 'Do you want to override it?'), array('Y', 'N'), 'Y'));
			
			switch ($override) {
				case 'Y':
					$this->createLayoutFile($name, $layout_type, true); 					
					break;
				case 'N':
					$this->error(__d('cake_console', 'Aborted!')); 
					break;				
			}

		}  
		else $this->createLayoutFile($name, $layout_type); 
	}

	/*
	 * createLayoutFile($name, $layout_type, $override = false) - Create a new custom Twitter Bootstrap Layout file with the parameters 
	 *
	 * @access private
	 * @param String $name - The name for the new layout file
	 * @param String $layout_type - Type of the layout (fluid or fixed)
	 * @param bool $override [default: false] - Override a layout file if it already exists
	 * @return void 
	 */
	private function createLayoutFile($name, $layout_type, $override = false) {
		$layout_file = new File(''.$this->layout_folder.''.$name.'.ctp');
		$template_file = new File($this->layout_files[''.$layout_type.'']);

		if($this->layoutFileExists($name) && $override == true) $layout_file->delete(); 

		$this->hr();
		$this->out(__d('cake_console', '<yellow>Creating layout file `'.$layout_file->pwd().'`:</yellow>'));

		if($layout_file->create()) {
			$content = $template_file->read(); 
			if($layout_file->write($content)) $this->out(__d('cake_console', '<green>SUCCESS!</green>')); 
			else $this->out(__d('cake_console', 'Could not write contents to `'.$layout_file->pwd().'`'));
		} 
		else $this->error(__d('cake_console', 'Could not create file!'));
	}

	/*
	 * layoutFileExists($name) - Check if a layout file is already available
	 *
	 * @access public
	 * 
	 * @return bool 
	 */
	public function layoutFileExists($name) {
		if(isset($name)) {
			$file = new File(''.$this->layout_folder.''.$name.'.ctp');
			return $file->exists();  
		}
		else return false; 
	}

	/*
	 * getOptionParser() 
	 *
	 * @access public 
	 * @return void 
	 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();
		$parser->description(__d('cake_console', 'The shell to create custom Twitter Bootstrap Layouts'
		))->addOption('name', array(
			'short' => 'n',
			'help' => __d('cake_console', 'The name for your new layout file.'),
			'default' => 'default'
		))->addOption('type', array(
			'short' => 't',
			'help' => __d('cake_console', 'Layout type (fixed [default] or fluid)'),
			'choices' => $this->layout_types,
			'default' => 'fixed'
		));
		return $parser; 
	}

}

