<?php
if(!defined("THANKSTHRIVE"))die();
class AC{
	private $key = '';
	private $url = '';
	private $urlv3 = '';
	
	function __construct($url = "", $key = ""){
		$url = trim($url);
		$url = str_replace("/admin/api.php","",$url);
		$this->url = trim($url, "/")."/admin/api.php";
		$this->key = $key;
		$this->urlv3 = str_replace("http:","https:",trim($url, "/")."/api/3/");
	}
	
	function send($parameters){
		if($this->url == "" || $this->key == "") return false;
		$parameters["api_key"] = $this->key;
		// $context	= stream_context_create(
			// array('http' =>
				// array(
					// 'method'	=> 'POST',
					// 'header'	=> 'Content-type: application/x-www-form-urlencoded',
					// 'content' => http_build_query($parameters)
				// )
			// )
		// );
		// return filex_get_contents($this->url, false, $context);
		return curl_get_contents($this->url, "POST", $parameters);
	}
	
	function sendv3($endpoint, $parameters, $method = "POST"){
		$data = json_encode($parameters);
		// $request = curl_init("https://breakthroughemailmarketing.activehosted.com/api/3/connections"); // initiate curl object
		$request = curl_init($this->urlv3.$endpoint); // initiate curl object
		curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
		curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
		curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment if you get no gateway response and are using HTTPS
		curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);
		if(!empty($parameters)){
			curl_setopt($request, CURLOPT_HTTPHEADER, array('Content-type: application/json', 'Content-Length: ' . strlen($data), 'Api-Token: '.$this->key));
			curl_setopt($request, CURLOPT_CUSTOMREQUEST, $method);
			curl_setopt($request, CURLOPT_POSTFIELDS, $data);
		}else{
			curl_setopt($request, CURLOPT_HTTPHEADER, array('Api-Token: '.$this->key));
		}

		$response = (string)curl_exec($request); 
		curl_close($request); // close curl object
		
		$res = json_decode($response, true);

		return $res;
	}
	
	function addCustomField($title, $type = 1){
		$parameters = array(
			'api_action'=> 'list_field_add',
			'api_output'	=> 'json',
			'title'		=> $title,
			'type'		=> $type,
			'req'		=> 0,
			'perstag'	=> '',
			'p[0]'	=> 0,
		);
		// foreach((array)$lists as $list){
			// $parameters["p[".$list["id"]."]"] = $list["id"];
		// }
		$res = json_decode($this->send($parameters), true);
		return $res;
	}
	
	function get_contact($email){
		$parameters = array(
			'api_output'	=> 'json'
		);
		
		if(!filter_var($email, FILTER_VALIDATE_EMAIL) === false){
			$parameters['api_action'] = "contact_view_email";
			$parameters['email'] = $email;
		}elseif(is_numeric($email)){
			$parameters['api_action'] = "contact_view";
			$parameters['id'] = $email;
		}else return false;
		
		return $this->send($parameters);
	}
	
	function update_contact($args, $email){
		$parameters = array_merge(array(
			'api_action' => 'contact_sync',
			'api_output' => 'json',
			'email'		 => $email
		), $args);		
		
		if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
			return false;
		}
		
		return $this->send($parameters);
	}
	
	function edit_contact($args, $id){
		$parameters = array_merge(array(
			'api_action' => 'contact_edit',
			'api_output' => 'json',
			'id'		 => $id
		), $args);		
		
		if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
			return false;
		}
		
		return $this->send($parameters);
	}
	
	function add_note($args, $id){
		$parameters = array_merge(array(
			'api_action' => 'contact_note_add',
			'api_output' => 'json',
			'id'		 => $id
		), $args);
		
		return $this->send($parameters);
	}
	
	function getLists(){
		$parameters = array(
			'api_action' => 'list_list',
			'api_output' => 'json',
			'ids'		 => "all",
			'full'		 => "1",
		);
		
		return $this->send($parameters);
	}
	
	function get_custom_fields($list_id = "all"){
		if($list_id != ""){
			$parameters = array(
				'api_action' => 'list_field_view',
				'api_output' => 'json',
				'ids'		 => $list_id
			);
			
			return $this->send($parameters);
		}
    }
	
	function update_pass($email, $user_pass, $fid){
		$parameters = array(
			'api_action'	=> 'contact_sync',
			'api_output'	=> 'json',
			'email' 		=> $email,
			'field['.$fid.',0]'	=> $user_pass,
		);
		return $this->send($parameters);
	}
	
	function createConnection(){
		$conn = array(
			"connection" => array(
				"service" 	=> "ThriveCart",
				"externalid"=> "ThriveCart-".STOREID,
				"name"		=> STOREID." (ThriveCart)",
				"logoUrl"	=> "http://example.com/i/foo.png",
				"linkUrl"	=> "http://thrivecartdeepdata.com"
			)
		);
		$res = $this->sendv3("connections", $conn, "POST");
		return $res;
	}
	
	function getConnection($id){
		if($id)return false;
		$res = $this->sendv3("connections/$id", array(), "GET");
		return $res;
	}
	
	function findConnection($externalid){
		if(!$externalid)return false;
		$res = $this->sendv3("connections/?filter[externalid]=$externalid", array(), "GET");
		return $res;
	}
	
	function updateConnection($id, $params){
		if($id)return false;
		$data = array(
			"service" 		=> "",
			"externalid" 	=> "",
			"name" 			=> "",
			"logoUrl" 		=> "",
			"linkUrl" 		=> "",
			"status" 		=> "",
			"syncStatus" 	=> "",
		);
		$conn = array_intersect_key($params,$data);
		$res = $this->sendv3("connections/$id", $conn, "PUT");
		return $res;
	}
	
	function deleteConnection($id){
		if($id)return false;
		$res = $this->sendv3("connections/$id", array(), "DELETE");
		return $res;
	}
	
	/** 
	* @param (string)(externalid) Order ID from external service 'ThriveCart'.
	* @param (string)(email) Customer email
	* @param (array)(orders) An array of the purchased product with the following fields:
	*			(string)(externalid) The id of the product in the external service 'ThriveCart'.
	*			(string)(required)(name) The name of the product
	*			(string)(required)(price) The price of the product
	*			(string)(required)(quantity) The quantity of the product
	*			(string)(category) The category of the product
	* @param (int32)(totalPrice) The total price of the order including tax and shipping charges.
	* @param (int32)(currency) The currency of the order (3-digit ISO code, e.g., 'USD').
	* @param (int32)(customerid) The id of the customer associated with this order.
	* @param (int32)(source) The order source code (0 - sync, 1 - realtime webhook).
	* @param (date)(orderDate) he date the order was placed.
	* @param (string)(orderUrl) The URL for the order in the external service.
	* @param (string)(shippingMethod) The shipping method of the order.
	*/
	function createOrder($externalid, $email, $orders, $totalPrice, $currency, $customerid, $source = 1, $orderDate="", $orderUrl="", $shippingMethod=""){
		$connection = $this->findConnection("ThriveCart-".STOREID);
		$connectionid = false;
		foreach($connection["connections"] as $conn){
			if($conn["externalid"] == "ThriveCart-".STOREID){
				$connectionid = $conn["id"];
				break;
			}
		}
		if($connectionid === false){
			$connection = $this->createConnection();
			$connectionid = $connection["connection"]["externalid"];
		}
		$customer = $this->createCustomer($connectionid, $customerid, $email);
		if(!isset($customer["ecomCustomer"]["id"])){
			$customer = $this->findCustomer($connectionid, $email);
			$customerid = $customer["id"];
		}else{
			$customerid = $customer["ecomCustomer"]["id"];
		}
		$ecomOrder = array(
			"ecomOrder" => array(
				"externalid" => $externalid,
				"source" => $source,
				"email" => $email,
				"orderNumber" => $externalid,
				"orderProducts" => $orders,
				"orderUrl" => $orderUrl,
				"orderDate" => $orderDate,
				"shippingMethod" => $shippingMethod,
				"totalPrice" => $totalPrice,
				"currency" => $currency,
				"connectionid" => $connectionid,
				"customerid" => $customerid
			)
		);
		// print_r($ecomOrder);
		// echo json_encode($ecomOrder);
		$res = $this->sendv3("ecomOrders", $ecomOrder, "POST");
		// var_dump($res);
		$res["connection"] = $connection;
		$res["params"] = $ecomOrder;
		return $res;
	}
	
	function createCustomer($connectionid, $externalid, $email){
		if(!$connectionid || !$externalid || !$email) return false;
		$ecomCustomer = array(
			"ecomCustomer" => array(
				"connectionid" => $connectionid,
				"externalid" => $externalid,
				"email" => $email
			)
		);
		$res = $this->sendv3("ecomCustomers", $ecomCustomer, "POST");
		return($res);
	}
	
	function findCustomer($connectionid, $email){
		$customers = $this->sendv3("ecomCustomers?filters[connectionid]=".$connectionid."&filters[email]=".$email, array(), "GET");
		foreach($customers["ecomCustomers"] as $customer){
			if($customer["connection"] == $connectionid && $customer["email"] == $email){
				return $customer;
				break;
			}
		}
		return false;
	}
	
	function refund($externalid, $email, $orders){
		$connection = $this->findConnection("ThriveCart-".STOREID);
		$connectionid = false;
		foreach($connection["connections"] as $conn){
			if($conn["externalid"] == "ThriveCart-".STOREID){
				$connectionid = $conn["id"];
				break;
			}
		}
		if($connectionid === false){
			$connection = $this->createConnection();
			$connectionid = $connection["connection"]["externalid"];
		}
		
		$order = $this->findOrder($connectionid, $externalid, $email);
		if($order){
			$ecomOrder = array(
				"ecomOrder" => array(
					"orderProducts" => $orders
				)
			);
			
			$orders = $this->sendv3("ecomOrders/".$order["id"], $ecomOrder, "PUT");
			print_r($orders);
		}
	}
	
	function findOrder($connectionid, $externalid, $email){
		$orders = $this->sendv3("ecomOrders?filters[connectionid]=".$connectionid."&filters[externalid]=".$externalid."&filters[email]=".$email, array(), "GET");
		foreach($orders["ecomOrders"] as $order){
			if($order["connectionid"] == $connectionid && $order["externalid"] == $externalid && $order["email"] == $email){
				return $order;
				break;
			}
		}
		return false;
	}
}
?>