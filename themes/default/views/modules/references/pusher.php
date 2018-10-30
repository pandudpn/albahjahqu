Index of Pusher Server API. Biline DEV.

<script src="https://js.pusher.com/4.1/pusher.min.js"></script>
<script>

// Enable pusher logging - don't include this in production
Pusher.logToConsole = true;

var pusher = new Pusher('4319a32bebe5a27c5962', {
  cluster: 'ap1',
  encrypted: false
});

var channel = pusher.subscribe('my-channel-box');
channel.bind('my-sms-request', function(data) {
     console.log('A chat message was triggered by postman: ' + data.message);
});
</script>