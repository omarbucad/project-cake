<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

	<p>Hello <?php echo $name; ?>, </p>

	<p>Congrats on signing up for Gravybaby! In order to activate your account please click the button below to verify your email address: </p>
	<br>
	<a href="<?php echo site_url("login/code/".$activation_code); ?>" style="padding: 20px;margin: 30px 0px;background-color: #16A085;color:white;border-radius: 10px;text-decoration:none; ">ACTIVATE ACCOUNT</a>
	<br>
	<br>
	<p>If the button above doesn't work, paste this into your browser: </p>

	<p><a href="<?php echo site_url("login/code/".$activation_code); ?>"><?php echo site_url("login/code/".$activation_code); ?></a></p>

	<p>For additional help, visit our Support Center . </p>

	<p>YOU CAN'T BUY HAPPINESS BUT YOU CAN BUY A CAKE & THAT'S KIND OF THE SAME THING!</p>
</body>
</html>