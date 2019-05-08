
<?php 

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

$conn = new mysqli("localhost", "root", "", "web");
if ($conn->connect_error) {
	die("Database connection established Failed..");
} 
$res = array('error' => false);
$action = 'readusers';
$action = 'readroles';
if (isset($_GET['action'])) {
	$action = $_GET['action'];
}
if($action=='search'){
	$id = $_GET['id'];
$result = $conn->query("SELECT * FROM `usuarios` where `id`='$id' and `estado`='activo'");
	$users = array();
	while ($row = $result->fetch_assoc()){
		array_push($users, $row);
	}
	$res['users'] = $users;
}
if ($action == 'readusers') {
	$result = $conn->query("SELECT * from usuario");
	$users = array();
	while ($row = $result->fetch_assoc()){
		array_push($users, $row);
	}
	$res['users'] = $users;
	
}
if ($action == 'readroles') {
	$result = $conn->query("SELECT * FROM `roles`");
	$roles = array();
	while ($row = $result->fetch_assoc()){
		array_push($roles, $row);
	}
	$res['roles'] = $roles;
	
}
if ($action == 'createuser') {
	$id = $_POST['id'];
	$nombre = $_POST['nombre'];
	$apellido = $_POST['apellido'];
	$clave = $_POST['clave'];
	
	$result = $conn->query("INSERT INTO `usuario` (`id`, `nombre`, `apellido`,`clave`) VALUES (	'$id','$nombre', '$apellido','$clave') ");
	if ($result) {
		$res['message'] = "Usuario creado correctamente";
	} else{
		$res['error'] = true;
		$res['message'] = "No se ha podido crear el usuario";
	}
}
if ($action == 'createrol') {
	$id = $_POST['id'];
	$nombre = $_POST['nombre'];
	$descripcion = $_POST['descripcion'];
	$result = $conn->query("INSERT INTO `roles` (`id`, `nombre`, `descripcion`) VALUES ('$id', '$nombre', '$descripcion')");
	if ($result) {
		$res['message'] = "Rol creado correctamente";
	} else{
		$res['error'] = true;
		$res['message'] = "No se ha podido crear el rol";
	}
}
if ($action == 'updateuser') {
	$id = $_POST['id'];
	$nombre = $_POST['nombre'];
	$apellido = $_POST['apellido'];
	$correo = $_POST['correo'];
	$clave = $_POST['clave'];
	$credencial = $_POST['credencial'];
	$idlista = $_POST['idlista'];
	$idprograma = $_POST['idprograma'];
	$result = $conn->query("UPDATE usuarios SET nombre = '$nombre',apellido='$apellido' ,
	correo='$correo', credencial = '$credencial',
	idlista = (select id from lista where nombre = '$idlista'), idprograma= (select id from programa where nombre =  '$idprograma') ,
	 estado = 'activo'
	  WHERE id = '$id'");
	if ($result) {
		$res['message'] = "Usuario actualizado correctamente!";
	} else{
		$res['error'] = true;
		$res['message'] = "No se ha podido actualizar el usuario";
	}
}
if ($action == 'updaterol') {
	$id = $_POST['id'];
	$nombre = $_POST['nombre'];
	$descripcion = $_POST['descripcion'];
	$result = $conn->query("UPDATE `roles` SET `nombre` = '$nombre',`descripcion`='$descripcion' WHERE `id` = '$id'");
	if ($result) {
		$res['message'] = "Rol actualizado correctamente!";
	} else{
		$res['error'] = true;
		$res['message'] = "No se ha podido actualizar el rol";
	}
}
if ($action == 'deleteuser') {
	$id = $_POST['id'];
	$result = $conn->query("UPDATE `usuarios` SET `estado` = 'inactivo' WHERE `id` = '$id'");	
	if ($result) {
		$res['message'] = "Usuario ha pasado a inactivo";
	} else{
		$res['error'] = true;
		$res['message'] = "No se ha podido cambiar el estado del usuario";
	}
}
if ($action == 'deleterol') {
	$id = $_POST['id'];
	$result = $conn->query("DELETE FROM `roles` WHERE `id` = '$id'");	
	if ($result) {
		$res['message'] = "Rol eliminado!";
	} else{
		$res['error'] = true;
		$res['message'] = "No se ha podido eliminar el Rol";
	}
}
$conn -> close();
header("Content-type: application/json");
echo json_encode($res);
die();
 ?>