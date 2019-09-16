<?php

function get_type_label($id, $i, $purchase_map, $custom_labels){
	$type = get_type($id, $i, $purchase_map);
	$customLabel = get_custom_type_labels($custom_labels);
	$label = $customLabel[$type];
	if(!$label || $label == "") {
		$defaults = json_decode(CUSTOM_TYPE_LABELS, true);
		if(isset($defaults[$type]))
			$label = $defaults[$type];
		else $label = $type;
	}
	return $label;
}

function get_custom_type_labels($custom_labels = array()){
	$defaults = json_decode(CUSTOM_TYPE_LABELS, true);
	$type_labels = array_merge($defaults, $custom_labels);
	return $type_labels;
}
function get_type($id, $i, $purchase_map){
	$pm = @$purchase_map[$i];
	if($pm == "product-".$id) return "Main Product";
	elseif($pm == "bump-".$id) return "Bump";
	elseif($pm == "upsell-".$id) return "Upsell";
	elseif($pm == "downsell-".$id) return "Downsell";
	else{
		foreach($purchase_map as $pm){
			if($pm == "product-".$id) return "Main Product";
			elseif($pm == "bump-".$id) return "Bump";
			elseif($pm == "upsell-".$id) return "Upsell";
			elseif($pm == "downsell-".$id) return "Downsell";
		}
	}
}

function get_user_hash($email, $key){
	return md5(base64_encode(substr($email,0,1).trim($email).substr($email,0,2)).$key);
}

function get_product_ids($order){
	$product_ids = array();
	foreach($order as $product){
		array_push($product_ids, (isset($product["reference"])?$product["reference"]:$product["id"]) );
	}
	return $product_ids;
}
function in_array_r($needle, $array, $foundall = false){
	if($foundall === true && is_array($needle)){
		$diff = array_diff($needle, $array);
		if(empty($diff) || count($diff) ==0 )return true;
		else return false;
	}else{
		if(is_array($needle)){
			foreach($needle as $n){
				if(in_array($n, $array)) return true;
			}
		}else{
			return in_array($needle, $array);
		}
	}
}
?>