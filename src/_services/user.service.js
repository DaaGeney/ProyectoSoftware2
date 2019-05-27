import config from 'config';
import { authHeader } from '../_helpers';
import Axios from 'axios';

export const userService = {
    login,
    logout,
    register,
    getAll,
    getById,
    update,
    delete: _delete,
    cargar
};

function login(username, password) {

    const requestOptions = {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, password })
    };
    cargar();

    return fetch(`${config.apiUrl}/users/authenticate`, requestOptions)
        .then(handleResponse)
        .then(user => {
            // Ingresa si hay un JWT en la respuesta
            
            if (user.token) {
                // Guarda los detalles del usuario y el JWT en el localStorage, para que la sesion no se cierre mientras navega
                localStorage.setItem('user', JSON.stringify(user));
            }

            return user;
        });
}
function cargar() {
    axios.get("http://localhost/Software/api.php?action=readusers")
                      .then(function (response) {
                          console.log(response.data)
                          if (response.data.error) {
                             // th.errorUserMessage = response.data.message;
                          } else {
                              //this.usuario = response.data.users;
                              console.log(response.data.users[1])
                              for(var i = 0;i<response.data.users.length;i++){
                                  var auxi ={firstName:response.data.users[i].nombre,lastName:response.data.users[i].apellido,password:response.data.users[i].clave,username:response.data.users[i].id}
                                  register(auxi)
                              }

                          }     
                      });
              
}

function deletec() {
    axios.get("http://localhost/Software/api.php?action=readusers")
                      .then(function (response) {
                          console.log(response.data)
                          if (response.data.error) {
                             // th.errorUserMessage = response.data.message;
                          } else {
                              //this.usuario = response.data.users;
                              for(var i = 0;i<response.data.users.length;i++){
                                  _delete(response.data.users[i].id)
                              }

                          }     
                      });
              
}

function logout() {
    // Elimina el usuario del localStorage, para cerrar la sesion
    localStorage.removeItem('user');
}

function register(user) {
    const requestOptions = {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(user)
    };

    return fetch(`${config.apiUrl}/users/register`, requestOptions).then(handleResponse);
}

function getAll() {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };

    return fetch(`${config.apiUrl}/users`, requestOptions).then(handleResponse);
}


function getById(id) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };

    return fetch(`${config.apiUrl}/users/${id}`, requestOptions).then(handleResponse);
}

function update(user) {
    const requestOptions = {
        method: 'PUT',
        headers: { ...authHeader(), 'Content-Type': 'application/json' },
        body: JSON.stringify(user)
    };

    return fetch(`${config.apiUrl}/users/${user.id}`, requestOptions).then(handleResponse);
}

// nombre de función prefijado con guión bajo porque eliminar es una palabra reservada en javascript
function _delete(id) {
    const requestOptions = {
        method: 'DELETE',
        headers: authHeader()
    };

    return fetch(`${config.apiUrl}/users/${id}`, requestOptions).then(handleResponse);
}

function handleResponse(response) {
    return response.text().then(text => {
        const data = text && JSON.parse(text);
        if (!response.ok) {
            if (response.status === 401) {
                // cierre de sesión automático si la respuesta de la API es 401
                logout();
                location.reload(true);
            }

            const error = (data && data.message) || response.statusText;
            return Promise.reject(error);
        }

        return data;
    });
}