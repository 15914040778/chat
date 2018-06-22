<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style type='text/css'>
    .roomList{
      width: 30%;
      float: left;
    }
    .userMessages{
      width: 70%;
      float: left;
    }
    .clear{
      clear:both;
    }

    </style>
  </head>
  <body>

    <div class="bd-example">
      <div class="bd-example-container">
        <div class="bd-example-container-header">
          <!-- header -->
          <nav class="navbar navbar-expand-lg navbar-light bg-lightnavbar navbar-dark bg-dark">
            <!-- Navbar content -->
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                  <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Dropdown
                  </a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Something else here</a>
                  </div>
                </li>
                <li class="nav-item">
                  <a class="nav-link disabled" href="#">Disabled</a>
                </li>
              </ul>
              <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
              </form>
            </div>
          </nav>
          <!-- end header -->
        </div>
        <div class="bd-example-container-sidebar">
          <div class="bd-example" id='roomList'>
            <!-- Room list -->

            <!-- end Room list -->
          </div>
        </div>
        <div class="bd-example-container-body userMessages" id='chatRoomContents'>
          <!-- Chat contens -->
          
          <!-- end Chat contens -->
        </div>
      </div>
    </div>
    <script type="text/javascript" src='https://code.jquery.com/jquery-3.3.1.min.js'></script>
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.bootcss.com/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.bootcss.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://cdn.bootcss.com/react/15.4.2/react.min.js"></script>
  	<script src="https://cdn.bootcss.com/react/15.4.2/react-dom.min.js"></script>
  	<script src="https://cdn.bootcss.com/babel-standalone/6.22.1/babel.min.js"></script>
    <script>
      var userId = {{$userInfo->id}};
      var userName = '{{$userInfo->name}}';
    </script>
    <script src='/js/testChat/index.js' type='text/babel'></script>
  </body>
</html>
