// window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

// try {
//     window.Popper = require('popper.js').default;
//     window.$ = window.jQuery = require('jquery');

//     require('bootstrap');
// } catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
import axios from 'axios';
import Pusher from 'pusher-js';
import Echo from "laravel-echo"

window.axios = axios;
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.axios.defaults.baseURL = 'http://192.168.12.2/hr/public/index.php';
window.axios.defaults.filePath = 'http://192.168.12.2/hr/public/files/';
window.axios.defaults.imgsPath = 'http://192.168.12.2/hr/public/imgs/';

window.axios.defaults.postImagePath = 'http://192.168.12.2/hr/public/posts/';
window.axios.defaults.baseURL = window.location.origin + '/hr/public/index.php';
window.axios.defaults.filePath = window.location.origin + '/hr/public/files/';
window.axios.defaults.imgsPath = window.location.origin + '/hr/public/imgs/';
window.axios.defaults.postImagePath = window.location.origin + '/hr/public/posts/';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    encrypted: true,
});

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
