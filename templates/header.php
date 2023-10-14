<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
     <link rel="stylesheet" href="/assets/css/toastify.min.css">
     <script src="/assets/js/jquery-3.7.1.js"></script>
     <script src="/assets/js/toastify-js.js"></script>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
      <a class="navbar-brand fs-5" href="/">BLOG</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ml-auto">
              <?php if (isset($_SESSION['user_id'])) : ?>
                  <?php if ($userGroup == 1) : ?>
                      <li class="nav-item">
                          <a class="nav-link" href="create_post.php">Создать пост</a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link" href="my_post.php">Мои посты</a>
                      </li>
                  <?php endif; ?>
                  <li class="nav-item">
                      <a class="nav-link" href="logout.php">Выйти</a>
                  </li>
              <?php else : ?>
                  <li class="nav-item">
                      <a class="nav-link" href="login.php">Авторизоваться</a>
                  </li>
              <?php endif; ?>
          </ul>
      </div>
  </nav>
