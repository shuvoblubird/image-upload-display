<?php  
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$serverName 	= ""; 
$username 		= "";   
$password 		= "";  
$databaseName 	= ""; 

try {
  $conn =  new PDO("sqlsrv:server=$serverName;Database=$databaseName", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

$id = 11110;

//image upload
$src = "https://image.shutterstock.com/image-photo/natural-gas-tank-morning-260nw-1667280220.jpg";
$imageData  = file_get_contents($src);


$update_run=$conn->prepare("update pictures set picture=:picture, thumbnail=:thumbnail, tiny=:tiny where id=:id ");
$update_run->bindParam(':picture', $imageData, PDO::PARAM_LOB, 0, PDO::SQLSRV_ENCODING_BINARY);
$update_run->bindParam(':thumbnail', $imageData, PDO::PARAM_LOB, 0, PDO::SQLSRV_ENCODING_BINARY);
$update_run->bindParam(':tiny', $imageData, PDO::PARAM_LOB, 0, PDO::SQLSRV_ENCODING_BINARY);
$update_run->bindParam(':id', $id, PDO::PARAM_INT);

$update_run = $update_run->execute();

if($update_run){
	echo "Successfully updated";
}else{
	print_r($update_run->errorInfo();
}



//Image Preview
$result = $conn->prepare("SELECT * FROM pictures where id=$id");
$result->execute();

$image = $result->fetch();

$image = "data:image/png;base64,".base64_encode($image['picture']);
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
		<img src="<?php echo $image ?>"/>
</body>
</html>

