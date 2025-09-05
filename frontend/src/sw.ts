import sha256 from "crypto-js/sha256";

//const SW_VERSION = "v3";
const SW_VERSION_KEY = "sw_version";

/**
 * Script for register service worker
 */
export async function registerServiceWorker() {
  return new Promise((resolve, reject) => {
    if ("serviceWorker" in navigator) {
      window.addEventListener("load", async () => {
        navigator.serviceWorker
          .register(`/service-worker.js`, {updateViaCache: 'none', scope: '/'})
          .then((registration) => {

            // avoid that the serviceWorker is active but not loaded. This happen when hard refreshing the page.
            if(registration?.active && !navigator.serviceWorker.controller) {
              window.location.reload();
            }


            if (registration.active?.state !== "activated") {

              // Mark action as resolved
              resolve(null);

            } else {

              // check the hash of the service-worker.js file and if it is different then update service worker and reload the page
              fetch(`/service-worker.js`)
                .then((response) => response.text())
                .then((text) => {

                  const savedHash  = localStorage.getItem(SW_VERSION_KEY);
                  const hash = sha256(text);

                  // Delete service worker when is different to cached.
                  if (hash.toString() !== savedHash) {
                    registration.unregister().then(() => {
                      localStorage.setItem(SW_VERSION_KEY, hash.toString());
                      window.location.reload();
                    });
                  } else {
                    resolve(null);
                  }
                });
            }

          })
          .catch((error) => {
            console.log("Fallo al registrar el Service Worker:", error);
            reject(error);
          });
      });
    }
  });

}