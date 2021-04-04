<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Sign In - vTwo Enterpries Business Management System </title>

  <link rel="stylesheet" href="<?= base_url('resources/tempcss/login.css') ?>">

</head>

<body>
  <div class="wrapper">
    <div class="container">
      <h1>Welcome</h1>


      <?php echo validation_errors(); ?>

      <?php if (!empty($errors)) {
        echo $errors;
      } ?>

      <form action="<?= base_url('auth/login') ?>" method="post">
        <input type="text" placeholder="Username" name="username" id="username">
        <input type="password" placeholder="Password" name="password" id="password">
        <button type="submit" id="login-button">Login</button>
      </form>
    </div>

    <ul class="bg-bubbles">
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
    </ul>
  </div>
  <!-- partial -->
  <script src="<?= base_url('resources/tempjs/jquery.min.js') ?>"></script>

  <!-- <script src="<?= base_url('resources/tempjs/login.js') ?>"></script> -->

</body>

</html>