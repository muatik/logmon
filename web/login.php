<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="tr" lang="tr">
<title>login test</title>	
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
</head>
<body>
Login
<form action="index.php/signin" method="post">
    <input type="text" name="email" value="" />
    <input type="password" name="password" value="" />
    <input type="submit" />
</form>

Register
<form action="index.php/signup" method="post">
    <input type="text" name="email" value="" />
    <input type="password" name="password" value="" />
    <input type="submit" />
</form>
<a href="index.php/logout">logout</a>
</body>
</html>
