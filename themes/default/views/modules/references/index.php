Index of Home. Biline DEV.

<script type="text/javascript">
// var car_query_api = "https://www.carqueryapi.com/api/0.3/?callback=?";
// $.getJSON(car_query_api, {cmd:"getMakes", year:"2009"}, function(data) {
//    var makes = data.Makes;
//    for (var i = 0; i < makes.length; i++)
//    {
//        console.log(makes[i].make_display);
//    }
// });
</script>
<script type="text/javascript">
// var json_url = "http://localhost/introduction-bilinedev/hmvc/home/experiment";
// $.getJSON(json_url, {paramTest: "testing"}, function(resp) {
//  // var parsed = $.parseJSON(data);
//  console.log(resp.data);
// });
</script>
<script type="text/javascript">

if (!!window.EventSource) {
  var event_source = '<?php echo site_url() ?>' + 'references/stream_event';
  var source = new EventSource(event_source);
} else { console.log('xhr polling'); /* Result to xhr polling */ }

source.addEventListener('message', function(e) { var data = JSON.parse(e.data); console.log(data); }, false);
source.addEventListener('open', function(e) { /* Connection was opened. */ }, false);
source.addEventListener('error', function(e) { if (e.readyState == EventSource.CLOSED) { /* Connection was closed. */ } }, false);

</script>