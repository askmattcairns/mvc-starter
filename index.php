<?php

// Fix headers to keep the Facebook session in place.
header('p3p: CP="ALL DSP COR PSAa PSDa OUR NOR ONL UNI COM NAV"');

$default_controller = 'contest';
$default_function = 'index';
$param = NULL;

// Load Controller
$controller = $default_controller;
if ( 
  array_key_exists( 'c', $_GET ) 
  && file_exists( 'controllers/' . $_GET['c'] . '.php' ) 
)
{
  $controller = $_GET['c'];
}
$controller_path = 'application/controllers/' . $controller . '.php';
if ( file_exists( $controller_path ) )
{
  require_once( $controller_path );
}
else
{
  die( 'the controller at ' . $controller_path . ' does not exist.' );
}

$controller_name = ucfirst($controller);
$the_controller = new $controller();

// Get Function
if( array_key_exists( 'f', $_GET ) )
{
  $function = $_GET['f'];
  
  if( ! method_exists( $the_controller, $function ) )
  {
    $function = 'index';
  }
  
  if( array_key_exists( 'type', $_GET ) )
  {
    $param = $_GET['type'];
  }
}

// Execute
$the_controller->$function( $param );
