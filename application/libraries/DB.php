<?php

class DB {

  public $table;
  public $primary_key = 'id';

  function __construct( $table )
  {
    self::connect();
    $this->table = $table;
  }
  
  public function create( $data = array() )
  {
    $keys = "`" . implode('`,`', array_keys( $data ) ) . "`";
    $values = "'" . implode("','", array_values( $data ) ) . "'";
    $query = "INSERT INTO `{$this->table}` ({$keys}) VALUES ({$values})";
    if( $this->run( $query ) )
      return mysql_insert_id();
    else
    {
      return array(
        'success' => FALSE,
        'error'   => mysql_error()
      );
    }
  }
  
  public function read( $params = FALSE )
  {
    if( is_array( $params ) )
    {
      $elements = array();
      $keys = array_keys( $params );
      $values = array_values( $params );
      for( $i = 0; $i < count( $keys ); $i++ )
      {
        $elements[] = "`" . $keys[$i] . "` = '" . $values[$i] . "'";
      }
      $clause = implode( ' AND ', $elements );
    }
    else
    {
      $clause = "`{$this->primary_key}` = '{$params}'";
    }
    
    if( ! $params )
    {
      $query = "SELECT * FROM `{$this->table}`";
    }
    else
    {
      $query = "SELECT * FROM `{$this->table}` 
                WHERE {$clause}";
    }
    $result = $this->run( $query );
    
    $num_rows = mysql_num_rows( $result );
    
    if( $num_rows <= 0 )
    {
      return FALSE;
    }
    else if( $num_rows == 1 )
    {
      return mysql_fetch_object( $result);
    }
    else
    {
      return $result;
    }
  }
  
  public function update( $key, $data )
  {
    $updates = array();
    $keys = array_keys( $data );
    $values = array_values( $data );
    for( $i = 0; $i < count( $keys ); $i++ )
    {
      $updates[] = "`" . $keys[$i] . "` = '" . $values[$i] . "'";
    }
    $query = "UPDATE `{$this->table}` 
              SET " . implode( ', ', $updates ) . " 
              WHERE `{$this->primary_key}` = '{$key}'";
    if( $this->run( $query ) )
      return mysql_insert_id();
    else
    {
      return array(
        'success' => FALSE,
        'error'   => mysql_error()
      );
    }
  }
  
  public function run( $query )
  {
    $result = mysql_query( $query );
    if( ! $result )
    {
      return FALSE;
    }
    else
    {
      return $result;
    }
  }
  
  public function count()
  {
    $query = "SELECT COUNT(*) FROM {$this->table}";
    $count = $this->run( $query );
    $count = mysql_fetch_row($count);
    return $count[0];
  }
  
  private function connect()
  {
    include dirname( dirname(__FILE__) ) . '/config/db.php';
    // Connect
    try
    {
      mysql_connect( $config['db']['host'], $config['db']['user'], $config['db']['password'] );
      mysql_select_db( $config['db']['db_name'] );
    }
    catch( Exception $e )
    {
      echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
  }

}
