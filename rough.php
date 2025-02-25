<?php
	$string=exec('getmac');
	$mac=substr($string, 0, 17); 
	echo $mac;
?>
<html>
	<head>
		<title>
			Ip adress
		</title>
	</head>
	<body>
		<script>
			fetch('https://api.ipify.org?format=json')
			.then(response => response.json())
			.then(data => {
				console.log('Your Public IP Address:', data.ip);
			})
			.catch(error => {
				console.error('Error fetching IP:', error);
			});
		</script>
	</body>
</html>