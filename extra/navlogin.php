<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation Bar</title>
    <style type="text/css">
        *{
            text-decoration:none;
        }
        .navbar{
            font-family:calibri; padding-right:15px; padding-left:15px;
        }
        .navdiv{
            display:flex; align-items:center; justify-content:space-between;
        }
        .logo a{
            font-size:35px; font-weight:700; color:black;
        }
        li{
            list-style:none; display:inline-block;
        }
        li a {
            color: black;
            font-size: 18px;
            font-weight: bold;
            margin-right: 25px;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        li a:hover {
        color: #2980b9; /* Change text color on hover */
        }

        
        button{
            background-color:black; margin-left:10px; border-radius:10px; padding:10px; width:90px
        }
        button a{
            color:white; font-weight:bold; font-size:15px;
        }

    </style></style>
</head>
<body>
    <nav class="navbar">
        <div class="navdiv">
            <div class="logo"><a href="#">99travels</a></div>
            <ul>
                <li><a href=""#">Route</a></li>
                <li><a href=""#">Branch Location</a></li>
                <li><a href=""#">Contact Us</a></li>
                <li><a href=""#">FAQ</a></li>
                <button><a href="logout.php">Logout</a></button>
            </ul>
        </div>
    </nav>
</body>
</html>
