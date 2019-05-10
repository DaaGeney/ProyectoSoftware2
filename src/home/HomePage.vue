<template>
  <div>
    
    <h1 style="display:inline">Bienvenido {{account.user.firstName}}!</h1>
    <p style="display:inline; float:right">
      <router-link to="/login">Logout</router-link>
    </p>
  

    <span v-if="users.error" class="text-danger">ERROR: {{users.error}}</span>
    <ul v-if="users.items">
      <li v-for="user in users.items" :key="user.id">
        {{user.firstName + ' ' + user.lastName}}
        <span v-if="user.deleting">
          <em>- Deleting...</em>
        </span>
        <span v-else-if="user.deleteError" class="text-danger">- ERROR: {{user.deleteError}}</span>
        <span v-else>
          -
          <a @click="deleteUser(user.id)" v-on:click="deleteus(user.username)" class="text-danger">Eliminar mi usuario</a>
          <br>
           <a @click="cargarFavorito(user.username)" class="text-danger">cargar favoritos</a>
        </span>
      </li>
    </ul>
    <table class="list">
      <tr>
        <th>SYMBOl</th>
        <th>PRICE</th>
        <th>ASK</th>
        <th>BID</th>
        <th>DELETE</th>
      </tr>
      <tr v-for="usr in favoritos">
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
      <tr v-for="usr in usuarios">
        <td>{{usr.symbol}}</td>
        <td>{{usr.price}}</td>
        <td>{{usr.ask}}</td>
        <td>{{usr.bid}}</td>

        <td>
          <button @click=" favorite(usr.symbol,account.user.username)">favorito</button>
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
//import func from '../../vue-temp/vue-editor-bridge';

export default {
  data() {
    return {
      usuarios: [],
      favoritos: [],
      clickedUser: {},
      id: ""
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
    getAllu: function() {
      var that = this;
      var requestListado = new XMLHttpRequest();
      var request = new XMLHttpRequest();
    var  auxiliar = [];
          
      // Open a new connection, using the GET request on the URL endpoint
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
          for(var i = 0; i<10;i++){
            auxiliar[i]= { "symbol": json[i].symbol, "price": json[i].price,"bid":json[i].bid, "ask": json[i].ask}
             //console.log("auxili")
            //console.log(auxiliar)
            }
            console.log(auxiliar)
            that.usuarios = auxiliar;
            for(var i = 0; i<10;i++){
            axios.get("http://localhost/vue-vuex-registration-login-example-master/src/back/api.php?action=createdivisa", {
                params:{
                    symbol: that.usuarios[i].symbol,
                    ask: that.usuarios[i].ask,
                    bid: that.usuarios[i].bid,
                    price:that.usuarios[i].price

                }
            })
				.then(function (response) {
					console.log(response);
					//console.log(app.newUser)
					if (response.data.error) {
                        console.log("error: " +response.data.error)
                       
					} else {
						//console.log("entro melo")
						
					}
				});
      }
      };
         
      request.send();
    console.log(auxiliar.length)
    },
    favorite(usera, first){
        console.log(first)
        	axios.get("http://localhost/vue-vuex-registration-login-example-master/src/back/api.php?action=createfav", {
                params:{
                    user_id:first,
                    symbol: usera

                }
            })
				.then(function (response) {
					console.log(response);
					//console.log(app.newUser)
					if (response.data.error) {
                        //console.log("Error metiendo fav")
                        alert("Ya ha sido registrado como favorito")
                       
					} else {
                        //console.log("fav dentro melo")
                        alert("Agregado a favoritos");
                        
						
					}
				});
    },
    deletefav(usera, first){
        	axios.get("http://localhost/vue-vuex-registration-login-example-master/src/back/api.php?action=deletefav", {
                params:{
                    user_id:first,
                    symbol: usera

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
                        
						
					}
				});
    },
        deleteus(username){
          	axios.get("http://localhost/vue-vuex-registration-login-example-master/src/back/api.php?action=deleteuser", {
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
        "http://localhost/vue-vuex-registration-login-example-master/src/back/api.php?action=getfav&user_id=" + id,
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
    }

  }
};
</script>

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
</style>
