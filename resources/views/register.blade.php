<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>register</title>
    <link rel="stylesheet" rel='stylesheet' href="/bootstrap/css/bootstrap.min.css" />
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" charset="utf-8"></script>
    <script src="/js/register/index.js" charset="utf-8"></script>
  </head>
  <body>
    <form id="register_form" action="/register" method="GET">
      <h1>Register</h1>
      <input type='hidden' name='action' value='submit' />
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

      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text">Confirm the password</span>
        </div>
        <input type="password" class="form-control" name='confirm_password' placeholder="Confirm the password" aria-label="Username" aria-describedby="basic-addon1">
      </div>

      <button type="button" class="btn btn-primary" name='submits'>Submit</button>
    </form>

  </body>
</html>
