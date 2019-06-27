@include('head')
<body id="login">
<div class="login-logo">
    <a href="index.html"><img src="/static/images/logo.png" alt=""/></a>
</div>
<h2 class="form-heading">login</h2>
<div class="app-cam">
    <form action="/admin/login/index" method="post">
        <input type="text" class="text" value="E-mail address" onfocus="this.value = '';"  name="username">
        <input type="password" value="Password" onfocus="this.value = '';"  name="password">
        <div class="submit"><input type="submit" onclick="myFunction()" value="Login"></div>
    </form>
</div>
</body>
</html>
