<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
  <table>
  	 <tr><td>Dear {{ $name }}</td></tr>
  	 <tr><td>&nbsp;</td></tr>
  	 <tr><td>Please Click On below link to activate your stack developers account:-</td></tr>
  	 <tr><td>&nbsp;</td></tr>
  	 <tr><td><a href="{{ url('/user/confirm/'.$code) }}">Confirm Account</a></td></tr>
  	 <tr><td>&nbsp;</td></tr>
  	 <tr><td>&nbsp;</td></tr>
  	 <tr><td>Thanks & Regards,</td></tr>
  	 <tr><td>Stack Developers</td></tr>

  </table>
</body>
</html>