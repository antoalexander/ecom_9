<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
  <tr><td>Dear {{ $name }}</td></tr>
  <tr><td>&nbsp;<br></td></tr>
  <tr><td>Please click on below link to confirm your Vendor Account :-</td></tr>
  <tr><td><a href="{{ url('vendor/confirm/'.$code) }}">{{ url('vendor/confirm/'.$code) }}</td></tr>
  <tr><td>&nbsp;<br></td></tr>
  <tr><td>Thanks & Records,</td></tr>
  <tr><td>&nbsp;<br></td></tr>
  <tr><td>Stack Developers</td></tr>

</body>
</html>