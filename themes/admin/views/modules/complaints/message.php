<link href="<?php echo $this->template->get_theme_path();?>assets/css/new-style.css" rel="stylesheet" />

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">
            	
            	Ticket : <?php echo $cus_support->ticket; ?>

            </h4>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- end row -->

<div class="row">
    <div class="col-12">
        <a href="<?php echo site_url('complaints/report'); ?>"><button class="btn btn-sm btn-primary waves-effect waves-light">
            <i class="zmdi zmdi-arrow-left"></i> Back</button>
        </a>
        <br><br>
        <div class="card-box table-responsive" id="container-chat">
        	<div class="container-fluid">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                        <div class="panel panel-primary">
								<div class="panel-body" id="panel-chat-body" style="max-height: 350px;">
							      	<ul class="chat" id="chatBase">

							      		<?php foreach ($cus_support_message as $key => $c) { ?>

							      			<?php if(!empty($c->message)) { ?>
								      			<?php if($c->sender_role != $user->role) {  ?>
								      			<li class="left clearfix">
										            <span class="chat-img pull-left">
										            <img src="https://ui-avatars.com/api/?name=<?php echo $c->sender_name; ?>&background=f7f436&color=000&length=1&size=45" alt="User Avatar" class="img-circle">
										            </span>
										            <div class="chat-body clearfix">
										               <div class="header">
										                  <strong class="primary-font"><?php echo $c->sender_name; ?></strong> <small class="pull-right text-muted">
										                  <span class="glyphicon glyphicon-time"></span><?php echo $c->created_on; ?></small>
										               </div>
										               <p><?php echo $c->message; ?></p>
										            </div>
										        </li>
										        <?php } else { ?>
										        <li class="right clearfix">
										            <span class="chat-img pull-right">
										            <img src="https://ui-avatars.com/api/?name=<?php echo $c->sender_name; ?>&background=4286f4&color=000&length=1&size=45" alt="User Avatar" class="img-circle">
										            </span>
										            <div class="chat-body clearfix">
										               <div class="header">
										                  <small class=" text-muted"><span class="glyphicon glyphicon-time"></span><?php echo $c->created_on; ?></small>
										                  <strong class="pull-right primary-font"><?php echo $c->sender_name; ?></strong>
										               </div>
										               <p class="pull-right"><?php echo $c->message; ?></p>
										            </div>
									          	</li>
										        <?php } ?>
									        <?php } ?>

							      		<?php } ?>

								        <!-- <li class="left clearfix">
								            <span class="chat-img pull-left">
								            <img src="https://ui-avatars.com/api/?name=OKBABE&background=f7f436&color=000&length=1&size=45" alt="User Avatar" class="img-circle">
								            </span>
								            <div class="chat-body clearfix">
								               <div class="header">
								                  <strong class="primary-font">OKBABE</strong> <small class="pull-right text-muted">
								                  <span class="glyphicon glyphicon-time"></span>26 Dec 2018 12:13 pm</small>
								               </div>
								               <p>Hallo</p>
								            </div>
								        </li>

							       		<li class="right clearfix">
								            <span class="chat-img pull-right">
								            <img src="https://ui-avatars.com/api/?name=Dealer&background=4286f4&color=000&length=1&size=45" alt="User Avatar" class="img-circle">
								            </span>
								            <div class="chat-body clearfix">
								               <div class="header">
								                  <small class=" text-muted"><span class="glyphicon glyphicon-time"></span>26 Dec 2018 12:13 pm</small>
								                  <strong class="pull-right primary-font">Dealer</strong>
								               </div>
								               <p class="pull-right">Hallo juga</p>
								            </div>
							          	</li>

							          	<li class="left clearfix">
								            <span class="chat-img pull-left">
								            <img src="https://ui-avatars.com/api/?name=Customer&background=5cf44b&color=000&length=1&size=45" alt="User Avatar" class="img-circle">
								            </span>
								            <div class="chat-body clearfix">
								               <div class="header">
								                  <strong class="primary-font">Customer</strong> <small class="pull-right text-muted">
								                  <span class="glyphicon glyphicon-time"></span>26 Dec 2018 12:13 pm</small>
								               </div>
								               <p>Hallo mas bro</p>
								            </div>
								        </li> -->

							       	</ul>
							   </div>
							   <div class="panel-footer">
							      <div class="input-group">
							         <input id="btn-input" type="text" class="form-control input-sm" placeholder="Type your message here...">
							         <span class="input-group-btn">
							         <button class="btn btn-warning btn-sm" id="btn-chat" style="height: 50px;">
							         Send</button>
							         </span>
							      </div>
							   </div>
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://js.pusher.com/4.3/pusher.min.js"></script>
<script>
	Pusher.logToConsole = true;

	var chat_message           = document.getElementById('chat_message');
	const pusher_credentials   = { app_id:"<?php echo $pusher_app_id; ?>", key:"<?php echo $pusher_app_key; ?>", secret:"<?php echo $pusher_app_secret; ?>", cluster:"<?php echo $pusher_app_cluster; ?>" };
	const pusher_subscriptions = { channel: "<?php echo $cus_support->ticket; ?>", event: ["customer_reply", "dealer_reply", "dekape_reply" ] };

	/* If We want to use PUSH.JS: Push.Permission.request(); */
	var pusher  = new Pusher(pusher_credentials.key, { cluster: pusher_credentials.cluster, encrypted: true });
	var channel = pusher.subscribe(pusher_subscriptions.channel);
	channel.bind(pusher_subscriptions.event[0], function(data) {
		console.log(data.message)
	  // chat_message.innerHTML = "Ini Customer (" + data.sender_name + ") bilang: " + data.message;
	  	if(data.sender_role != '<?php echo $user->role; ?>')
	  	{ append_chat('left', data.sender_name, data.created_on, data.message) }
	  	else
	  	{ append_chat('right', data.sender_name, data.created_on, data.message) } 
	  	
	});
	channel.bind(pusher_subscriptions.event[1], function(data) {
		console.log(data.message)
		if(data.sender_role != '<?php echo $user->role; ?>')
	  	{ append_chat('left', data.sender_name, data.created_on, data.message) }
	  	else
	  	{ append_chat('right', data.sender_name, data.created_on, data.message) } 
	  // chat_message.innerHTML = "Ini Dealer (" + data.sender_name + ") bilang: " + data.message;
	});
	channel.bind(pusher_subscriptions.event[2], function(data) {
		console.log(data.message)
		if(data.sender_role != '<?php echo $user->role; ?>')
	  	{ append_chat('left', data.sender_name, data.created_on, data.message) }
	  	else
	  	{ append_chat('right', data.sender_name, data.created_on, data.message) } 
	  // chat_message.innerHTML = "Ini Dealer (" + data.sender_name + ") bilang: " + data.message;
	});

	document.addEventListener('DOMContentLoaded', function() { });

	$(document).ready(function(){
		scrollToBottom();
	});

	function scrollToBottom(){
	    var divheight = $('#chatBase').height();
	    $('#panel-chat-body').animate({ scrollTop: divheight }, 0);
	}

	function create_chat()
	{
		$("#btn-input").attr('disabled', true);
		$("#btn-chat").attr('disabled', true);
		var msg_text = $("#btn-input").val();

		$.ajax({
			type: "POST",
			url: '<?php echo site_url().'complaints/messages/create'; ?>',
			data: { id:"<?php echo $id; ?>", message:msg_text },
			success: function (res) {
				console.log(res);
				if (res.status == 'success') { 
					// created_on = res.data.created_on;
					// append_chat_me(sender_name, sender_initial, msg_text, created_on); 
					// $("#btn-input").val('');
					//

					$("#btn-input").attr('disabled', false);
					$("#btn-chat").attr('disabled', false);
					$("#btn-input").val('');
				} 
				else { 

					$("#btn-input").attr('disabled', false);
					$("#btn-chat").attr('disabled', false);
				}
			}
		});
	}

	function append_chat(pos, name, time, message)
	{
		if(pos == 'left')
		{
			var msg = 	'<li class="left clearfix">'+
		            		'<span class="chat-img pull-left">'+
		            			'<img src="https://ui-avatars.com/api/?name='+name+'&background=f7f436&color=000&length=1&size=45" alt="User Avatar" class="img-circle">'+
		            		'</span>'+
		            		'<div class="chat-body clearfix">'+
			               		'<div class="header">'+
				                  	'<strong class="primary-font">'+name+'</strong> <small class="pull-right text-muted">'+
				                  	'<span class="glyphicon glyphicon-time"></span>'+time+'</small>'+
			               		'</div>'+
		               			'<p>'+message+'</p>'+
		            		'</div>'+
	        			'</li>';
       	}
        else
        {
	        var msg = 	'<li class="right clearfix">'+
				            '<span class="chat-img pull-right">'+
				            '<img src="https://ui-avatars.com/api/?name='+name+'&background=4286f4&color=000&length=1&size=45" alt="User Avatar" class="img-circle">'+
				            '</span>'+
				            '<div class="chat-body clearfix">'+
				               '<div class="header">'+
				                  '<small class=" text-muted"><span class="glyphicon glyphicon-time"></span>'+time+'</small>'+
				                  '<strong class="pull-right primary-font">'+name+'</strong>'+
				               '</div>'+
				               '<p class="pull-right">'+message+'</p>'+
				            '</div>'+
			          	'</li>';
		}
        

        $("#chatBase").append(msg);
        scrollToBottom();
	}

	$(document).ready(function(){
	    
	    $("#btn-chat").on("click", function(e){ 
	          create_chat();
	    });

	    $("#btn-input").on('keyup', function (e) { 
	          if (e.keyCode == 13) { 
	              create_chat();
	          } 
	    });
  	});
</script>