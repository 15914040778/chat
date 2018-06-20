<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" rel='stylesheet' href="/bootstrap/css/bootstrap.min.css" />
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" charset="utf-8"></script>
    <script src="/js/login/index.js" charset="utf-8"></script>
    <meta name="_token" content="{{ csrf_token() }}"/>
  </head>
  <body>
    <form action="/logion_verification" method="POST">
      <h1>Login</h1>
      <input type='hidden' name='action' value='login' />
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text">User name</span>
        </div>
        <input type="text" class="form-control" name='user_name' placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
      </div>

      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text">Password</span>
        </div>
        <input type="password" class="form-control" name='password' placeholder="Password" aria-label="Password" aria-describedby="basic-addon1">
      </div>

      <button type="button" class="btn btn-primary" name='login'>Login</button>
    </form>

  </body>
</html>
