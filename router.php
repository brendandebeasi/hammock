<?php
include_once("classController.php");

$server_uri = $_SERVER['REQUEST_URI'];
$paths = explode("/", $server_uri);
$base_paths = explode("?", $paths[1]);
$second_paths = explode("?", $paths[2]);
$third_paths = explode("?", $paths[3]);
$fourth_paths = explode("?", $paths[4]);
$base_switch = $base_paths[0];
$second_switch = $second_paths[0];
$third_switch = $third_paths[0];
$fourth_switch = $fourth_paths[0];
$request_method = $_SERVER['REQUEST_METHOD'];
$output = array('success'=>false,'message'=>null,'data'=>array());
//start base switch
switch($base_switch) {
	// /user/
    case "user":
    	if($second_switch == 'list') { //user/list/
	    	
    	} else {
	    	//$user_id = ($second_switch == 'id' ? $third_switch : false);// /users/id/{ID}
	    	switch($request_method) {
		        case 'POST'://create
                    $new_user = json_decode(file_get_contents('php://input'),true);
		        	if($user->isUnique($new_user)) {
			        	if($user->validate($new_user)) {
				        	$result = $user->create($new_user);
				        	if(!$result) $output['message'] = 'There was an unknown error adding the user.';
				        	else {
				        		$output['success']=true;
					        	$output['data']=$result;
				        	}
			        	} else $output['message'] = 'The user information you entered is not valid.';
		        	} else $output['message'] = 'The email or username you entered already exists in our system.';

		        	break;
	        	case 'GET'://get
	        		$user_id = $second_switch;
	        		if(!empty($user_id)) {
		        		$result = $user->getById($user_id);
		        		if(!$result) $output['message'] = 'No user found with given ID.';
		        		else {
			        		$output['success']=true;
				        	$output['data']=$result;	
		        		}
	        		} else $output['message'] = 'No user ID specified.';
		        	break;
		        case 'PUT'://update
		        	$user_id = $second_switch;
	        		if(!empty($user_id)) {
                        $new_user = json_decode(file_get_contents('php://input'),true);
	        			
	        			$result = $user->update($user_id,$new_user);
	        			if(!$result) $output['message'] = 'No user found with given ID.';
	        			else $output['success'] = true;
	        			
	        		} else $output['message'] = 'No user ID specified.';
		        	break;
	        	case 'DELETE'://delete
	        		$user_id = $second_switch;
	        		if(!empty($user_id)) {
	        			$result = $user->delete($user_id);
	        			if($result) $output['success'] = true;
	        			else $output['message'] = 'No user found with given ID.';
	        		} else $output['message'] = 'No user ID specified.';
	        		break;
			}	
    	}        
        break;
    case "session":
        switch($second_switch) {
            case 'create'://session/create/B64LOGIN/B64PASS
                $login = base64_decode($third_switch);
                $pass = base64_decode($fourth_switch);
                $session_user = $user->getByLoginCredentials($login,$pass);
                if(!$session_user) $output['message'] = 'Could not find a user with the given credentials.';
                else {
                    $output['data']['user'] = $session_user;
                    unset($outpuut['data']['user']['password']);//remove password
                    $output['data']['user_id'] = $session_user['_id']->{'$id'};
                    $output['data']['ticket'] = $user->generateTicket($session_user['_id']->{'$id'});
                    $output['success']=true;
                }
                break;
            case 'validate'://session/validate/USERID/TICKET
                $result = $user->isTicketValid($third_switch,$fourth_switch);
                if($result) {
                    $output['success'] = true;
                }
                else {
                    $output['success']= false;
                    $output['message']='Ticket is not valid.';
                }
                break;
        }
        break;

}

header('Content-type: application/json');
if($output['success'] == false) http_response_code(400);
else http_response_code(200);
echo json_encode($output);