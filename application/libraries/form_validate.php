<?php

class Form_validate
{

  public $message = array();

  public function required( $data, $label )
  {
    if( empty( $data ) )
    {
      $this->message[] = "{$label} is required.";
      return FALSE;
    }
    else
    {
      return TRUE;
    }
  }
  
  public function email( $data, $label )
  {
  	$isValid = true;
  	$atIndex = strrpos($data, "@");
  	if (is_bool($atIndex) && !$atIndex)
  	{
  		$isValid = false;
  	}
  	else
  	{
  		$domain = substr($data, $atIndex+1);
  		$local = substr($data, 0, $atIndex);
  		$localLen = strlen($local);
  		$domainLen = strlen($domain);
  		if ($localLen < 1 || $localLen > 64)
  		{
  			// local part length exceeded
  			$isValid = false;
  		}
  		else if ($domainLen < 1 || $domainLen > 255)
  		{
  			// domain part length exceeded
  			$isValid = false;
  		}
  		else if ($local[0] == '.' || $local[$localLen-1] == '.')
  		{
  			// local part starts or ends with '.'
  			$isValid = false;
  		}
  		else if (preg_match('/\\.\\./', $local))
  		{
  			// local part has two consecutive dots
  			$isValid = false;
  		}
  		else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
  		{
  			// character not valid in domain part
  			$isValid = false;
  		}
  		else if (preg_match('/\\.\\./', $domain))
  		{
  			// domain part has two consecutive dots
  			$isValid = false;
  		}
  		else if(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local)))
  		{
  			// character not valid in local part unless 
  			// local part is quoted
  			if (!preg_match('/^"(\\\\"|[^"])+"$/',
   				str_replace("\\\\","",$local)))
  			{
  				$isValid = false;
  			}
  		}
  		if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
  		{
  			// domain not found in DNS
  			$isValid = false;
  		}
  	}
    
    if( ! $isValid )
    {
      $this->message[] = "{$label} must be a valid email address.";
    }
    
  	return $isValid;
    
  }
  
  public function min_length( $data, $label, $length )
  {
    if( strlen($data) < $length )
    {
      $this->message[] = "{$label} must be at least {$length} characters long.";
      return FALSE;
    }
    else
    {
      return TRUE;
    }
  }
  
  public function max_length( $data, $label, $length )
  {
    if( strlen($data) > $length )
    {
      $this->message[] = "{$label} must be less than {$length} characters long.";
      return FALSE;
    }
    else
    {
      return TRUE;
    }
  }
  
  public function match( $original, $match, $original_label, $match_label )
  {
    if( $original != $match )
    {
      $this->message[] = "{$match_label} must match {$original_label}.";
      return FALSE;
    }
    else
    {
      return TRUE;
    }
  }
  
}
