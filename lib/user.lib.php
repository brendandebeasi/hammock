<?

/*USER LIBRARY*/

class User {

    static private $_i;
    //create a singleton
    static public function singleton() {
        return isset(self::$_i) ? self::$_i : self::$_i = new self();
    }

    //constructor that inits the mongo database connection
    function __construct() {
        global $db,$util,$user;

        if(empty($db)) {
            $mongo = new MongoClient(/*"mongodb://external.com"*/);
            $this->db = $mongo->hammock;
        } else {
            $this->db = $db;
        }

        if(empty($util)) { $this->util = Util::Singleton(); } else { $this->util = $util; }
		
		$this->collection = $this->db->users;
        $this->ticketSep = '~!~';
    }
    public function isUnique($user) {
	    $userByUsername = $this->getByUsername($user['username']);
        $userByEmail = $this->getByEmail($user['email']);
	    if(!$userByUsername && !$userByEmail) return true;
	    else return false;
    }
	public function validate($user) {
		return (!empty($user['first_name']) &&
			!empty($user['last_name']) &&
			!empty($user['username']) &&
			!empty($user['email']) &&
			!empty($user['password'])) ? true : false;
	}
	
	public function create($new_user) {
		if($this->validate($new_user)) {
			$new_user['password'] = $this->hashPassword($new_user['password']);
			$result = $this->collection->insert($new_user);
			
			if($result) return $new_user;
			else return false;			
		} else {
			return false;
		}

	}
	
	public function delete($user_id) {
		try {
			$user_id_mongo = new MongoId($user_id);   
		} catch (MongoException $ex) {
		    return false;
		}
		$result = $this->collection->remove(array('_id' => $user_id_mongo), array("justOne" => true));
		if($result) return true;
		else return false;
	}
	public function update($user_id,$user_data) {
		try {
			$user_id_mongo = new MongoId($user_id);   
		} catch (MongoException $ex) {
		    return false;
		}
        if(isset($user_data['password'])) $user_data['password'] = $this->hashPassword($user_data['password']);
		$result = $this->collection->update(array('_id' => $user_id_mongo), array('$set' => $user_data));
		if($result) return true;
		else return false;
	}
	function getById($user_id) {
		try {
			$user_id_mongo = new MongoId($user_id);   
		} catch (MongoException $ex) {
		    return false;
		}
		$user_to_return = $this->collection->findOne(array('_id' => $user_id_mongo));
		if($user_to_return === NULL) return false;
		else return $user_to_return;
	}
    function getByUsername($username) {
        $user = $this->collection->findOne(array('username' => $username));
        if($user === NULL) return false;
        else return $user;
    }
    function getByEmail($email) {
        $user = $this->collection->findOne(array('email' => $email));
        if($user === NULL) return false;
        else return $user;
    }
    function getByLoginCredentials($login,$pass) {
        $user = $this->getByUsername($login);
        if(!$user) $user = $this->getByEmail($login);
        if(!$user) return false;
        elseif($this->isPasswordEqualToHash($pass,$user['password'])) {
            return $user;
        }
        else return false;
    }
    public function generateTicket($user_mongo_id) {
        $ticket = time().$this->ticketSep.hash("sha256",$user_mongo_id . '-hammock');
        $ticket = $this->util->encrypt($ticket);
        return $this->util->asc2hex($ticket);
    }
    public function isTicketValid($user_mongo_id,$user_ticket) {
        $sep = '~!~';
        $user_ticket = $this->util->hex2asc($user_ticket);
        $decryptedTicket = $this->util->decrypt($user_ticket);

        $explodedTicket = explode($this->ticketSep, $decryptedTicket);
        $user_ticket = end($explodedTicket);


        $ticket = hash("sha256",$user_mongo_id . '-hammock');

        if(trim($ticket) == trim($user_ticket)) {
            return true;
        } else {
            return false;
        }

    }
    public function hashPassword($password) {
      return hash('sha256',$password . '-callie-ham');
    }
    public function isPasswordEqualToHash($plain_password,$hash_password) {
        $plain_password = $this->hashPassword($plain_password);
        if($plain_password == $hash_password) return true;
        else return false;
    }


}