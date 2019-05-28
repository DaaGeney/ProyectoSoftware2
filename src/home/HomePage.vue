<template>
  <div style="width:auto">
    
    <h1 style="display:inline">Bienvenido {{account.user.firstName}}!</h1>
    <p style="display:inline; float:right">
      <router-link to="/login">Cerrar sesion</router-link>
    </p>

    <div id="root">

		<div class="">
			<div class="crud_header">
				<h1 class="left">Segundo Sprint</h1>

				<div class="fix"></div>
			</div>
			<div>
				<input type="text" v-model="id">
				<button @click="getOneUser()">Busqueda</button>
				<button @click="getAllU()">Traer todo</button>
			</div>
			<div>
				<input type="text" v-model="facultad">
				<button @click="getFacultad();getProgramasFacultad()">Filtro facultad</button>
			</div>
			<div>
				<input type="text" v-model="programa">
				<button @click="getPrograma()">Filtro Programa</button>
			</div>

			<h1 class="left">OCMS Gestion de usuarios y roles</h1>
			<button class="right addnew" @click="showingModal = true;">Agregar usuario</button>
			<button class="right addnew" @click="showingRolModal = true;">Agregar rol</button>
			<button class="right addnew" @click="showingProgramaModal = true;">Agregar Programa</button>
			<div class="fix"></div>
		</div>
		<hr>

		<!--Notificaciones lista de programas--------------------------------------------------------------->
		<p class="errorMessage" v-if="errorProgramaMessage">{{errorProgramaMessage}}</p>
		<p class="successMessage" v-if="successProgramaMessage">{{successProgramaMessage}}</p>
		<!--Notificaciones lista de roles--------------------------------------------------------------->
		<p class="errorMessage" v-if="errorRolMessage">{{errorRolMessage}}</p>
		<p class="successMessage" v-if="successRolMessage">{{successRolMessage}}</p>
		<!--Notificaciones lista de usuarios--------------------------------------------------------------->
		<p class="errorMessage" v-if="errorUserMessage">{{errorUserMessage}}</p>
		<p class="successMessage" v-if="successUserMessage">{{successUserMessage}}</p>

		<div class="fix"></div>
		<!--Agregar un nuevo usuario-------------------------------------------------------------------------------->
		<div class="modal col-md-6" id="addmodal" v-if="showingModal">
			<div class="modalheading">
				<p class="left">Agregar nuevo usuario</p>
				<p class="right close" @click="showingModal = false;">x</p>
				<div class="fix"></div>
			</div>
			<div class="modalbody">
				<div class="modalcontent">
					<table class="form">
						<tr>
							<th>Código</th>
							<th>:</th>
							<td><input type="number" placeholder="id" v-model="newUser.id"></td>
						</tr>
						<tr>
							<th>Nombre</th>
							<th>:</th>
							<td><input type="text" placeholder="Nombre" v-model="newUser.nombre"></td>
						</tr>

						<tr>
							<th>Apellido</th>
							<th>:</th>
							<td><input type="text" placeholder="apellido" v-model="newUser.apellido"></td>
						</tr>
						<tr>
							<th>Correo</th>
							<th>:</th>
							<td><input type="email" placeholder="correo" v-model="newUser.correo"></td>
						</tr>
						<tr>
							<th>Clave</th>
							<th>:</th>
							<td><input type="text" placeholder="clave" v-model="newUser.clave"></td>
						</tr>
						<tr>
							<th>Credencial</th>
							<th>:</th>
							<td><input type="text" placeholder="credencial" v-model="newUser.credencial"></td>
						</tr>
						<tr>
							<th>Lista</th>
							<th>:</th>
							<td><input type="text" placeholder="lista" v-model="newUser.idlista"></td>
						</tr>
						<tr>
							<th>Programa</th>
							<th>:</th>
							<td><input type="text" placeholder="programa" v-model="newUser.idprograma"></td>
						</tr>
						<tr>
							<th>Rol</th>
							<th>:</th>
							<td><input type="text" placeholder="rol" v-model="newUser.idrol"></td>
						</tr>
					</table>
					<div class="margin"></div>
					<button class="center" @click="showingModal = false; saveUser();">ACEPTAR</button>
					<div class="margin"></div>
				</div>
			</div>
		</div>
		<!--Agregar un nuevo rol------------------------------------------------------------------------------>
		<div class="modal col-md-6" id="addrolmodal" v-if="showingRolModal">
			<div class="modalheading">
				<p class="left">Agregar nuevo rol</p>
				<p class="right close" @click="showingRolModal = false;">x</p>
				<div class="fix"></div>
			</div>
			<div class="modalbody">
				<div class="modalcontent">
					<table class="form">
						<tr>
							<th>Código</th>
							<th>:</th>
							<td><input type="number" placeholder="id" v-model="newRol.id"></td>
						</tr>

						<tr>
							<th>Nombre</th>
							<th>:</th>
							<td><input type="text" placeholder="Nombre" v-model="newRol.nombre"></td>
						</tr>

						<tr>
							<th>Descripción</th>
							<th>:</th>
							<td><input type="text" placeholder="descripcion" v-model="newRol.descripcion"></td>
						</tr>
					</table>
					<div class="margin"></div>
					<button class="center" @click="showingRolModal = false; saveRol();">ACEPTAR</button>
					<div class="margin"></div>
				</div>
			</div>
		</div>
		<div class="modal col-md-6" id="addprogramamodal" v-if="showingProgramaModal">
			<div class="modalheading">
				<p class="left">Agregar nuevo programa</p>
				<p class="right close" @click="showingProgramaModal = false;">x</p>
				<div class="fix"></div>
			</div>
			<div class="modalbody">
				<div class="modalcontent">
					<table class="form">
						<tr>
									<th>ID</th>
									<th>:</th>
									<td><input type="number" placeholder="id" v-model="newPrograma.id"></td>
								</tr>
							<tr>
									<th>Nombre</th>
									<th>:</th>
									<td><input type="text" placeholder="nombre" v-model="newPrograma.nombre"></td>
								</tr>
								<tr>
									<th>facultad</th>
									<th>:</th>
									<td><input type="text" placeholder="facultad" v-model="newPrograma.facultad"></td>
								</tr>
								<tr>
									<th>Snies</th>
									<th>:</th>
									<td><input type="text" placeholder="snies" v-model="newPrograma.snies"></td>
								</tr>
								<tr>
									<th>Duracion</th>
									<th>:</th>
									<td><input type="number" placeholder="duracion" v-model="newPrograma.duracion"></td>
								</tr>
								<tr>
									<th>Modalidad</th>
									<th>:</th>
									<td><input type="text" placeholder="modalidad" v-model="newPrograma.modalidad"></td>
								</tr>
								<tr>
									<th>Reacreditacion</th>
									<th>:</th>
									<td><input type="text" placeholder="reacreditacion"
											v-model="newPrograma.reacreditacion"></td>
								</tr>
								<tr>
									<th>Icfes</th>
									<th>:</th>
									<td><input type="text" placeholder="Icfes" v-model="newPrograma.icfes"></td>
								</tr>
								<tr>
									<th>Renovacion</th>
									<th>:</th>
									<td><input type="text" placeholder="Renovacion" v-model="newPrograma.renovacion"></td>
								</tr>
								<tr>
									<th>Tipo</th>
									<th>:</th>
									<td><input type="text" placeholder="Tipo" v-model="newPrograma.tipo"></td>
								</tr>
					</table>
					<div class="margin"></div>
					<button class="center" @click="showingProgramaModal = false; savePrograma();">ACEPTAR</button>
					<div class="margin"></div>
				</div>
			</div>
		</div>
		<!--Editar un usuario------------------------------------------------------------------------------>
		<div class="modal col-md-6" id="editmodal" v-if="showingeditModal">
			<div class="modalheading">
				<p class="left">Editar usuario</p>
				<p class="right close" @click="showingeditModal = false;">x</p>
				<div class="fix"></div>
			</div>
			<div class="modalbody">
				<div class="modalcontent">
					<table class="form">
						<tr>
							<th>Nombre</th>
							<th>:</th>
							<td><input type="text" placeholder="nombre" v-model="clickedUser.nombre"></td>
						</tr>
						<tr>
							<th>Apellido</th>
							<th>:</th>
							<td><input type="text" placeholder="apellido" v-model="clickedUser.apellido"></td>
						</tr>
						<tr>
							<th>Correo</th>
							<th>:</th>
							<td><input type="email" placeholder="correo" v-model="clickedUser.correo"></td>
						</tr>
						<tr>
							<th>Clave</th>
							<th>:</th>
							<td><input type="text" placeholder="clave" v-model="clickedUser.clave"></td>
						</tr>
						<tr>
							<th>Credencial</th>
							<th>:</th>
							<td><input type="text" placeholder="credencial" v-model="clickedUser.credencial"></td>
						</tr>
						<tr>
							<th>Lista</th>
							<th>:</th>
							<td><input type="text" placeholder="lista" v-model="clickedUser.idlista"></td>
						</tr>
						<tr>
							<th>Programa</th>
							<th>:</th>
							<td><input type="text" placeholder="programa" v-model="clickedUser.idprograma"></td>
						</tr>
						<tr>
							<th>Rol</th>
							<th>:</th>
							<td><input type="text" placeholder="rol" v-model="clickedUser.idrol"></td>
						</tr>
					</table>
					<div class="margin"></div>
					<button class="center" @click="showingeditModal = false; updateUser()">ACEPTAR</button>
					<div class="margin"></div>
				</div>
			</div>
		</div>
		<!--Editar un Rol------------------------------------------------------------------------------>
		<div class="modal col-md-6" id="editRolmodal" v-if="showingRoleditModal">
			<div class="modalheading">
				<p class="left">Editar rol</p>
				<p class="right close" @click="showingRoleditModal = false;">x</p>
				<div class="fix"></div>
			</div>
			<div class="modalbody">
				<div class="modalcontent">
					<table class="form">
						<tr>
							<th>Nombre</th>
							<th>:</th>
							<td><input type="text" placeholder="nombre" v-model="clickedRol.nombre"></td>
						</tr>
						<tr>
							<th>Descripción</th>
							<th>:</th>
							<td><input type="text" placeholder="descripcion" v-model="clickedRol.descripcion"></td>
						</tr>
					</table>
					<div class="margin"></div>
					<button class="center" @click="showingRoleditModal = false; updateRol()">ACEPTAR</button>
					<div class="margin"></div>
				</div>
			</div>
		</div>
		<!--Editar un programa------------------------------------------------------------------------------>
		<div class="modal col-md-6" id="editProgramamodal" v-if="showingProgramaeditModal">
			<div class="modalheading">
				<p class="left">Editar </p>
				<p class="right close" @click="showingProgramaeditModal = false;">x</p>
				<div class="fix"></div>
			</div>
			<div class="modalbody">
				<div class="modalcontent">
					<table class="form">
						<tr>
							<th>Nombre</th>
							<th>:</th>
							<td><input type="text" placeholder="nombre" v-model="clickedPrograma.nombre"></td>
						</tr>
						<tr>
							<th>facultad</th>
							<th>:</th>
							<td><input type="text" placeholder="facultad" v-model="clickedPrograma.facultad"></td>
						</tr>
						<tr>
							<th>Snies</th>
							<th>:</th>
							<td><input type="text" placeholder="snies" v-model="clickedPrograma.snies"></td>
						</tr>
						<tr>
							<th>Duracion</th>
							<th>:</th>
							<td><input type="number" placeholder="duracion" v-model="clickedPrograma.duracion"></td>
						</tr>
						<tr>
							<th>Modalidad</th>
							<th>:</th>
							<td><input type="text" placeholder="modalidad" v-model="clickedPrograma.modalidad"></td>
						</tr>
						<tr>
							<th>Reacreditacion</th>
							<th>:</th>
							<td><input type="text" placeholder="reacreditacion"
									v-model="clickedPrograma.reacreditacion"></td>
						</tr>
						<tr>
							<th>Icfes</th>
							<th>:</th>
							<td><input type="text" placeholder="Icfes" v-model="clickedPrograma.icfes"></td>
						</tr>
						<tr>
							<th>Renovacion</th>
							<th>:</th>
							<td><input type="text" placeholder="Renovacion" v-model="clickedPrograma.renovacion"></td>
						</tr>
						<tr>
							<th>Tipo</th>
							<th>:</th>
							<td><input type="text" placeholder="Tipo" v-model="clickedPrograma.tipo"></td>
						</tr>
					</table>
					<div class="margin"></div>
					<button class="center" @click="showingProgramaeditModal = false;updatePrograma() ">ACEPTAR</button>
					<div class="margin"></div>
				</div>
			</div>
		</div>
		<!--Eliminar un usuario------------------------------------------------------------------------------>
		<div class="modal col-md-6" id="deletemodal" v-if="showingdeleteModal">
			<div class="modalheading">
				<p class="left">Eliminar usuario</p>
				<p class="right close" @click="showingdeleteModal = false;">x</p>
				<div class="fix"></div>
			</div>
			<div class="modalbody">
				<div class="modalcontent">

					<div class="margin"></div>
					<h3 class="center">¿Desea cambiar a inactivo?</h3>
					<div class="margin"></div>
					<h4 class="center">{{clickedUser.username}}</h4>
					<div class="margin"></div>
					<div class="col-md-6 center">
						<button class="left" @click="showingdeleteModal = false; deleteUser()">SI</button>
						<button class="right" @click="showingdeleteModal = false;">NO</button>
					</div>
					<br><br>
					<div class="margin"></div>
				</div>
			</div>
		</div>
		<!--Eliminar un Rol------------------------------------------------------------------------------>
		<div class="modal col-md-6" id="deleteRolmodal" v-if="showingRoldeleteModal">
			<div class="modalheading">
				<p class="left">Eliminar Rol</p>
				<p class="right close" @click="showingRoldeleteModal = false;">x</p>
				<div class="fix"></div>
			</div>
			<div class="modalbody">
				<div class="modalcontent">

					<div class="margin"></div>
					<h3 class="center">¿Está seguro que desea eliminar este rol?,
						Este rol se eliminará de todos los usuarios que lo contengan</h3>
					<div class="margin"></div>
					<h4 class="center">{{clickedRol.nombre}}</h4>
					<div class="margin"></div>
					<div class="col-md-6 center">
						<button class="left" @click="showingRoldeleteModal = false; deleteRol()">SI</button>
						<button class="right" @click="showingRoldeleteModal = false;">NO</button>
					</div>
					<br><br>
					<div class="margin"></div>
				</div>
			</div>
		</div>
		<!--Eliminar un programa------------------------------------------------------------------------------>
		<div class="modal col-md-6" id="deleteProgramamodal" v-if="showingProgramadeleteModal">
			<div class="modalheading">
				<p class="left">Eliminar Rol</p>
				<p class="right close" @click="showingProgramadeleteModal = false;">x</p>
				<div class="fix"></div>
			</div>
			<div class="modalbody">
				<div class="modalcontent">

					<div class="margin"></div>
					<h3 class="center">¿Está seguro que desea eliminar este programa?,
						Este programa se eliminará con todos los usuarios que lo contengan</h3>
					<div class="margin"></div>
					<h4 class="center">{{clickedPrograma.nombre}}</h4>
					<div class="margin"></div>
					<div class="col-md-6 center">
						<button class="left" @click="showingProgramadeleteModal = false; deletePrograma()">SI</button>
						<button class="right" @click="showingProgramadeleteModal = false;">NO</button>
					</div>
					<br><br>
					<div class="margin"></div>
				</div>
			</div>
		</div>
		<div id="accordion">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h5 class="mb-0">
        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Programas
        </button>
      </h5>
    </div>

    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
      <div class="card-body">
				<table class="list">
			<tr>
				<th>ID</th>
				<th>Nombre</th>
				<th>Facultad</th>
				<th>Snies</th>
				<th>Duracion</th>
				<th>Modalidad</th>
				<th>Reacreditacion</th>
				<th>Icfes</th>
				<th>Renovacion</th>
				<th>Tipo</th>
				<th>Editar</th>
				<th>Eliminar</th>
				<th>UOCs</th>
			</tr>
			<tr v-for="programa in programas">
				<td>{{programa.id}}</td>
				<td>{{programa.nombre}}</td>
				<td>{{programa.facultad}}</td>
				<td>{{programa.snies}}</td>
				<td>{{programa.duracion}}</td>
				<td>{{programa.modalidad}}</td>
				<td>{{programa.reacreditacion}}</td>
				<td>{{programa.icfes}}</td>
				<td>{{programa.renovacion}}</td>
				<td>{{programa.tipo}}</td>

				<td><button @click="showingProgramaeditModal = true; selectPrograma(programa)">Editar</button></td>
				<td><button @click="showingProgramadeleteModal = true; selectPrograma(programa)">Eliminar</button></td>
				<td><button @click="location='../privilegios.html'">Definir UOCs</button></td>

			</tr>
		</table>

      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingTwo">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          Roles
        </button>
      </h5>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
      <div class="card-body">
				
		<!--Lista de roles------------------------------------------------------------------------------>
		<table class="list">
			<tr>
				<th>ID</th>
				<th>Nombre</th>
				<th>Descripcion</th>
				<th>Editar</th>
				<th>Eliminar</th>
				<th>Privilegios</th>
			</tr>
			<tr v-for="rol in roles">
				<td>{{rol.id}}</td>
				<td>{{rol.nombre}}</td>
				<td>{{rol.descripcion}}</td>

				<td><button @click="showingRoleditModal = true; selectRol(rol)">Editar</button></td>
				<td><button @click="showingRoldeleteModal = true; selectRol(rol)">Eliminar</button></td>
				<td><button @click="location='../privilegios.html'">Privilegios</button></td>
			</tr>
		</table>

      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingThree">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          Usuarios
        </button>
      </h5>
    </div>
    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
      <div class="card-body">
					<!--Lista de usuarios------------------------------------------------------------------------------>
		<table class="list" id="simple">
			<tr>
				<th>ID</th>
				<th>Nombre</th>
				<th>Apellido</th>
				<th>E-mail</th>
				<th>Clave</th>
				<th>Credencial</th>
				<th>Lista</th>
				<th>Programa</th>
				<th>Rol</th>
				<th>Editar</th>
				<th>Eliminar</th>
			</tr>
			<tr v-for="user in usuarios">
				<td>{{user.id}}</td>
				<td>{{user.nombre}}</td>
				<td>{{user.apellido}}</td>
				<td>{{user.correo}}</td>
				<td>{{user.clave}}</td>
				<td>{{user.credencial}}</td>
				<td>{{user.idlista}}</td>
				<td>{{user.idprograma}}</td>
				<td>{{user.idrol}}</td>

				<td><button @click="showingeditModal = true; selectUser(user)">Editar</button></td>
				<td><button @click="showingdeleteModal = true; selectUser(user)">Eliminar</button></td>
			</tr>
		</table>
      </div>
    </div>
  </div>
</div>
	</div>
	</div>
  

</template>

<script>
import { mapState, mapActions } from "vuex";
//import func from '../../vue-temp/vue-editor-bridge';

export default {
  data() {
    return {
      //Declaramos todas las variables que se van a utilizar
		showingModal: false,
		showingRolModal: false,
		showingProgramaModal: false,

		showingeditModal: false,
		showingRoleditModal: false,
		showingProgramaeditModal: false,

		showingdeleteModal: false,
		showingRoldeleteModal: false,
		showingProgramadeleteModal: false,

		errorUserMessage: "",
		successUserMessage: "",
		errorRolMessage: "",
		successRolMessage: "",
		errorProgramaMessage: "",
		successProgramaMessage: "",

		usuarios: [],
		newUser: { id: "", nombre: "", apellido: "", correo: "", clave: "", credencial: "", idlista: "", idprograma: "", idrol: "" },
		clickedUser: {},
		id: "",
		facultad: "",
		programa: "",

		roles: [],
		newRol: { id: "", nombre: "", descripcion: "" },
		clickedRol: {},

		programas: [],
		newPrograma: { id: "", nombre: "", facultad: "",snies:"",duracion:"",modalidad:"",reacreditacion:"",icfes:"",renovacion:"",tipo:"" },
		clickedPrograma: {},
    };
  },
  mounted() {
  this.getAllU();
	this.getAllRoles();    //this.cargarFavorito()
  },
  computed: {
    ...mapState({
      account: state => state.account,
      users: state => state.users.all
    })
  },
  created() {
    this.getAllUsers();
  },
  methods: {
    ...mapActions("users", {
      getAllUsers: "getAll",
      deleteUser: "delete"
    }),
   //Trae todos los usuarios de la base de datos
		getAllU: function () {
      var that = this;
			axios.get("http://localhost/VersionFinalProyectoAula/src/back/api.php?action=readusers")
				.then(function (response) {
					console.log(response.data)
					if (response.data.error) {
						that.errorUserMessage = response.data.message;
						console.log(that.users)
					} else {
						that.usuarios = response.data.users;
						console.log(that.usuarios)
					}
					//


				});
		},
		getOneUser: function () {
			      var that = this;

			var formData = that.toFormData(that.clickedUser);
			//console.log("Al menos entra +" + formData)
			axios.get("http://localhost/VersionFinalProyectoAula/src/back/api.php?action=search", {
				params: {
					id: that.id
				}
			})
				.then(function (response) {
					console.log(response.data)
					that.id = ""
					if (response.data.error) {
						that.errorMessage = response.data.message;
						//console.log("error")
					} else {
						that.usuarios = response.data.users;
						//console.log("Entro")
					}
				});


		},
		getProgramasFacultad: function () {
			      var that = this;

			//var formData = app.toFormData(app.clickedUser);
			//console.log("Al menos entra +" + formData)
			axios.get("http://localhost/VersionFinalProyectoAula/src/back/api.php?action=getProgramasFacultad", {
				params: {
					facultad: that.facultad
				}
			})
				.then(function (response) {
					//console.log(response.data)
					that.facultad = ""
					if (response.data.error) {
						that.errorMessage = response.data.message;
						//console.log("error")
					} else {
						that.programas = response.data.users;
						console.log("getProgramasFacultad: ")
						console.log( response.data.users)
					}
				});


		},
		getFacultad: function () {
			      var that = this;

			var formData = that.toFormData(that.clickedUser);
			//console.log("Al menos entra +" + formData)
			axios.get("http://localhost/VersionFinalProyectoAula/src/back/api.php?action=getFacultad", {
				params: {
					facultad: that.facultad
				}
			})
				.then(function (response) {
					console.log(response.data)
					that.facultad = ""
					if (response.data.error) {
						that.errorMessage = response.data.message;
						//console.log("error")
					} else {
						that.usuarios = response.data.users;
						//console.log("Entro")
					}
				});


		},
		getPrograma: function () {
			      var that = this;

//console.log("Al menos entra +" + formData)
			axios.get("http://localhost/VersionFinalProyectoAula/src/back/api.php?action=getPrograma", {
				params: {
					programa: that.programa
				}
			})
				.then(function (response) {
					console.log(response.data)
					that.programa = ""
					if (response.data.error) {
						that.errorMessage = response.data.message;
						//console.log("error")
					} else {
						that.usuarios = response.data.users;
						//console.log("Entro")
					}
				});


		},
		//Trae todos los roles de la base de datos
		getAllRoles: function () {
            var that = this;

			axios.get("http://localhost/VersionFinalProyectoAula/src/back/api.php?action=readrol")
				.then(function (response) {
					console.log(response.data)
					if (response.data.error) {
						that.errorRolMessage = response.data.message;
						console.log(that.roles)
					} else {
						that.roles = response.data.rol;
						console.log(response.data.rol)
					}
					//
					console.log(that.roles)

				});

		},
		//Crea un nuevo usuario en la tabla usuarios
		saveUser: function () {
            var that = this;     
			var formData = that.toFormData(that.newUser);
			axios.post("http://localhost/VersionFinalProyectoAula/src/back/api.php?action=createuser", formData)
				.then(function (response) {
					console.log(response);
					that.newUser = { id: "", nombre: "", apellido: "", correo: "", clave: "", credencial: "", idlista: "", idprograma: "",idrol: "" };
					console.log(that.newUser)
					if (response.data.error) {
						that.errorUserMessage = response.data.message;
						//console.log(response.data.message)
					} else {
						console.log("entro melo")
						that.successUserMessage = response.data.message;
						that.getAllUsers();
					}
				});
		},
		//Crea un nuevo rol en la tabla roles
		saveRol: function () {
          var that = this;     
			var formData = that.toFormData(that.newRol);
			axios.post("http://localhost/VersionFinalProyectoAula/src/back/api.php?action=createrol", formData)
				.then(function (response) {
					console.log(response);
					that.newRol = { id: "", nombre: "", descripcion: "" };
					console.log(that.newRol)
					if (response.data.error) {
						that.errorRolMessage = response.data.message;
						//console.log(response.data.message)
					} else {
						console.log("entro melo")
						that.successRolMessage = response.data.message;
						that.getAllRoles();
					}
				});
		},
		//Crea un nuevo rol en la tabla roles
		savePrograma: function () {
      var that = this;    
			var formData = that.toFormData(that.newPrograma);
			axios.post("http://localhost/VersionFinalProyectoAula/src/back/api.php?action=saveProgramaFacultad", formData)
				.then(function (response) {
					console.log(response);
					that.newPrograma= { id: "", nombre: "", facultad: "",snies:"",duracion:"",modalidad:"",reacreditacion:"",icfes:"",renovacion:"",tipo:"" }
					if (response.data.error) {
						that.errorProgramaMessage = response.data.message;
						//console.log(response.data.message)
					} else {
						console.log("entro melo")
						that.successProgramaMessage = response.data.message;
						that.getAllRoles();
					}
				});
		},
		//Actualiza un usuario dependiendo del id de este
		updateUser: function () {
			var that = this;
			var formData = that.toFormData(that.clickedUser);
			axios.post("http://localhost/VersionFinalProyectoAula/src/back/api.php?action=updateuser", formData)
				.then(function (response) {
					that.clickedUser = {};
					if (response.data.error) {
						that.errorUserMessage = response.data.message;
					} else {
						that.successUserMessage = response.data.message;
						console.log("Melo Wey")
						that.getAllUsers();
					}
				});
		},
		//Actualiza un rol dependiendo del id de este
		updatePrograma: function () {
      var that = this;
			var formData = that.toFormData(that.clickedPrograma);
			axios.post("http://localhost/VersionFinalProyectoAula/src/back/api.php?action=updateProgramasFacultad", formData)
				.then(function (response) {
					console.log(response);
					that.clickedPrograma = {};
					if (response.data.error) {
						that.errorProgramaMessage = response.data.message;
					} else {
						that.successProgramaMessage = response.data.message;
					}
				});
		},
		//Actualiza un programa dependiendo del id de este
		updateRol: function () {
      var that = this;
			var formData = that.toFormData(that.clickedRol);
			axios.post("http://localhost/VersionFinalProyectoAula/src/back/api.php?action=updaterol", formData)
				.then(function (response) {
					that.clickedRol = {};
					if (response.data.error) {
						that.errorRolMessage = response.data.message;
					} else {
						that.successRolMessage = response.data.message;
						that.getAllRoles();
					}
				});
		},
		//Elimina un usuario dependiendo del id de este
		deleteUser: function () {
      var that = this;
			var formData = that.toFormData(that.clickedUser);
			axios.post("http://localhost/VersionFinalProyectoAula/src/back/api.php?action=deleteuser", formData)
				.then(function (response) {
					that.clickedUser = {};
					if (response.data.error) {
						that.errorUserMessage = response.data.message;
					} else {
						that.successUserMessage = response.data.message;
						that.getAllUsers();
					}
				});
		},
		//Elimina un programa dependiendo del id de este
		deletePrograma: function () {
      var that = this;
			var formData = that.toFormData(that.clickedPrograma);
			axios.post("http://localhost/VersionFinalProyectoAula/src/back/api.php?action=deletePrograma", formData)
				.then(function (response) {
					console.log(response);
					that.clickedPrograma = {};
					if (response.data.error) {
						that.errorUserMessage = response.data.message;
					} else {
						that.successProgramaMessage = response.data.message;

					}
				});
		},
		//Elimina un Rol dependiendo del id de este
		deleteRol: function () {
      var that = this;
			var formData = that.toFormData(that.clickedRol);
			axios.post("http://localhost/VersionFinalProyectoAula/src/back/api.php?action=deleterol", formData)
				.then(function (response) {
					console.log(response);
					that.clickedRol = {};
					if (response.data.error) {
						that.errorRolMessage = response.data.message;
					} else {
						that.successRolMessage = response.data.message;
						that.getAllRoles();
					}
				});
		},
		//Establece el usuario seleccionado
		selectUser(user) {
      var that = this;
			that.clickedUser = user;
			console.log("Pruebas")
			console.log(that.clickedUser.idrol)
			console.log(user)
		},
		//Establece el rol seleccionado
		selectRol(rol) {
      var that = this;
      that.clickedRol = rol;
		},
		selectPrograma(programa) {
      var that = this;
      that.clickedPrograma = programa;
		},
		//Traduce los datos a la tabla
		toFormData: function (obj) {
			var form_data = new FormData();
			for (var key in obj) {
				form_data.append(key, obj[key]);
			}
			return form_data;
		},
		//Limpia los mensajes de las notificaciones
		clearMessage: function () {
			that.errorUserMessage = "";
			that.successUserMessage = "";
			that.errorRolMessage = "";
			that.successRolMessage = "";
			that.errorProgramaMessage = "";
			that.successProgramaMessage = "";
		},
	}
};
</script>

<!--Hoja de estilos-->
<style>
*{
	margin: 0;
	padding: 0;
}

body {
    margin: 0;
    font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    text-align: left;
    background-color: #e0e0e0;
}
.left{
	float: left;
	display: block;
	overflow: hidden;
}
.right{
	float: right;
	margin-left: 10px;
}
.fix{
	overflow: hidden;
	clear: both;
}
h1{
	font-size: 25px;

}

div#root {
    margin-top: 50px;
    background: #f3f3f3;
    padding: 50px;
}
button {
  padding: 0 15px;
  border: 0;
  background: #02a2ff;
  color: #fff;
  border-radius: 3px;
  cursor: pointer;
  outline: 0;
}

table.list {
  width: 100%;
}
table.list tr {
  margin: 5px !important;

  border: 1px solid #fff;
}
table.list th {
  background: #02a2ff;
  padding: 2px;
  text-align: center;
}
.crud_header {
  background: #e0e0e0;
  padding: 35px 22px 20px 20px;
}
table.list tr td {
  padding: 10px;

  margin: 2px !important;

  background: #ddd;

  overflow: hidden;

  text-align: center;

  margin-top: 5px;

  border-right: 1px solid #fff;
}

.modalheading {
  background: #fff;
  padding: 5px;
  font-size: 20px;
  line-height: 32px;
}
.modalcontent {
  padding: 10px;
}
table.form {
  width: 100%;
}
.form tr {
  text-align: center;
  border-bottom: 5px solid #b6b6b6;
}
.form td {
  text-align: left;
  margin: 0 10px;
  border: 5px solid #b6b6b6;
}
.form th {
  text-align: right;

  border-right: 5px solid #b6b6b6;
}
.margin {
  margin: 10px 0;
}
.center {
  text-align: center;
  display: block;
  margin: 0 auto;
}

.form td input {
  padding: 5px 10px;
  outline: 0;
}
.modal{
	top: 99px;
	left:0;
	right:0;
	bottom: auto;
	position: fixed;
	background: #b5b5b5fc;
	margin: 0 auto;
	display: block;
	padding: 0;
	height: auto;
	box-shadow: 0 0 130px 28px #00000073;
}
.modalheading{
	background: #fff;
	padding: 5px;
	font-size: 20px;
	line-height: 32px;
}
.modalcontent {
    padding: 10px;
}
table.form {
    width: 100%;
}
.form tr{
text-align: center;
border-bottom: 5px solid #b6b6b6;
}
.form td{
text-align: left;
margin: 0 10px;
border: 5px solid #b6b6b6;
}
.form th{
	
text-align: right;
	
border-right: 5px solid #b6b6b6;
}
.margin{
	margin: 10px 0;
}
.center {
    text-align: center;
    display: block;
    margin: 0 auto;
}
p.errorMessage {
    background: #ffbaba;
    padding: 10px;
    border-left: 5px solid #f00;
}
p.successMessage {
    background: #a2ffa2;
    padding: 10px;
    border-left: 5px solid #008c1e;
}
.form td input {
    padding:5px 10px;
    outline: 0;
}
</style>
