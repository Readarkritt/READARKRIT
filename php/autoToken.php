<?php
?>
<html>
	<head>
	</head>
	<body>
		<form action=<?php echo '"'.$_SERVER['REQUEST_URI'].'"' ?> name="req" id="req" method="post" style="display:none"></form>
	</body>
	<script src="./js/librerias/jquery-3.1.0.min.js" type="text/javascript"></script>
		<script>
			$(document).ready(function(){
			var token = 'notValid';
			if(sessionStorage.getItem('token') != null){
				token = sessionStorage.getItem('token');
			}
			$("#req").append('<input type="text" name="token" value="'+token+'"></input>');
			document.forms['req'].submit();
		});
		</script>
</html>