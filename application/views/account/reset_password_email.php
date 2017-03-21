<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Aponjon Password Reset</title>
	<style>
   /* body{
        background-color:#CCC;	
    }
    header{
        border-bottom: solid 2px #fb9f39;
        background-color:#386bb6;
        height:105px;	
    }
    .container{
        margin:10px 20px 10px 20px;
            
    }
    .content{
        padding:10px;
        background-color:#FFF;
    }
    footer{
        background-color:#386bb6;
        padding:10px 5px;
        color:#FFF;
    }*/
    </style>
</head>
<body style="background-color:#CCC; margin-bottom:10px; margin-top:10px;">
	<div style="margin:10px 20px 10px 20px;">
    	<div style="background-color:#386bb6; height:165px; margin-top:10px">
            <a href="http://prepaid.aponjon.com.bd/" target="_blank"><img src="http://prepaid.aponjon.com.bd/resource/img/aponjon_banner-email.png" /></a>
        </div>
        <div class="content" style="padding:10px; background-color:#FFF;">
        	<h2>Welcome to Aponjon</h2>
        	<p><?php echo sprintf(lang('reset_password_email'), $username, $password_reset_url); ?></p>
        </div>
        <div style="background-color:#386bb6; padding:10px 5px;color:#FFF; margin-bottom:10px;">        
            <a style="color:#FFF; text-decoration:none" href="http://prepaid.aponjon.com.bd/about_us" target="_blank">About Us</a> |  
            <a style="color:#FFF; text-decoration:none" href="http://prepaid.aponjon.com.bd/contact_us" target="_blank">Contact Us</a>
        </div>
    </div>

</body>
</html>
