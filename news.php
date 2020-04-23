<?php
include 'database.php';
if (isset($_SESSION["username"])) {
  $s1 = "SELECT * FROM login WHERE (Username = '" . $_SESSION["username"] . "' OR Email='" . $_SESSION["username"] . "')" . " AND Password='" . $_SESSION["password"] . "';";
  $result = $con->query($s1);
  $num_rows = mysqli_num_rows($result);
  if ($num_rows != 1) {
    $ss1 = "<li><a href='login.php'>Login</a></li>";
  } else {
    $ss1 = '<li><a href="nifty-companies.php">NSE Details</a></li><li><a class="cta" href="news.php">News</a></li><li><a href="dashboard.php">Dashboard</a></li><li><a href="logout.php">Logout</a></li>';
  }
} else {
  $ss1 = '<li><a class="cta" href="news.php">News</a></li><li><a href="login.php">Sign In</a></li>';
}
if (isset($_POST["clear"])) {
  echo '<meta http-equiv="refresh" content="0">';
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>News</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <!-- <link href="https://fonts.googleapis.com/css?family=Montserrat:500&display=swap" rel="stylesheet"> -->
  <link href="https://fonts.googleapis.com/css?family=Raleway:100,200,300,400&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" />
  <style type="text/css">
    #loader {
      height: 100vh;
      align-items: center;
      display: flex;
      justify-content: center;
    }

    .progress {
      display: none;
    }

    .errorMsg {
      font-size: 34px;
      height: 100vh;
      align-items: center;
      display: flex;
      justify-content: center;
    }

    svg {
      fill: aliceblue;
    }

    * {
      list-style: none;
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'RaleWay', Arial, Helvetica, sans-serif;
    }

    header {
      position: sticky;
      display: flex;
      justify-content: flex-end;
      align-items: center;
      padding: 30px 10%;

      background-color: #24252a;
    }

    .logo {
      margin-right: auto;
    }

    .nav__links {
      display: flex;
      font-size: 18px;
    }

    .nav__links a,
    .cta,
    .overlay__content a {
      font-family: "Montserrat", sans-serif;
      font-weight: 500;
      color: #edf0f1;
      text-decoration: none;
    }

    .nav__links li {
      padding: 0px 20px;
    }

    .nav__links li a {
      transition: all 0.3s ease 0s;
    }

    .nav__links li a:hover {
      color: #0088a9;
    }

    .cta {
      padding: 9px 25px;
      background-color: rgba(0, 136, 169, 1);
      border: none;
      border-radius: 50px;
      cursor: pointer;
      transition: all 0.3s ease 0s;
    }

    .cta:hover {
      background-color: rgba(0, 136, 169, 0.8);
    }

    /* Mobile Nav */

    .menu {
      display: none;
    }

    .overlay {
      height: 100%;
      width: 0;
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      background-color: #24252a;
      overflow-x: hidden;
      transition: all 0.5s ease 0s;
    }

    .overlay__content {
      display: flex;
      height: 100%;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .overlay a {
      padding: 15px;
      font-size: 36px;
      display: block;
      transition: all 0.3s ease 0s;
    }

    .overlay a:hover,
    .overlay a:focus {
      color: #0088a9;
    }

    .overlay .close {
      position: absolute;
      top: 20px;
      right: 45px;
      font-size: 60px;
      color: #edf0f1;
      cursor: pointer;
    }

    @media screen and (max-height: 450px) {
      .overlay a {
        font-size: 20px;
      }

      .overlay .close {
        font-size: 40px;
        top: 15px;
        right: 35px;
      }
    }

    @media only screen and (max-width: 800px) {

      .nav__links,
      .cta {
        display: none;
      }

      .menu {
        display: initial;
      }
    }

    main {
      /* make sure to cover the screen */
      min-height: 100vh;

      /* need a solid bg to hide the footer */
      background: white;

      /* put on top */
      position: relative;
      z-index: 1;

      font: 16px/1.4 system-ui, sans-serif;
      padding: 2rem;
    }

    footer {
      /* place on the bottom */
      position: sticky;
      bottom: 0;
      left: 0;
      width: 100%;

      background: #24252a;
      display: block;
      overflow: hidden;
      /* place-items: center; */
      box-sizing: border-box;
      padding: 50px;
    }

    .inner-footer {
      display: block;
      margin: 0 auto;
      width: 1100px;
      height: 100%;

    }

    .inner-footer .logo1 {
      margin-top: 40px;
      width: 35%;
      float: left;
      height: 100%;
      display: block;
    }

    .inner-footer .logo1 svg {
      width: 65%;
      /* height: auto; */
    }

    .footer-third {
      width: calc(21.666666666667% - 20px);
      margin-right: 10px;
      float: left;
      height: 100%;
    }

    .footer-third:last-child {
      margin-right: 0;
    }

    .footer-third h1 {
      font-size: 22px;
      color: white;
      display: block;
      width: 100%;
      margin-bottom: 20px;
    }

    .inner-footer .footer-third a {
      font-size: 16px;
      color: #8ae8ff;
      display: block;
      width: 100%;
      font-weight: 200;
      /* margin-bottom: 5px; */
      padding-bottom: 5px;
    }

    .inner-footer .footer-third span {
      font-size: 12px;
      color: white;
      display: block;
      width: 100%;
      font-weight: 200;
      /* margin-bottom: 20px; */
      /* padding-bottom: 5px; */
      padding-top: 20px;
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
    }

    p {
      max-width: 600px;
      margin: 0 auto 1rem;
    }

    @media(max-width: 700px) {
      footer .inner-footer {
        width: 90%;
      }

      .inner-footer .footer-third {
        width: 100%;
        margin-bottom: 30px;
      }
    }
  </style>
</head>

<body>
  <header>
    <a class="logo" href="/">
      <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24">
        <path d="M24 3.055l-6 1.221 1.716 1.708-5.351 5.358-3.001-3.002-7.336 7.242 1.41 1.418 5.922-5.834 2.991 2.993 6.781-6.762 1.667 1.66 1.201-6.002zm-16.69 6.477l-3.282-3.239 1.41-1.418 3.298 3.249-1.426 1.408zm15.49 3.287l1.2 6.001-6-1.221 1.716-1.708-2.13-2.133 1.411-1.408 2.136 2.129 1.667-1.66zm1.2 8.181v2h-24v-22h2v20h22z" />
      </svg>
    </a>
    <ul class="nav__links">
      <li><a href="index.php">Home</a></li>
      <!-- <li><a  href="news.html">News</a></li> -->
      <?php echo $ss1; ?>
    </ul>
    <p onclick="openNav()" class="menu cta">Menu</p>
  </header>
  <div id="mobile__menu" class="overlay">
    <a class="close" onclick="closeNav()">&times;</a>
    <div class="overlay__content">
      <a href="index.php">Home</a>
      <?php echo $ss1; ?>
    </div>
  </div>

  <main>
    <div class="container">
      <form action="" method="POST">
        <div class="input-field">
          <i class="material-icons prefix">public</i>
          <input type="text" id="searchquery" />
          <label>Find what's happeing in the India......</label>
        </div>

        <div class="row">
          <input type="submit" id="searchbtn" class="btn col m2 s12" value="search" />
          <input type="reset" name="clear" id="searchbtn" class="btn col m2 s12 red right" value="clear" style="margin-top:3px;" />
        </div>
      </form>

      <div class="row">
        <div id="newsResults"></div>
      </div>

    </main>
        <footer>
    <div class="inner-footer">
      <div class="logo1">
        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24">
          <path d="M24 3.055l-6 1.221 1.716 1.708-5.351 5.358-3.001-3.002-7.336 7.242 1.41 1.418 5.922-5.834 2.991 2.993 6.781-6.762 1.667 1.66 1.201-6.002zm-16.69 6.477l-3.282-3.239 1.41-1.418 3.298 3.249-1.426 1.408zm15.49 3.287l1.2 6.001-6-1.221 1.716-1.708-2.13-2.133 1.411-1.408 2.136 2.129 1.667-1.66zm1.2 8.181v2h-24v-22h2v20h22z" />
        </svg>

      </div>
      <div class="footer-third">
        <h1>Need Help?</h1>
        <a href="#">Terms &amp; Conditions</a>
        <a href="https://raw.githubusercontent.com/tanujdey7/Project/master/LICENSE">Privacy Policy</a>
      </div>
      <div class="footer-third">
        <h1>Pages</h1>
        <a href="index.php">Home</a>
        <a href="nifty-companies.php">NSE Companies</a>
        <a href="dashboard.php">Dashboard</a>
      </div>
      <div class="footer-third">
        <h1>Developed By</h1>
        <a href="https://github.com/tanujdey7"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" /></svg>&nbsp;&nbsp; Tanuj Dey </a>
        <a href="https://github.com/maruf212000"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" /></svg> &nbsp;&nbsp;Maruf Memon</a>
            <a href="https://github.com/maruf212000"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" /></svg> &nbsp;&nbsp;Yash Gor</a>
        <span>
          Stock Predictors &copy; 2020<br>
          GLS University <br>
          Faculty of Computer Applications &amp; IT <br>
        </span>
      </div>
    </div>
  </footer>
  <script src="vendors/js/jquery-3.3.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
  <script src="vendors/js/app.js"></script>
  <script src="vendors/js/newsapi.js"></script>
</body>

</html>