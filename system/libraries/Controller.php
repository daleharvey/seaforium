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
		
		$this->meta = array(
			'user_id' => (int) $this->session->userdata('user_id') ?: 0,
			'username' => $this->session->userdata('username') ?: 'guest',
			'session_id' => $this->session->userdata('session_id'),
			'hide_enemy_posts' => $this->session->userdata('hide_enemy_posts'),
			'comments_shown' => (int) $this->session->userdata('comments_shown') ?: 50,
			'threads_shown' => (int) $this->session->userdata('comments_shown') ?: 50,
			'view_html' => (bool) $this->session->userdata('view_html') ?: TRUE,
			);
	}

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