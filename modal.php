<?php
include 'database.php';
if (isset($_SESSION["username"])) {
    $s1 = "SELECT * FROM login WHERE (Username = '" . $_SESSION["username"] . "' OR Email='" . $_SESSION["username"] . "')" . " AND Password='" . $_SESSION["password"] . "';";
    $result = $con->query($s1);
    $num_rows = mysqli_num_rows($result);
    if ($num_rows != 1) {
        header("Location: login.php");
    }
} else {
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
        
    <title>Caja de Busqueda con efecto</title>
    <style>

        .buscar-caja {
            position: absolute;
            top: 15%;
            left: 10%;
            /* top: 50%;
            left: 50%; */
            transform: translate(-50%, -50%);
            background: #2f3640;
            height: 40px;
            border-radius: 40px;
            padding: 10px;
        }

        .buscar-caja:hover>.buscar-txt {
            width: 240px;
            padding: 0 6px;
        }

        .buscar-caja:hover>.buscar-btn {
            background: white;
            color: black;
        }

        .buscar-btn {
            color: #e84118;
            float: right;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #2f3640;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: 0.4s;
            color: white;
            cursor: pointer;
        }

        .buscar-btn>i {
            font-size: 30px;
        }

        .buscar-txt {
            border: none;
            background: none;
            outline: none;
            float: left;
            padding: 0;
            color: white;
            font-size: 16px;
            transition: 0.4s;
            line-height: 40px;
            width: 0px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="buscar-caja">
        <input type="text" name="" class="buscar-txt" placeholder="Search..." />
        <a class="buscar-btn">
            <i class="fa fa-search"></i>
        </a>
    </div>
</body>

</html>