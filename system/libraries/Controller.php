<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2010, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/general/controllers.html
 */
class Controller extends CI_Base {

  var $_ci_scaffolding	= FALSE;
  var $_ci_scaff_table	= FALSE;

  /**
   * Constructor
   *
   * Calls the initialize() function
   */
	function Controller()
	{
		parent::CI_Base();
		$this->_ci_initialize();
		log_message('debug', "Controller Class Initialized");
	}

	/**
	 * Initialize
	 *
	 * Assigns all the bases classes loaded by the front controller to
	 * variables in this class.  Also calls the autoload routine.
	 *
	 * @access	private
	 * @return	void
	 */
	 /*
	function _ci_initialize()
	{
		// Assign all the class objects that were instantiated by the
		// front controller to local class variables so that CI can be
		// run as one big super object.
		$classes = array(
							'config'	=> 'Config',
							'input'		=> 'Input',
							'benchmark'	=> 'Benchmark',
							'uri'		=> 'URI',
							'output'	=> 'Output',
							'lang'		=> 'Language',
							'router'	=> 'Router'
							);
		
		foreach ($classes as $var => $class)
		{
			$this->$var =& load_class($class);
		}
		
		$this->load =& load_class('Loader');
		$this->load->_ci_autoloader();
		
		// redirect to login if they arent currently logged in
		
		if (!$this->sauth->is_logged_in())
		{
			if ((!isset($this->uri->segments[1]) || $this->uri->segments[1] != 'beta'))
			{
				$this->load->helper(array('url'));
				redirect('/beta/');
			}
		}
	}*/

  // --------------------------------------------------------------------

  /**
   * Initialize
   *
   * Assigns all the bases classes loaded by the front controller to
   * variables in this class.  Also calls the autoload routine.
   *
   * @access	private
   * @return	void
   */
  function _ci_initialize()
  {
    // Assign all the class objects that were instantiated by the
    // front controller to local class variables so that CI can be
    // run as one big super object.
    $classes = array('config' => 'Config',
                     'input' => 'Input',
                     'benchmark' => 'Benchmark',
                     'uri' => 'URI',
                     'output' => 'Output',
                     'lang' => 'Language',
                     'router' => 'Router'
                     );

    foreach ($classes as $var => $class) {
      $this->$var =& load_class($class);
    }
    $this->load =& load_class('Loader');
    $this->load->_ci_autoloader();

  }

}
// END _Controller class

/* End of file Controller.php */
/* Location: ./system/libraries/Controller.php */
