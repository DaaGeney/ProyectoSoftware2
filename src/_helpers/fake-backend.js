// Arreglo en almacenamiento local para usuarios registrados.
let users = JSON.parse(localStorage.getItem('users')) || [];
    
export function configureFakeBackend() {
    let realFetch = window.fetch;
    window.fetch = function (url, opts) {
        return new Promise((resolve, reject) => {
            // Ajuste en el tiempo de espera para simular la llamada api del servidor
            setTimeout(() => {

                // autenticar
                if (url.endsWith('/users/authenticate') && opts.method === 'POST') {
                    // Obtiene los parametros del post
                    let params = JSON.parse(opts.body);

                    // encontrar si algún usuario coincide con las credenciales de inicio de sesión
                    let filteredUsers = users.filter(user => {
                        return user.username === params.username && user.password === params.password;
                    });

                    if (filteredUsers.length) {
                        // devuelve los detalles del usuario y el token jwt
                        let user = filteredUsers[0];
                        let responseJson = {
                            id: user.id,
                            username: user.username,
                            firstName: user.firstName,
                            lastName: user.lastName,
                            token: 'fake-jwt-token'
                        };
                        resolve({ ok: true, text: () => Promise.resolve(JSON.stringify(responseJson)) });
                    } else {
                        reject('Nombre de usuario o contraseña incorrectos');
                    }

                    return;
                }

                // Obtener usuarios
                if (url.endsWith('/users') && opts.method === 'GET') {
                    if (opts.headers && opts.headers.Authorization === 'Bearer fake-jwt-token') {
                        resolve({ ok: true, text: () => Promise.resolve(JSON.stringify(users))});
                    } else {
                        // devuelve 401 no autorizado si el token es nulo o no válido
                        reject('Unauthorised');
                    }

                    return;
                }

                // Obtener usuarios por id
                if (url.match(/\/users\/\d+$/) && opts.method === 'GET') {
                    if (opts.headers && opts.headers.Authorization === 'Bearer fake-jwt-token') {
                        // Encuentra usuarios por el id en el arreglo de usuarios
                        let urlParts = url.split('/');
                        let id = parseInt(urlParts[urlParts.length - 1]);
                        let matchedUsers = users.filter(user => { return user.id === id; });
                        let user = matchedUsers.length ? matchedUsers[0] : null;

                        // devuelve 200 od + el usuario
                        resolve({ ok: true, text: () => JSON.stringify(user)});
                    } else {
                        // devuelve 401 no autorizado si el token es nulo o no válido
                        reject('Unauthorised');
                    }

                    return;
                }

                // registrar usuario
                if (url.endsWith('/users/register') && opts.method === 'POST') {
                    let newUser = JSON.parse(opts.body);

                    // validacion
                    let duplicateUser = users.filter(user => { return user.username === newUser.username; }).length;
                    if (duplicateUser) {
                        reject('El nombre de usuario "' + newUser.username + '" ya está en uso');
                        return;
                    }

                    // Guardar new user
                    newUser.id = users.length ? Math.max(...users.map(user => user.id)) + 1 : 1;
                    users.push(newUser);
                    localStorage.setItem('users', JSON.stringify(users));

                    // responde 200 OK
                    resolve({ ok: true, text: () => Promise.resolve() });

                    return;
                }

                // Eliminar usuario
                if (url.match(/\/users\/\d+$/) && opts.method === 'DELETE') {
                    if (opts.headers && opts.headers.Authorization === 'Bearer fake-jwt-token') {
                        // Encuentra usuarios por el id
                        let urlParts = url.split('/');
                        let id = parseInt(urlParts[urlParts.length - 1]);
                        for (let i = 0; i < users.length; i++) {
                            let user = users[i];
                            if (user.id === id) {
                                // Eliminando usuario
                                users.splice(i, 1);
                                localStorage.setItem('users', JSON.stringify(users));
                                break;
                            }
                        }

                        // responde 200 OK
                        resolve({ ok: true, text: () => Promise.resolve() });
                    } else {
                        // devuelve 401 no autorizado si el token es nulo o no válido
                        reject('Unauthorised');
                    }

                    return;
                }

                realFetch(url, opts).then(response => resolve(response));

            }, 500);
        });
    }
}