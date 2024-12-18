import Echo from "./laravel-echo";
import Pusher from "./pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: "8e49cb8e60d21e0478dc",
    cluster: "ap2",
    forceTLS: true,
});

window.Echo.channel('screen-updates')
    .listen('ScreenUpdated', (e) => {
        console.log('Update received:', e.data);
        // Update the UI as needed
    });
