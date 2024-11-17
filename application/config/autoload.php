<?php
defined('BASEPATH') OR exit('No direct script access allowed');
  
$autoload['packages'] = array();
 
$autoload['libraries'] = array( 'smartie' => 'smarty', 'session', 'email','form_validation', 'Currency', 'Software');
 
$autoload['drivers'] = array();
 
$autoload['helper'] = array('url', 'file', 'language', 'currency', 'software', 'array');

$autoload['config'] = array( 'twig' );
 
$autoload['language'] = array();
 
$autoload['model'] = array('Base_model');
