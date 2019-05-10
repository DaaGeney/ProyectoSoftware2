
<?php 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
//123!\"·QWE
$conn = new mysqli("localhost", "root", "", "web");
if ($conn->connect_error) {
	die("Database connection established Failed..");
} 
$res = array('error' => false);
$action = 'readusers';

if (isset($_GET['action'])) {
	$action = $_GET['action'];
}
//Lee usuarios de la base de datos desde la api
if ($action == 'readusers') {
	$result = $conn->query("SELECT * from usuarios");
	$users = array();
	while ($row = $result->fetch_assoc()){
		array_push($users, $row);
	}
	$res['users'] = $users;
	
}


//Actualiza un usuario en la base de datos
if ($action == 'updateuser') {
	$id = $_GET['id'];
	
	$nombre = $_GET['nombre'];
	$apellido = $_GET['apellido'];
	$clave = $_GET['clave'];
	$result = $conn->query("UPDATE usuarios SET nombre = '$nombre',apellido='$apellido' ,
	 clave = '$clave' where id='$id'");
	if ($result) {
		$res['message'] = "Usuario actualizado correctamente!";
	} else{
		$res['error'] = true;
		$res['message'] = "No se ha podido actualizar el usuario";
	}
}

if ($action == 'createuser') {
	$id =$_GET['id'];
	$nombre = $_GET['nombre'];
	$apellido = $_GET['apellido'];
	$clave = $_GET['clave'];
	$result = $conn->query("INSERT INTO `usuarios` (`id`, `nombre`, `apellido`,`clave`) VALUES ('$id','$nombre', '$apellido','$clave') ");
	if ($result) {
		$res['message'] = "Usuario creado correctamente";
	} else{
		$res['error'] = true;
		$res['message'] = "Error al registrar" ;
	}
}
// Crea un usuario en la base de datos por medio de la api
if ($action == 'createuser') {
	$id =$_GET['id'];
	$nombre = $_GET['nombre'];
	$apellido = $_GET['apellido'];
	$clave = $_GET['clave'];
	$result = $conn->query("INSERT INTO `usuarios` (`id`, `nombre`, `apellido`,`clave`) VALUES ('$id','$nombre', '$apellido','$clave') ");
	if ($result) {
		$res['message'] = "Usuario creado correctamente";
	} else{
		$res['error'] = true;
		$res['message'] = "Error al registrar" ;
	}
}

//Elimina un usuario
if ($action == 'deleteuser') {
	$id =$_GET['id'];

	$result = $conn->query("DELETE  from  `usuarios` where `id` ='$id'  ");
	if ($result) {
		$res[$result];
		$res['message'] = "Usuario eliminado correctamente";
	} else{
		$res['error'] = true;
		$res['message'] = "Error al eliminar" ;
	}
}

//Crea un nuevo favorito
if ($action == 'createfav') {
	$user_id =$_GET['user_id'];
	$symbol = $_GET['symbol'];

	$result = $conn->query("INSERT INTO `favoritos` (`symbol_id`, `user_id`) VALUES ('$symbol','$user_id') ");
	if ($result) {
		$res['message'] = "Favorito agregado";
	} else{
		$res['error'] = true;
		$res['message'] = "Error al registrar" ;
	}
}

//Elimina una divisa favorita
if ($action == 'deletefav') {
	$user_id =$_GET['user_id'];
	$symbol = $_GET['symbol'];

	$result = $conn->query("DELETE from  `favoritos` where `symbol_id` = '$symbol' and `user_id` ='$user_id' ");
	if ($result) {
		$res['message'] = "Favorito eliminado correctamente";
	} else{
		$res['error'] = true;
		$res['message'] = "Error al eliminar" ;
	}
}

if ($action == 'getfav') {
	$user_id =$_GET['user_id'];


	$result = $conn->query("SELECT `favoritos`.`symbol_id`,`roles`.`price`, `roles`.`bid`,`roles`.`ask` from `favoritos`,`roles` where `favoritos`.`symbol_id`=`roles`.`symbol` and `favoritos`.`user_id`='$user_id'");
	$users = array();
	while ($row = $result->fetch_assoc()){
		array_push($users, $row);
	}
	$res['users'] = $users;
}

//Crea una divisa
if ($action == 'createdivisa') {
	$symbol =$_GET['symbol'];
	$price = $_GET['price'];
	$bid = $_GET['bid'];
	$ask = $_GET['ask'];
	$result = $conn->query("DELETE FROM `roles` WHERE `roles`.`symbol` = '$symbol'");
	$result = $conn->query("INSERT INTO `roles` (`symbol`, `price`, `bid`,`ask`) VALUES ('$symbol','$price', '$bid','$ask') ");
	if ($result) {
		$res['message'] = "Divisa creada";
	} else{
		$res['error'] = true;
		$res['message'] = "Error al crear la divisa" ;
	}
}



if ($action == 'deleteuser') {
	$id = $_POST['id'];
	$result = $conn->query("UPDATE `usuarios` SET `estado` = 'inactivo' WHERE `id` = '$id'");	
	if ($result) {
		$res['message'] = "Usuario ha sido eliminado";
	} else{
		$res['error'] = true;
		$res['message'] = "No se ha podido eliminar el usuario";
	}
}

$conn -> close();
header("Content-type: application/json");
echo json_encode($res);
die();
 ?>