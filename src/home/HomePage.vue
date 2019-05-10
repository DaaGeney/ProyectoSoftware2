<template>
  <div>
    
    <h1 style="display:inline">Bienvenido {{account.user.firstName}}!</h1>
    <p style="display:inline; float:right">
      <router-link to="/login">Logout</router-link>
      <a @click="showingModal = true;">Editar usuario</a> 
    </p>
  

    <hr>
    <span>
      <a @click="cargarFavorito(account.user.username)" class="text-danger">cargar favoritos</a>
      <br>
      <a @click="deleteUser(account.user.id)" v-on:click="deleteus(account.user.username)" class="text-danger">Eliminar mi usuario</a>
    </span>
    <table class="list">
      <tr>
        <th>SYMBOl</th>
        <th>PRICE</th>
        <th>ASK</th>
        <th>BID</th>
        <th>DELETE</th>
      </tr>
      <tr v-for="usr in favoritos" :key="usr.symbol">
        <td>{{usr.symbol}}</td>
        <td>{{usr.price}}</td>
        <td>{{usr.ask}}</td>
        <td>{{usr.bid}}</td>

        <td>
          <button @click=" deletefav(usr.symbol,account.user.username)">Eliminar</button>
        </td>
      </tr>
    </table>
    <table class="list">
      <tr>
        <th>SYMBOl</th>
        <th>PRICE</th>
        <th>ASK</th>
        <th>BID</th>
        <th>OPCION</th>
      </tr>
      <tr v-for="usr in usuarios" :key="usr.symbol">
        <td>{{usr.symbol}}</td>
        <td>{{usr.price}}</td>
        <td>{{usr.ask}}</td>
        <td>{{usr.bid}}</td>

        <td>
          <button @click=" favorite(usr.symbol,account.user.username)">❤+</button>
        </td>
      </tr>
    </table>
    <!--editar un nuevo usuario-------------------------------------------------------------------------------->
			<div class="modal col-md-6" id="addmodal" v-if="showingModal">
				<div class="modalheading">
					<p class="left">Actualizar usuario</p>
					<p class="right close" @click="showingModal = false;">x</p>
					<div class="fix"></div>
				</div>
				<div class="modalbody">
					<div class="modalcontent">
						<table class="form">
							<tr>
								<th>UserName</th>
								<th>:</th>
								<td><input type="text"  search :placeholder="account.user.username" v-model="clickedUser.username" readonly disabled ></td>
							</tr>
							<tr>
              <tr>
								<th>Nombre</th>
								<th>:</th>
								<td><input type="text" search :placeholder="account.user.username" v-model="clickedUser.nombre"></td>
							</tr>
							<tr>
								<th>Apellido</th>
								<th>:</th>
								<td><input type="text" search :placeholder="account.user.lastName" v-model="clickedUser.apellido"></td>
							</tr>

							<tr>
								<th>Clave</th>
								<th>:</th>
								<td><input type="text" placeholder="clave"  v-model="clickedUser.clave" ></td>
							</tr>
							
						</table>
						<div class="margin"></div>
						<button class="center" @click="showingModal = false;username( account.user.firstName ); updateUser()  ">ACEPTAR</button>
						<div class="margin"></div>
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
      usuarios: [],
      idusuario:"",
      favoritos: [],

      id: "",
      clickedUser: {},
      showingModal: false
    };
  },
  mounted() {
    this.getAllu();
    //this.cargarFavorito()
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
    username(username){
      var that = this;
      that.idusuario=username
    },
    getAllu: function() {
      var that = this;
      var requestListado = new XMLHttpRequest();
      var request = new XMLHttpRequest();
    var  auxiliar = [];
          
      // Abre una nueva conexion, usando el req GET para traer la lista de las divisas
      var string, string2;

      requestListado.open(
        "GET",
        "https://forex.1forge.com/1.0.3/symbols?api_key=CN2qvr0mUhY8UHPwK505ij5Q82Ig2zSB",
        true
      );
      requestListado.onload = function() {
        string = requestListado.responseText;
        string = string
          .replace(/['"]+/g, "")
          .replace("[", "")
          .replace("]", "");
      };
      requestListado.send();
      request.open(
        "GET",
        "https://forex.1forge.com/1.0.3/quotes?pairs=" +
          string +
          "&api_key=CN2qvr0mUhY8UHPwK505ij5Q82Ig2zSB",
        true
      );
    
      request.onload=function(){
           
          var json = JSON.parse(request.responseText);
          // console.log("apunto")
          for(var i = 0; i<100;i++){
            auxiliar[i]= { "symbol": json[i].symbol, "price": json[i].price,"bid":json[i].bid, "ask": json[i].ask}
             //console.log("auxili")
            //console.log(auxiliar)
            }
          //  console.log(auxiliar)
            that.usuarios = auxiliar;
            for(var i = 0; i<100;i++){
            axios.get("http://localhost/VersionFinalProyectoAula/src/back/api.php?action=createdivisa", {
                params:{
                    symbol: that.usuarios[i].symbol,
                    ask: that.usuarios[i].ask,
                    bid: that.usuarios[i].bid,
                    price:that.usuarios[i].price

                }
            })
				.then(function (response) {
				//	console.log(response);
					//console.log(app.newUser)
					if (response.data.error) {
                        console.log("error: " +response.data.error)
                       
					} else {
						//console.log("entró melo")
						
					}
				});
      }
      };
         
      request.send();
    //console.log(auxiliar.length)
    },
    favorite(usera, first){
        console.log(first)
        	axios.get("http://localhost/VersionFinalProyectoAula/src/back/api.php?action=createfav", {
                params:{
                    user_id:first,
                    symbol: usera

                }
            })
				.then(function (response) {
				//	console.log(response);
					//console.log(app.newUser)
					if (response.data.error) {
                        //console.log("Error metiendo fav")
                        alert("Ya ha sido registrado como favorito")
                       
					} else {
                        //console.log("fav entró melo")
                        alert("Agregado a favoritos");
                        
						
					}
				});
    },
    deletefav(usera, first){
        	axios.get("http://localhost/VersionFinalProyectoAula/src/back/api.php?action=deletefav", {
                params:{
                    user_id:first,
                    symbol: usera

                }
            })
				.then(function (response) {
				//	console.log(response);
					//console.log(app.newUser)
					if (response.data.error) {
                        //console.log("Error metiendo fav")
                        alert("Error al eliminar")
                       
					} else {
                        //console.log("fav entró melo")
                        alert("Eliminado correctamente");
                        
						
					}
				});
    },
        deleteus(username){
          	axios.get("http://localhost/VersionFinalProyectoAula/src/back/api.php?action=deleteuser", {
                params:{
                    id:username
                }
            })
				.then(function (response) {
					console.log(response);
					//console.log(app.newUser)
					if (response.data.error) {
                        //console.log("Error metiendo fav")
                        alert("Error al eliminar")
                       
					} else {
                        //console.log("fav dentro melo")
                        alert("Eliminado correctamente");
                         location.href = "/login"			
          
          }
				});

    }
    ,cargarFavorito(id){
        console.log(id)
        var that = this;
        var requestListado = new XMLHttpRequest();
        var request = new XMLHttpRequest();
      var  auxiliar = [];
          
      
      var string;

      requestListado.open(
        "GET",
        "http://localhost/VersionFinalProyectoAula/src/back/api.php?action=getfav&user_id=" + id,
        true
      );
      requestListado.onload = function() {
         var json = JSON.parse(requestListado.responseText);
         json = json.users
  
          for(var i = 0; i<json.length; i++){
            auxiliar[i]= { "symbol": json[i].symbol_id, "price": json[i].price,"bid":json[i].bid, "ask": json[i].ask} 
 
          }
          that.favoritos=auxiliar;
      };
      requestListado.send();
    },
    //Actualiza un usuario dependiendo del id de este
		updateUser() {
       var that = this;
      //var that = this;
      
   

      axios.get("http://localhost/VersionFinalProyectoAula/src/back/api.php?action=updateuser", {
                params:{
                    id:that.idusuario,
                    nombre:that.clickedUser.nombre,
                    apellido:that.clickedUser.apellido,
                    clave: that.clickedUser.clave
                }

                })
				.then(function (response) {
					console.log(response);
					that.clickedUser = {};
					if (response.data.error) {
            that.errorUserMessage = response.data.message;
            alert("error al actualizar")
					} else {
            that.successUserMessage = response.data.message;
            alert("Actualizado correctamente en la BD")
					}
				});
		},
    

  }
};
</script>

<!--Hoja de estilos-->
<style>
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
