<?php
if(!defined("THANKSTHRIVE"))die();
class SITES{
	private $thrive;
	function __construct(){
		$this->thrive = new THRIVE;
	}
	
	public function getSites($request, $format = "array"){
		$res["data"] = $this->thrive->getSites();
		switch ($format){
			case "json":
				$res = json_encode($res);
			break;
		}
		return $res;
	}
	
	public function getSite($siteid = 0){
		if($siteid == 0)return array();
		return $this->thrive->getSite($siteid);
	}
	
	public function deleteSite($siteid = 0){
		if($siteid == 0)return array();
		return $this->thrive->deleteSite($siteid);
	}
	
	public function updateSite($site = false){
		if($site == false) return false;
		return $this->thrive->updateSite($site);
	}
	
	public function addSite($base_product = ""){
		if($base_product == "") return false;
		return $this->thrive->addSite($base_product);
	}
	
	public function enableMultiple($enable = true){
		if($enable == true){
			$enable = 1;
		}else{
			$sites = $this->getSites();
			if(count($sites) <=1 ) $enable = 0;
			else return json_encode(array("success" => 0));
		}
		
		$globalsettings = get_global_settings();
		
		$globalsettings["multipleDeepDataIntegrationSettings"] = $enable;
		$res = file_put_contents("globals.json", json_encode($globalsettings));
		
		if($res === false) return json_encode(array("success" => 0));
		else return json_encode(array("success" => 1));
	}
	
	public function duplicateSite($siteid = 0, $sitename = ""){
		if(intval($siteid) <= 0 || $sitename == "") return false;
		
		$globals = get_global_settings();
		$dup = false;
		if(!isset($globals["multipleDeepDataIntegrationSettings"])) $globals["multipleDeepDataIntegrationSettings"] = 1;
		if($globals["multipleDeepDataIntegrationSettings"] == 1){
			$dup = true;
		}else{
			$sites = $this->getSites();
			if(count($sites) <=0 ) $dup = true;
			else $dup = false;
		}
		
		if($dup === false) return false;
		
		$site = $this->getSite($siteid);
		if(isset($site["id"])){
			$nid = $this->thrive->addSite($sitename);
			if($nid > 0){
				$usite = json_decode($site["details"], true);
				$usite["id"] = $nid;
				$usite["APIs"]["ThriveCart"]["name"] = $sitename;
				$res = $this->thrive->updateSite($usite);
				if($res) return $nid;
				else return false;
			}else return false;
		}else return false;
	}
	
	public function getSiteOrders($siteid = 0, $format = "array"){
		if($siteid == 0) return false;
		$globals = get_global_settings();
		if(!isset($globals["multipleDeepDataIntegrationSettings"])) $globals["multipleDeepDataIntegrationSettings"] = 1;
		if($globals["multipleDeepDataIntegrationSettings"] != 1) $siteid = "all";
		return $this->thrive->getSiteOrders($siteid, $format);
	}
	
	public function loadACData($url = "", $key = ""){
		if($url == "" || $key == "") return array();
		require_once("ac.class.php");
		$ac = new AC($url, $key);
		$res["Lists"] = json_decode($ac->getLists(), true);
		$res["cf"] = json_decode($ac->get_custom_fields("all"), true);
		$res["result_code"] = @$res["Lists"]["result_code"];
		$res["result_message"] = @$res["Lists"]["result_message"];
		unset($res["Lists"]["result_code"]);
		unset($res["Lists"]["result_message"]);
		unset($res["Lists"]["result_output"]);
		unset($res["cf"]["result_code"]);
		unset($res["cf"]["result_message"]);
		unset($res["cf"]["result_output"]);
		return $res;
	}
	
	public function getMyLatestUpdates(){
		$lu = $this->getLatestUpdates();
		if(empty($lu)) return false;
		else{
			$mlu = json_decode($_COOKIE["latestUpdate"], true);
			if(@$mlu["id"] != $lu["id"]){
				return $lu;
			}else{
				return false;
			}
		}
	}
	
	public function getLatestUpdates(){
		$context	= stream_context_create(
			array('http' =>
				      array(
					      'method'	=> 'GET',
					      'header'	=> 'Content-type: application/x-www-form-urlencoded'
				      )
			)
		);
		$url = "http://www.customthrivecartpages.com/license/";
		// $ret = filex_get_contents($url, false, $context);
		$ret = curl_get_contents($url, "GET", array("request"=>"updates"));
		$res = json_decode($ret, true);
		if($res)return $res;
		else return array();
	}
	
	public function newIssue($issue, $subject, $name, $email){
		$issue = array("do" => "new", "issue" => $issue, "subject" => $subject, "name" => $name, "email" => $email);
		// $context	= stream_context_create(
			// array('http' =>
				      // array(
					      // 'method'	=> 'POST',
					      // 'header'	=> 'Content-type: application/x-www-form-urlencoded',
					      // 'content' => http_build_query(array("issue" => $issue))
				      // )
			// )
		// );
		$url = $this->thrive->composeRequest("support");
		// $ret = filex_get_contents($url, false, $context);
		$ret = curl_get_contents($url, "POST", array("issue" => $issue));
		return $ret;
	}
	
	public function listIssues(){
		$issue = array("do" => "list");
		// $context	= stream_context_create(
			// array('http' =>
				      // array(
					      // 'method'	=> 'POST',
					      // 'header'	=> 'Content-type: application/x-www-form-urlencoded',
					      // 'content' => http_build_query(array("issue" => $issue))
				      // )
			// )
		// );
		$url = $this->thrive->composeRequest("support");
		// $ret = filex_get_contents($url, false, $context);
		$ret = curl_get_contents($url, "POST", array("issue" => $issue));
		return json_decode($ret ,true);
	}
	
	public function issueConversation($issueid){
		$issue = array("do" => "conversation", "id" => $issueid);
		// $context	= stream_context_create(
			// array('http' =>
				      // array(
					      // 'method'	=> 'POST',
					      // 'header'	=> 'Content-type: application/x-www-form-urlencoded',
					      // 'content' => http_build_query(array("issue" => $issue))
				      // )
			// )
		// );
		$url = $this->thrive->composeRequest("support");
		// $ret = filex_get_contents($url, false, $context);
		$ret = curl_get_contents($url, "POST", array("issue" => $issue));
		return json_decode($ret ,true);
	}
	
	public function issueReply($issueid, $reply){
		$issue = array("do" => "reply", "id" => $issueid, "reply" => $reply);
		// $context	= stream_context_create(
			// array('http' =>
				      // array(
					      // 'method'	=> 'POST',
					      // 'header'	=> 'Content-type: application/x-www-form-urlencoded',
					      // 'content' => http_build_query(array("issue" => $issue))
				      // )
			// )
		// );
		$url = $this->thrive->composeRequest("support");
		// $ret = filex_get_contents($url, false, $context);
		$ret = curl_get_contents($url, "POST", array("issue" => $issue));
		return json_decode($ret ,true);
	}
	
	public function get_plugins_keys($url, $key){
		if(!$url || !$key) return array("success" => 0);
		$body = array("action" => "ocl_pk", "key" => $key);
		// $context	= stream_context_create(
			// array('http' =>
				      // array(
					      // 'method'	=> 'POST',
					      // 'header'	=> 'Content-type: application/x-www-form-urlencoded',
					      // 'content' => http_build_query($body)
				      // )
			// )
		// );
		$url = trim($url, "/")."/wp-admin/admin-ajax.php";
		// $ret = filex_get_contents($url, false, $context);
		$ret = curl_get_contents($url, "POST", $body);
		return json_decode($ret ,true);
	}
}
$sites = new SITES;
?>