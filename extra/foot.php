<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Footer</title>

  <!-- Offline Font Awesome -->
  <script src="https://kit.fontawesome.com/1165876da6.js" crossorigin="anonymous"></script>

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
    }

    footer {
      background-color: #000;
      color: #fff;
      padding: 40px 20px;
    }

    .con {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      max-width: 1200px;
      margin: auto;
    }

    .foot_con,
    .social_con {
      flex: 1 1 300px;
      margin: 20px;
    }

    .foot_con h1,
    .social_con h1 {
      font-size: 1.5rem;
      margin-bottom: 15px;
    }

    .foot_con p {
      margin: 8px 0;
      font-size: 1rem;
    }

    .social_con ul {
      padding: 0;
    }

    .social_con li {
      list-style: none;
      display: inline-block;
      margin: 8px 10px 0 0;
    }

    .social_con a {
      color: #fff;
      font-size: 1.2rem;
      transition: color 0.3s;
    }

    .social_con a:hover {
      color: #1da1f2;
    }

    .bottom_bar {
      text-align: center;
      margin-top: 40px;
      width: 100%;
      font-size: 0.9rem;
      color: #ffffff;
    }
    .social-icons{
        text-align: center;
        padding: 0;
    }
    .social-icons li{
        display: inline-block;
        text-align: center;
        padding: 5px;
    }
    .social-icons i{
        color: white;
        font-size: 25px;
    }
    .social-icons i:hover{
        color: #f18930;
    }
    html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    }

    .wrapper {
    min-height: 100%;
    display: flex;
    flex-direction: column;
    }

    .wrapper > footer {
    margin-top: auto;
    }

    

   
  </style>
</head>    
<body>
  <div class="wrapper">
  <footer>
    <div class="con">
      <div class="foot_con">
        <h1>Contact Us</h1>
        <p>Email: xyz@gmail.com</p>
        <p>Phone: 01793245656</p>
        <p>Address: XYZ Street, Dhaka, 1212</p>
      </div>

      <div class="social_con">
        <h1>Follow Us</h1>
        <ul class="social-icon">
          <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
          <li><a href="#"><i class="fab fa-twitter"></i></a></li>
          <li><a href="#"><i class="fab fa-instagram"></i></a></li>
          <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
        </ul>
      </div>
    </div>

    <div class="bottom_bar">
      <p>&copy; 2025 69travels. All Rights Reserved.</p>
    </div>
  </footer>
  </div>

</body>
</html>
