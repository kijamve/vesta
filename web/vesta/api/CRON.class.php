<?php
/*
 * CRON 
 * 
 * @author vesta, http://vestacp.com/
 * @copyright vesta 2010 
 */
class CRON extends AjaxHandler {
  function getListExecute($request) 
  {
    $_user = 'vesta';
    $reply = array();
    
    $result = Vesta::execute(Vesta::V_LIST_CRON_JOBS, array($_user, Config::get('response_type')));
    
    //	echo '<pre>';
    //	print_r($result);

    foreach($result['data'] as $id => $record)
      {
	$reply[$id] = array(
			    'CMD' => $record['CMD'],
			    'MIN' => $record['MIN'],
			    'HOUR' => $record['HOUR'],
			    'DAY' => $record['DAY'],
			    'MONTH' => $record['MONTH'],
			    'WDAY' => $record['WDAY'],
			    'SUSPEND' => $record['SUSPEND'],
			    'DATE' => date(Config::get('ui_date_format', strtotime($record['DATE'])))
			    );
      }
    
    if(!$result['status'])
      $this->errors[] = array($result['error_code'] => $result['error_message']);
    
    return $this->reply($result['status'], $reply);
  }


    
  function addExecute($request) 
  {
    $r = new Request();
    $_s = $r->getSpell();
    $_user = 'vesta';
    
    $params = array(
		    'USER' => $_user,
		    'MIN' => $_s['MIN'],
		    'HOUR' => $_s['HOUR'],
		    'DAY' => $_s['DAY'],
		    'MONTH' => $_s['MONTH'],
		    'WDAY' => $_s['WDAY'],
		    'CMD' => $_s['CMD']
		    );
    
    $result = Vesta::execute(Vesta::V_ADD_CRON_JOB, $params);


    if($_s['REPORTS'])
      {
	$result = array();
	$result = Vesta::execute(Vesta::V_ADD_SYS_USER_REPORTS, array('USER' => $_user));
	if(!$result['status'])
	  {
	    $this->status = FALSE;
	    $this->errors['REPORTS'] = array($result['error_code'] => $result['error_message']);
	  }
      }

    if(!$result['status'])
      $this->errors[] = array($result['error_code'] => $result['error_message']);
    
    return $this->reply($result['status'], $result['data']);
  }
  
  
    
  function delExecute($request) 
  {
    $r = new Request();
    $_s = $r->getSpell();
    $_user = 'vesta';
    
    $params = array(
		    'USER' => $_user,
		    'JOB' => $_s['JOB']
		    );
    
    $result = Vesta::execute(Vesta::V_DEL_CRON_JOB, $params);
    
    if(!$result['status'])
      $this->errors[] = array($result['error_code'] => $result['error_message']);
    
    return $this->reply($result['status'], $result['data']);
  }
  
    
  
  function changeExecute($request)
  {
    $r = new Request();
    $_s = $r->getSpell();
    $_old = $_s['old'];
    $_new = $_s['new'];

    $_user = 'vesta';
    $_JOB = $_new['JOB'];
    
    $result = array();
    $params = array(
		    'USER' => $_user,
		    'JOB' => $_JOB,
		    'MIN' => $_new['MIN'],
		    'HOUR' => $_new['HOUR'],
		    'DAY' => $_new['DAY'],
		    'MONTH' => $_new['MONTH'],
		    'WDAY' => $_new['WDAY'],
		    'CMD' => $_new['CMD']
		    );

    $result = Vesta::execute(Vesta::V_CHANGE_CRON_JOB, $params);


    
    if(!$result['status'])
      $this->errors[] = array($result['error_code'] => $result['error_message']);
    
    return $this->reply($result['status'], $result['data']);
  }



  function suspendExecute($request)
  {
    $r = new Request();
    $_s = $r->getSpell();

    $_user = 'vesta';
    $_JOB = $_s['JOB'];
    
    $params = array(
		    'USER' => $_user,
		    'JOB' => $_JOB
		    );
    
    $result = Vesta::execute(Vesta::V_SUSPEND_CRON_JOB, $params);
    
    if(!$result['status'])
      $this->errors[] = array($result['error_code'] => $result['error_message']);
    
    return $this->reply($result['status'], $result['data']);
  }



  function unsuspendExecute($request)
  {
    $r = new Request();
    $_s = $r->getSpell();

    $_user = 'vesta';
    $_JOB = $_s['JOB'];
    
    $params = array(
		    'USER' => $_user,
		    'JOB' => $_JOB
		    );
    
    $result = Vesta::execute(Vesta::V_UNSUSPEND_CRON_JOB, $params);
    
    if(!$result['status'])
      $this->errors[] = array($result['error_code'] => $result['error_message']);
    
    return $this->reply($result['status'], $result['data']);
  }



  function suspendAllExecute($request)
  {
    $r = new Request();
    $_s = $r->getSpell();

    $_user = 'vesta';
    $_JOB = $_s['JOB'];
    
    $params = array(
		    'USER' => $_user
		    );
    
    $result = Vesta::execute(Vesta::V_SUSPEND_CRON_JOBS, $params);
    
    if(!$result['status'])
      $this->errors[] = array($result['error_code'] => $result['error_message']);
    
    return $this->reply($result['status'], $result['data']);
  }



  function unsuspendAllExecute($request)
  {
    $r = new Request();
    $_s = $r->getSpell();

    $_user = 'vesta';
    
    $params = array(
		    'USER' => $_user
		    );
    
    $result = Vesta::execute(Vesta::V_UNSUSPEND_CRON_JOBS, $params);
    
    if(!$result['status'])
      $this->errors[] = array($result['error_code'] => $result['error_message']);
    
    return $this->reply($result['status'], $result['data']);
  }
   
}
