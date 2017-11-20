<?php
	header("Content-Type:application/json");

	    $status = array(
        200 => '200 OK',
        400 => '400 Bad Request',
        422 => 'Unprocessable Entity',
        500 => '500 Internal Server Error'
        );

	$db = new mysqli('localhost', 'username', 'pswd', 'dbname');

	if($db->connect_errno > 0){
		die('Unable to connect to database [' . $db->connect_error . ']');
	}
	
	// get
	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		// get one by id
		if (isset($_GET["id"])) {
			$id = $_GET["id"];
			$sql = "SELECT * FROM users WHERE deviceId = '$id'";
			$result = $db->query($sql);
			 
			while($row = $result->fetch_assoc()){
				$json[] = $row;
			}
			$data['data'] = $json;
			$result =  mysqli_query($db,$sql);
			$data['total'] = mysqli_num_rows($result);
			echo json_encode($data);
		// get all
		} else {
			$sql = "SELECT * FROM users WHERE visible = true AND updated > DATE_SUB(NOW(),INTERVAL 10 MINUTE)";
			$result = $db->query($sql);
			$total = mysqli_num_rows($result);
			if ($total >= 1) {
				while($row = $result->fetch_assoc()){
					$json[] = $row;
				}
				$data['data'] = $json;
				$result =  mysqli_query($db,$sql);
				$data['total'] = mysqli_num_rows($result);
				echo json_encode($data);
			} else {
				echo "{ }";
			}
		}
	// post
	} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$json = file_get_contents('php://input');
		$arr = json_decode($json, true);

		$id = $arr['deviceid'];
		$nickname = htmlentities($arr['nickname']);
		$loc_lat = $arr['loc_lat'];
		$loc_lon = $arr['loc_lon'];
		$bool = $arr['visibility'];
		$sql = "SELECT deviceId FROM users WHERE deviceId = '".$id."'";
		$result =  mysqli_query($db,$sql);
		$data['total'] = mysqli_num_rows($result);
		
		if ($data['total'] >= 1) {
			$sql = "UPDATE users SET nickname = '$nickname', loc_lat = '$loc_lat' , loc_lon = '$loc_lon', visible = '$bool' WHERE deviceId = '$id'";
			if($db->query($sql)) {
				echo "200 OK";
			} else {
				echo "500 Internal Server Error";
			}
			return;
		} else {
			$sql = "INSERT INTO users (deviceId, nickname, loc_lat, loc_lon, visible) VALUES ('$id', '$nickname', '$loc_lat', '$loc_lon', '$bool')";
			if($db->query($sql)) {
				echo "200 OK";
			} else {
				echo "500 Internal Server Error";
			}
			return;
		}
	// put
	} else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
		echo "400 Bad Request";
	}
	// delete
	else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
		echo "400 Bad Request";
		return;
	} else {
		echo "400 Bad Request";
		return;	
	}
?>
