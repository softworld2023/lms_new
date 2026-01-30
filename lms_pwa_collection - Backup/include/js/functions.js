function showSnackbar(message) {
    let snackbar = $('#snackbar');
    snackbar.html('');
    snackbar.html(message);
    snackbar.show();

    setTimeout(function() {
        snackbar.hide();
    }, 3000); // Hide after 3 seconds (adjust as needed)
}

function checkDevicePlatform() {
    const userAgent = navigator.userAgent;
    let devicePlatform = 'Unknown';

    if (userAgent.includes('iPhone') || userAgent.includes('iPad')) {
        devicePlatform = 'iOS';
    } else if (userAgent.includes('Android')) {
        devicePlatform = 'Android';
    }

    return devicePlatform;
}

async function getCacheData() {
    const keys = ['uid', 'branch'];

    try {
        const cache = await caches.open('lms-pwa-cache');
        const values = await Promise.all(keys.map(async (key) => {
            const response = await cache.match(key);
            if (response) {
                return response.text();
            } else {
                return null;
            }
        }));

        const cacheData = {};
        keys.forEach(function (key, index) {
            const value = values[index];
            if (key === 'uid') {
                cacheData.uid = value;
            } else if (key === 'branch') {
                cacheData.branch = value;
            }
        });

        return cacheData;
    } catch (error) {
        throw error;
    }
}

// Retrieve a cookie by name
function getCookie(name) {
    const cookies = document.cookie.split(';');
    console.log('Cookies: ' + cookies);
    for (let i = 0; i < cookies.length; i++) {
        const cookie = cookies[i].trim();
        if (cookie.startsWith(name + '=')) {
            return cookie.substring(name.length + 1);
        }
    }
    return null; // Cookie not found
}

function numberWithCommas(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}