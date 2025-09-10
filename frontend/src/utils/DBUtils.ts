export const DB = "romboc_db";
export const DB_USER = "user";
export const DB_VERSION = 1;

export const saveUser = (user: any) => {
    try {
        const serializedUser = JSON.stringify(user);
        const db = indexedDB.open(DB, DB_VERSION);

        // With indexedDB save user data
        db.onsuccess = (event) => {
            const db = (event.target as any).result;
            const transaction = db.transaction('user', 'readwrite');
            const objectStore = transaction.objectStore('user');

            // Update record.
            objectStore.put({
                id: 1,
                value: serializedUser
            });
        };

        db.onerror = (event) => {
            console.error("Error al guardar el usuario en la base de datos:", event);
        }

    } catch (error) {
        console.error("Error al guardar el usuario en la base de datos:", error);
    }
}