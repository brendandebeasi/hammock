<?
define("DEV_BUILD", 1); //Define the dev parameter

if(DEV_BUILD) {
	error_reporting(E_ERROR | E_PARSE);
} else {
	error_reporting(0);
}



if(DEV_BUILD) {
	
} else {
	
}
//Put.io
define('PUT_SECRET','4ujagnyxt57nz5gpwq8w'); // ENT APP KEY
	define('PUT_CLIENT_ID', '941'); // ENT Master Secret
	define('PUT_OAUTH_TOKEN', 'X3D7GLOE'); // ENT Master Secret

//Encryption Key
define("ENC_KEY","AD!34jsaSDFalkDD4a@25");

//neo4j inclusion / bootstrap
//require("phar://neo4jphp.phar");
//require_once($_SERVER['DOCUMENT_ROOT']."/lib/neo4j_bootstrap.php");
require_once($_SERVER['DOCUMENT_ROOT']."/lib/util.lib.php");
require_once($_SERVER['DOCUMENT_ROOT']."/lib/user.lib.php");


/*
// Neo4J classes
use Everyman\Neo4j\Client,
	Everyman\Neo4j\Transport,
	Everyman\Neo4j\Index\NodeIndex,
	Everyman\Neo4j\Relationship,
	Everyman\Neo4j\Node,
	Everyman\Neo4j\Cypher,
	Everyman\Neo4j\Path,
	Everyman\Neo4j\PathFinder,
	Everyman\Neo4j\Traversal;
*/
	


/* Instantiate the database  */
if(DEV_BUILD) {
//	$mongo = new MongoClient(/*"mongodb://external.com"*/);
} else {
//	$mongo = new MongoClient("mongodb://172.31.28.251:27017,172.31.31.102:27017,172.31.20.146:27017", array("replicaSet" => "rs0"));
}

$mongo = new MongoClient(/*"mongodb://external.com"*/);
$db = $mongo->hammock;

/* Instantiate the class libraries */
$util = Util::singleton();
$user = User::singleton();

//Neo4J
/*
if(DEV_BUILD) {
	$neo_client = new Client(new Transport('localhost', 7474));
} else {
	$neo_client = new Client(new Transport('172.31.27.92', 7474));
}
*/
/*

$neo_users = new NodeIndex($neo_client, 'users');
$neo_traversal = new Traversal($neo_client);
*/
