<?php
App::uses('AppModel', 'Model');

/**
 * Basic CakePHP Model to connect to the assets table
 * Version: 2.0 (11/05/18)
 * Copyright 2011-2018 Stuart J. Chalk
 */
class Asset extends AppModel
{
	public $hasMany=['Stream'];

}