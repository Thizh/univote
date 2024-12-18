import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;
Pusher.logToConsole = true;


window.Echo = new Echo({
    broadcaster: "pusher",
    key: "8e49cb8e60d21e0478dc",
    cluster: "ap2",
    forceTLS: true,
    enabledTransports: ['ws', 'wss'], // Add fallbacks
    disabledTransports: ['xhr_polling', 'xhr_streaming'],
});

window.Echo.channel('screen-updates')
    .subscribed(() => {
        console.log('Subscribed to channel screen-updates');
    })
    .listenToAll((event, data) => {
        // do what you need to do based on the event name and data
        console.log(event, data)
     });


