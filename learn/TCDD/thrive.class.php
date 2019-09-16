<?php
if(!defined("THANKSTHRIVE"))die();

if(file_exists("config.php")) require_once("config.php");
class THRIVE{
	private $DB;
	private $ordersTable;
	private $sitesTable;
	function __construct($install = false){
		if($install === false){
			$this->ordersTable = DB_ORDERS_TABLE;
			$this->sitesTable = DB_SITES_TABLE;
			$this->productsTable = DB_PRODUCTS_TABLE;
			$this->DB = new dbObject;
		}
	}
	
	public function checkHash(){
		// print_r($_GET);
		$pid = (isset($_GET["thrivecart"]["product_id"])?$_GET["thrivecart"]["product_id"]:$_GET["thrivecart"]["base_product"]);
		// echo $pid;
		$site = $this->getSite_by_product($pid);
		// print_r($site);
		if(!$site) return false;
		$my_thrivecart_secret = trim($site['thrivecart_secret']); 
		$hash = trim($_GET['thrivecart_hash']); // This is the hash as provided by ThriveCart

		/* Check if the hash exists, and is 32 characters long */
		if(empty($hash) || strlen($hash) !== 32) {
			return false;
		}

		/* Check that some order data has been passed along too */
		if(empty($_GET['thrivecart']) || !is_array($_GET['thrivecart'])) {
			return false;
		}

		/* Verify the hash matches the data provided */
		$thrivecart = $_GET['thrivecart'];
		ksort($thrivecart);
		array_walk_recursive($thrivecart, function(&$i) { $i = rawurlencode($i); });
		$local_hash = md5(implode('__', array($my_thrivecart_secret, strtoupper(json_encode($thrivecart)))));
		if($hash !== $local_hash) {
			return false;
		}
		return true;
	}
	
	private function save_DBConfig($mysql_host ,$mysql_user, $mysql_password, $DB_name, $DB_OrdersPrefix, $adminuname, $adminpass, $email = "", $key = "", $hash = "", $thrivecart_store_id = ""){
		$config = "<?php if(!defined(\"THANKSTHRIVE\"))die(); \n";
		$config .= "define('DB_HOST','$mysql_host');\n";
		$config .= "define('DB_USER','$mysql_user');\n";
		$config .= "define('DB_PASSWORD','$mysql_password');\n";
		$config .= "define('DB_NAME','$DB_name');\n";
		$config .= "define('DB_TABLE_PREFIX','$DB_OrdersPrefix');\n";
		$config .= "define('DB_ORDERS_TABLE','{$DB_OrdersPrefix}orders');\n";
		$config .= "define('DB_SITES_TABLE','{$DB_OrdersPrefix}sites');\n";
		$config .= "define('DB_PRODUCTS_TABLE','{$DB_OrdersPrefix}products');\n";
		$config .= "define('ADMIN_UNAME','$adminuname');\n";
		$config .= "define('ADMIN_PASS','$adminpass');\n";
		$config .= "define('EMAIL','".trim($email)."');\n";
		$config .= "define('KEY','$key');\n";
		$config .= "define('HASH','$hash');\n";
		$config .= "define('STOREID','$thrivecart_store_id');\n";
		$config .= "?>";
		file_put_contents("config.php", $config);
	}
	
	function save_TrackerConfig(){}
	function save_ACConfig(){}
	
	function loadTrackerConfig(){}
	function loadACConfig(){}
	
	public function install($mysql_host ,$mysql_user, $mysql_password, $DB_name, $DB_OrdersPrefix="", $adminuname="", $adminpass="", $email, $key, $thrivecart_store_id){
		if($adminuname == "" || $adminpass == "" || $thrivecart_store_id == "") return "All fields are required!. Please provide Admin Username and Password";

		$hash = $this->getInstallHash($email, $key, $thrivecart_store_id, $adminuname, $adminpass, false);
		if($hash == false) return "Invalid License.";
		$this->DB = new dbObject($mysql_host ,$mysql_user, $mysql_password, $DB_name);
		if($this->DB->connected === false || $DB_OrdersPrefix == "") return "All fields are required!. Please provide correct Database information";
		$this->ordersTable = $DB_OrdersPrefix."orders";
		$this->sitesTable = $DB_OrdersPrefix."sites";
		$this->productsTable = $DB_OrdersPrefix."products";

		$this->save_DBConfig($mysql_host ,$mysql_user, $mysql_password, $DB_name, $DB_OrdersPrefix, $adminuname, $adminpass, $email, $key, $hash, $thrivecart_store_id);
		
		$cr = $this->createTable();
		if($cr === false){
			if(file_exists("config.php"))unlink("config.php");
			return "An Error Occured during installation. Please contact Developer.";
		}else{
			$hash = $this->getInstallHash($email, $key, $thrivecart_store_id, $adminuname, $adminpass, true);
			$this->save_DBConfig($mysql_host ,$mysql_user, $mysql_password, $DB_name, $DB_OrdersPrefix, $adminuname, $adminpass, $email, $key, $hash,$thrivecart_store_id);
		}
		return true;
	}
	
	public function createThanksZip(){
		// Lets make a copy of the folder thanks first!
		$dir = __DIR__;
		if(!file_exists("../temp_thanks"))mkdir("../temp_thanks");
		foreach (glob($dir . "/thanks/*") as $file) {
			if(!is_dir($file)){
				$new_filename =  basename($file);
				copy($file, "../temp_thanks/".$new_filename);
			}else{
				$dir_name =  basename($file);
				mkdir("../temp_thanks/".$dir_name);
				foreach(glob($file."/*") as $f) {
					$new_filename =  basename($f);
					copy($f, "../temp_thanks/".$dir_name."/".$new_filename);
				}
			}
		}
		$thanks_codes = file_get_contents("../temp_thanks/thanks.php");
		
		$thanks_codes = str_replace("[TO_BE_REPLACED_BY_PHP_SCRIPT]", "//".$_SERVER["HTTP_HOST"].str_replace("/admin/index.php","",$_SERVER["SCRIPT_NAME"])."/", $thanks_codes);
		// echo $thanks_codes;
		file_put_contents("../temp_thanks/thanks.php", $thanks_codes);
		 
		
		$zip = new ZipArchive;
		$zip->open('../downloads/custom_thrive_success_pages.zip', ZipArchive::CREATE);
		
		foreach (glob($dir . "/temp_thanks/*") as $file) {
			if(!is_dir($file)){
				$new_filename =  basename($file);
				$zip->addFile($file ,str_replace($dir."/temp_thanks/","",$file));
			}else{
				foreach(glob($file."/*") as $f) {
					$new_filename =  basename($f);
					$zip->addFile($f ,str_replace($dir."/temp_thanks/","",$f));
				}
			}
		}           
		$zip->close();
	}
	
	public function alterTables(){
		$show = $this->DB->query("ALTER TABLE `".$this->ordersTable."` CHANGE `domain` `base_product` int(11) NOT NULL;");
		print_r($show);
	}
	
	public function showTable($table){
		echo "SHOW CREATE TABLE `".$table."`";
		$show = $this->DB->querySelect("SHOW CREATE TABLE `".$table."`");
		print_r($show);
	}
	
	private function createTable(){
		if(!$this->ordersTable || !$this->productsTable || !$this->sitesTable) return false;
		$create1 = $this->DB->query("CREATE TABLE IF NOT EXISTS `".$this->ordersTable."` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `base_product` int(11) NOT NULL,
			  `affiliate_id` varchar(250) NOT NULL,
			  `reference` varchar(250) NOT NULL,
			  `order_id` int(11) NOT NULL,
			  `order_total` int(11) NOT NULL,
			  `email` varchar(250) NOT NULL,
			  `coupon_id` int(11) NOT NULL,
			  `coupon_code` varchar(150) NOT NULL,
			  `details` blob NOT NULL,
			  `hash` blob NOT NULL,
			  `order_date` datetime NOT NULL,
			  `processed` int(1) NOT NULL,
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `order_id` (`order_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;", true);
		
		if($create1!== false){
			$create2 = $this->DB->query("CREATE TABLE IF NOT EXISTS `".$this->productsTable."` (
			  `id` int(11) NOT NULL,
			  `site_id` int(11) NOT NULL,
			  `name` varchar(1000) NOT NULL,
			  `wishlist_id` varchar(20) NOT NULL,
			  `wishlist_name` varchar(500) NOT NULL,
			  `dashboard_title` varchar(500) NOT NULL,
			  `dashboard_url` varchar(500) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;", true);	
		}else{
			return false;
		}
		
		if($create2!== false){
			$create3 = $this->DB->query("CREATE TABLE IF NOT EXISTS `".$this->sitesTable."` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `thrivecart_secret` varchar(150) NOT NULL,
			  `name` varchar(500) NOT NULL,
			  `wordpress` varchar(1000) NOT NULL,
			  `membership` varchar(50) NOT NULL,
			  `details` longtext NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;", true);
		}else{
			$this->DB->query("DROP TABLE IF EXISTS `".$this->ordersTable."`;");
			$this->DB->query("DROP TABLE IF EXISTS `".$this->productsTable."`;");
			return false;
		}
	}
	
	/* $thrive is the entire request from Thrive */
	public function recordOrder($thrive = ""){
		if($thrive == "") return;
		extract($thrive);
		$o = $this->get_order($order_id);
		if($o === false){
			$detail = json_encode($thrive);
			$qry = "INSERT INTO `".$this->ordersTable."` SET 
			`order_id` = ".intval($order_id).",".
			"`order_total` = ".(floatval($order["total"])/100).",".
			"`base_product` = ".intval($base_product).",".
			"`email` = '".addslashes($customer["email"])."',".
			"`coupon_id` = ".intval(@$coupon_id).",".
			"`coupon_code`  = '".addslashes(@$coupon_code)."',".
			"`details` = '".addslashes($detail)."',".
			"`processed` = 0,".
			"`order_date` = '".$order_date."'";
			$this->DB->query($qry);
		}else{
			// if($event == "order.success") die();
			if($event == "order.success"){
				// Do nothing
			}else{
				$thrive["rec_date"] = Date("Y-m-d h:m:iA");
				$details = json_decode($o["details"], true);
				$details["update"][] = $thrive;
				$d = json_encode($details);
				$qry = "Update `".$this->ordersTable."` SET `details` = '".addslashes($d)."' WHERE `order_id` = ".intval($order_id);
				$this->DB->query($qry);
			}
		}
	}
	
	public function get_order($order_id){
		if(intval($order_id) == 0) return false;
		
		$qry = "SELECT * FROM `".$this->ordersTable."` WHERE `order_id`=".intval($order_id);
		$res = $this->DB->querySelect($qry);
		if(isset($res[0])) return $res[0];
		else return false;
	}
	
	function markprocessed($order_id){
		$qry = "UPDATE `".$this->ordersTable."` SET  `processed`=1 WHERE `order_id`=".intval($order_id);
		$this->DB->query($qry);
	}
	
	function fillMissing($order_id, $hash){
		$qry = "UPDATE `".$this->ordersTable."` SET `affiliate_id`='".addslashes($hash["affiliate_id"])."', `reference`='".addslashes($hash["ref"])."', `hash`='".addslashes(json_encode($hash))."' WHERE `order_id`=".intval($order_id);
		$this->DB->query($qry);
	}
	
	public function addSite($site_name = ""){
		if($site_name == "") return false;
		$globals = get_global_settings();
		if(!isset($globals["multipleDeepDataIntegrationSettings"])) $globals["multipleDeepDataIntegrationSettings"] = 1;
		$add = false;
		if($globals["multipleDeepDataIntegrationSettings"] == 1){
			$add = true;
		}else{
			$sites = $this->getSites();
			if(count($sites) <=0 ) $add = true;
			else $add = false;
		}
		if($add === true){
			$qry = "INSERT INTO `".$this->sitesTable."` SET `name` = '".addslashes($site_name)."'";
			// echo $qry;
			$i = $this->DB->query($qry);
			if($i){
				return $this->DB->last_insert_id();
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	public function deleteSite($siteid = 0){
		if($siteid == 0) return false;
		$qry = "DELETE FROM `".$this->sitesTable."` WHERE id=".intval($siteid);
		$res = $this->DB->query($qry);
		return $res;
	}
	public function updateSite($site = false){
		if($site == false) return false;
		if(!isset($site["AC"]["ThriveCartCustomFields"]) || count(@$site["AC"]["ThriveCartCustomFields"])<=0){
			$osite = $this->getSite(@$site["id"]);
			$odetails = json_decode($osite["details"], true);
			$site["AC"]["ThriveCartCustomFields"] = @$odetails["AC"]["ThriveCartCustomFields"];
		}
		$qry = "UPDATE `".$this->sitesTable."` SET 
			`thrivecart_secret`='".addslashes(@$site["APIs"]["ThriveCart"]["secert"])."',
			`name`='".addslashes(@$site["APIs"]["ThriveCart"]["name"])."',
			`wordpress`='".addslashes(@$site["APIs"]["Membership"]["url"])."',
			`membership`='".@$site["APIs"]["Membership"]["plugin"]."',
			`details`='".json_encode(@$site)."'
			WHERE id=".intval(@$site["id"])."
			";
		$res = $this->DB->query($qry);
		// echo $qry;
		$values = array();
		$pids = array();
		foreach((array)@$site["Products"] as $p){
			$pids[] = $p["product_id"];
			$values[] = "('".addslashes($p["product_id"])."', 
				".intval($site["id"]).", 
				'".addslashes($p["product_name"])."', 
				'".addslashes($p["wishlist_id"])."', 
				'".addslashes($p["wishlist_name"])."', 
				'".$p["dashboard_title"]."', 
				'".$p["dashboard_url"]."')";
		}
		if(count($pids)>0){
			$del = "DELETE FROM `".$this->productsTable."` where `site_id`=".intval($site["id"])." AND `id` not in (".implode(",", $pids).")";
			$res = $this->DB->query($del);
		}
		
		if(count($values) > 0){
			$iu = "INSERT INTO `".$this->productsTable."` (id,site_id,name,wishlist_id,wishlist_name,dashboard_title,dashboard_url) 
				VALUES 
				".implode(", ", $values)."
				ON DUPLICATE KEY UPDATE name=values(`name`), wishlist_id=values(`wishlist_id`), wishlist_name=values(`wishlist_name`), dashboard_title=values(`dashboard_title`), dashboard_url=values(`dashboard_url`)";
			$res = $this->DB->query($iu);
		}
		return $res;
	}
	
	public function getSites(){
		$qry = "SELECT * FROM `".$this->sitesTable."`";
		$res = $this->DB->querySelect($qry);
		return $res;
	}
	
	public function getSite_by_product($base_product = ""){
		$globals = get_global_settings();
		$single = false;
		if(!isset($globals["multipleDeepDataIntegrationSettings"])) $globals["multipleDeepDataIntegrationSettings"] = 1;
		if($globals["multipleDeepDataIntegrationSettings"] == 1 && $base_product == "" ) return false;
		elseif($globals["multipleDeepDataIntegrationSettings"] == 0) $single = true;
		
		if($single === false){
			$qry = "SELECT st.* FROM `".$this->sitesTable."` as st LEFT JOIN `".$this->productsTable."` as pt on pt.site_id = st.id WHERE pt.`id`='".addslashes($base_product)."'";
		}else{
			$qry = "SELECT st.* FROM `".$this->sitesTable."` as st ORDER BY st.id ASC LIMIT 0,1";
		}
		$res = $this->DB->querySelect($qry);
		return @$res[0];
	}
	
	public function getSite($siteid = 0){
		if(intval($siteid) == 0){
			$sites = $this->getSites();
			return @$sites[0];
		}else{
			$qry = "SELECT * FROM `".$this->sitesTable."` WHERE id=".$siteid;
			$res = $this->DB->querySelect($qry);
			return $res[0];
		}
	}
	
	public function getTableRecords($table){
		$qry = "SELECT * FROM `".$table."`";
		$res = $this->DB->querySelect($qry);
		return $res;
	}
	
	public function getSiteOrders($siteid = 0, $format = "array"){
		if($siteid === 0) return false;
		if($siteid == "all"){
			$qry = "SELECT * FROM `".$this->ordersTable."`";
		}else{
			// $qry = "SELECT st.id as siteid,st.base_product,st.wordpress, ot.* FROM `".$this->sitesTable."` as st LEFT JOIN `".$this->ordersTable."` as ot on st.base_product = ot.base_product WHERE st.id=".intval($siteid)." AND ot.id IS NOT NULL";
			$qry = "SELECT pt.site_id as siteid, ot.* FROM `".$this->ordersTable."` as ot LEFT JOIN `".$this->productsTable."` as pt on pt.id=ot.base_product WHERE pt.site_id=".intval($siteid);
		}
		$res = $this->DB->querySelect($qry);
		if($format == "json"){
			$res = json_encode($res);
		}
		return $res;
	}
	
	public function checkThriveSecret($thrivecart_secret = ""){
		if($thrivecart_secret == "")return false;
		$qry = "SELECT * FROM `".$this->sitesTable."` WHERE `thrivecart_secret` = '".addslashes($thrivecart_secret)."'";
		$res = $this->DB->querySelect($qry);
		return !empty($res);
	}
	
	public function sendAdminAccount(){
		// $context	= stream_context_create(
			// array('http' =>
				// array(
					// 'method'	=> 'POST',
					// 'header'	=> 'Content-type: application/x-www-form-urlencoded',
					// 'content' => http_build_query(array())
				// )
			// )
		// );
		$url = $this->composeRequest("sendAdminAccount");
		// $ret = filex_get_contents($url, false, $context);
		$ret = curl_get_contents($url, "POST");
		return $ret;
	}
	public function sendPasswordRecovery(){
		$recovery_key = md5(date("U"));
		// $context	= stream_context_create(
			// array('http' =>
				// array(
					// 'method'	=> 'POST',
					// 'header'	=> 'Content-type: application/x-www-form-urlencoded',
					// 'content' => http_build_query(array())
				// )
			// )
		// );
		file_put_contents("recovery_key.php", '<?php if(!defined("THANKSTHRIVE"))die(); define("RECOVERY_KEY","'.$recovery_key.'"); ?>');
		$url = $this->composeRequest("sendPasswordRecovery")."&recovery_key=".$recovery_key;
		// $ret = filex_get_contents($url, false, $context);
		$ret = curl_get_contents($url, "POST");
		return $ret;
	}
	public function composeRequest($request){
		$domain = $_SERVER["SERVER_NAME"];
		$dir = __DIR__;
		$request = array("request"=> $request, "license"=>array("email" => EMAIL, "key" => KEY, "store_id" => STOREID, "hash" => HASH, "domain" => $domain, "dir" => $dir, "uname" => ADMIN_UNAME , "upass"=>ADMIN_PASS));
		return "http://www.customthrivecartpages.com/license/?".http_build_query($request);
	}
	
	public function featureRequest($feature = ""){
		if($feature == "") return 0;

		// $context	= stream_context_create(
			// array('http' =>
				      // array(
					      // 'method'	=> 'POST',
					      // 'header'	=> 'Content-type: application/x-www-form-urlencoded',
					      // 'content' => http_build_query(array("feature" => $feature))
				      // )
			// )
		// );
		$url = $this->composeRequest("feature_request");
		// $ret = filex_get_contents($url, false, $context);
		$ret = curl_get_contents($url, "POST", array("feature" => $feature));
		return $ret;

	}
	
	public function updateThriveCartCustomFields($site_id = "", $cf = array()){
		if(intval($site_id) > 0 && count($cf) > 0){
			$site = $this->getSite($site_id);
			$details = json_decode($site["details"], true);
			foreach($cf as $key => $f){
				$cf[$key] =  ucwords(str_replace("-", " ", $key));
				$details["AC"]["ThriveCartCustomFields"][$key] = ucwords(str_replace("-", " ", $key));
			}
			// $details["AC"]["ThriveCartCustomFields"] = array();
			$qry = "UPDATE `".$this->sitesTable."` SET `details`='".json_encode($details)."' WHERE id='".intval($site_id)."'";
			$res = $this->DB->query($qry);
			return $cf;
		}else{
			return false;
		}
	}
	public function validateLicense($email, $key, $thrivecart_store_id){
		if($email && $key && $thrivecart_store_id){
			// $context	= stream_context_create(
				// array('http' =>
						  // array(
							  // 'method'	=> 'GET',
							  // 'header'	=> 'Content-type: application/x-www-form-urlencoded',
						  // )
				// )
			// );
			$request = array("request"=>"validate", "license"=>array("email" => $email, "key" => $key, "store_id" => $thrivecart_store_id ));
			// $url = "http://www.customthrivecartpages.com/license/?".http_build_query($request);
			// $res = filex_get_contents($url, false, $context);
			$url = "http://www.customthrivecartpages.com/license/";
			$res = curl_get_contents($url, "GET", $request);
			return $res;
		}else{
			return array("result" => 0, "message" => "Invalid Request!.");
		}
	}
	private function getInstallHash($email = "", $key = "", $store_id, $uname, $upass, $record = true){
		if(!$email || !$key) return false;
		// $context	= stream_context_create(
			// array('http' =>
				      // array(
					      // 'method'	=> 'GET',
					      // 'header'	=> 'Content-type: application/x-www-form-urlencoded',
				      // )
			// )
		// );
		$domain = $_SERVER["SERVER_NAME"];
		$dir = __DIR__;
		$request = array("request"=>"install", "license"=>array("email" => $email, "key" => $key, "store_id" => $store_id, "domain" => $domain, "dir" => $dir, "uname" => $uname, "upass"=>$upass), "record" => $record);
		// $url = "http://www.customthrivecartpages.com/license/?".http_build_query($request);
		// $res = json_decode(filex_get_contents($url, false, $context), true);
		
		$url = "http://www.customthrivecartpages.com/license/";
		$res = json_decode(curl_get_contents($url, "GET", $request), true);
		if(@$res["result"] == 1 && @$res["hash"]){
			return $res["hash"];
		}else{
			return false;
		}
	}
	function sqlmode(){
		$res = $this->DB->query("SELECT @@SESSION.sql_mode;");
		return $res;
	}
}

Class dbObject{
	var $link="";
	var $db_host="";
	var $db_user="";
	var $db_password="";
	var $DB_name="";
	var $connected = false;

	function __construct($mysql_host=DB_HOST,$mysql_user=DB_USER,$mysql_password=DB_PASSWORD,$DB_name=DB_NAME){
		$this->db_host=$mysql_host;
		$this->db_user=$mysql_user;
		$this->db_password=$mysql_password;
		$this->connected = $this->dbConnect();
		$this->select_db($DB_name);
		mysqli_query($this->link, "SET SESSION sql_mode = ''");
	}

	private function dbConnect(){
		$this->link = @mysqli_connect($this->db_host, $this->db_user,$this->db_password )or die("Unable to connect to the Database. Please check and correct Database Informatioin.");
		return $this->link;
	}

	private function select_db($DB_name){
		$this->DB_name=mysqli_select_db($this->link, $DB_name) or die(mysqli_error($this->link));
	}

	function querySelect($query){
		$result = mysqli_query($this->link, $query) or die('Query failed: ' . mysqli_error($this->link));
		$returnAssoc=Array();
		$a=0;
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$returnAssoc[$a]=$row;
			$a++;
		}
		return ($returnAssoc);
	}

	function last_insert_id(){
		$last_id = mysqli_insert_id($this->link);
		return $last_id;
	}

	function query($query, $return_on_error = false){
		$result = mysqli_query($this->link, $query);
		if(!$result && $return_on_error === false)
			die('Query failed: ' . mysqli_error($this->link));
		elseif(!$result && $return_on_error === true)
			return false;
		return $result;
	}
}
?>