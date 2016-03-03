<?php

namespace Colecao\Models;

class ModelBase
{
	/**
	 * DB Connector
	 * @var \Application\Db
	 */
	protected $db;

	/**
	 * Session
	 * @var \Phalcon\Session\Adapter\Files
	 */
	protected $session;


	function __construct($module = null){
		
		if (is_null($module)) $module = 'colecao';

		$this->db = new \Application\Db($module);

	}



}
