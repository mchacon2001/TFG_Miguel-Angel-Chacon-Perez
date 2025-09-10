/**
 * Service worker allowing not connection data and other caché and notification features
 */


// Abre una conexión con la base de datos IndexedDB
const dbConnection = indexedDB.open("brainygym_db", 1);

// Manejo de errores y actualizaciones de la base de datos
dbConnection.onerror = (event) => {
    console.log("Error al abrir la base de datos:", event.target.error);
};

dbConnection.onupgradeneeded = (event) => {
    const db = event.target.result;

    // Crea un almacén de objetos (object store) en la base de datos
    if (!db.objectStoreNames.contains("user")) {
        db.createObjectStore("user", { keyPath: "id" });
    }
};




const getUserData = async () => {

    return new Promise((resolve, reject) => {
        // in database find user data
        const request = dbConnection.result.transaction("user", "readwrite").objectStore("user").get(1);
        request.onsuccess = (event) => {
            const user = event.target.result;
            if (user) {
                resolve(JSON.parse(user.value));
            }
        }
        request.onerror = (event) => {
            console.error("Error al obtener el usuario:", event.target.error);
            reject(event.target.error);
        }
    });

}


// Claim control of all clients under scope
self.addEventListener('activate', event => {
    event.waitUntil(
        self.clients.claim(),
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cache) => {
                    if (cache !== cacheName) {
                        return caches.delete(cache);
                    }
                })
            );
        })
    );
});


addEventListener('fetch', async function (event) {
    let requestUrl = event.request.url;

    // if requestUrl contains "/api/documents/render" add authorization header to fetch request
    if (requestUrl.indexOf("/api/documents/render-document") > -1) {
        event.respondWith(new Promise(async (resolve, reject) => {
            try {
                let userData = await getUserData();
                if (userData && userData.token) {
                    const response = await fetch(event.request.url, {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: {
                            'accept': event.request.headers.get("accept"),
                            'Authorization': `Bearer ${userData.token}`
                        },
                    });

                    if (response.status === 200) {
                        resolve(response);
                    } else {
                        reject(response);
                    }
                } else {
                    resolve(new Response("User not logged in", { status: 500 }));
                }
            } catch (error) {
                console.error("Error", error);
                resolve(new Response("User not logged in", { status: 500 }));
            }
        }));
    }

});




