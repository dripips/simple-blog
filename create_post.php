<?php
require 'config/database.php';
require 'includes/functions.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$userGroup = getUserGroup($pdo, $user_id);

if ($userGroup != 1) {
    header('Location: index.php');
    exit();
}
?>

<?php include 'templates/header.php'; ?>
<link rel="stylesheet" href="/assets/css/bb-editor.css">

<div class="container mt-5">
    <h1 class="text-center mb-4">Создать новый пост</h1>

    <form id="create-post-form" method="POST">
        <div class="form-group">
            <label for="title">Заголовок:</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="content">Содержание:</label>
            <div class="bb-editor-buttons">
                <button type="button" class="bb-editor-button" onclick="insertTag('bold')">Жирный</button>
                <button type="button" class="bb-editor-button" onclick="insertTag('italic')">Курсив</button>
                <button type="button" class="bb-editor-button" onclick="insertTag('underline')">Подчеркнутый</button><br>
                <button type="button" class="bb-editor-button" style="color: red;" onclick="setColor('red')">Красный</button>
                <button type="button" class="bb-editor-button" style="color: orange;" onclick="setColor('orange')">Оранжевый</button>
                <button type="button" class="bb-editor-button" style="color: yellow;" onclick="setColor('yellow')">Желтый</button>
                <button type="button" class="bb-editor-button" style="color: green;" onclick="setColor('green')">Зеленый</button>
                <button type="button" class="bb-editor-button" style="color: blue;" onclick="setColor('blue')">Синий</button>
                <button type="button" class="bb-editor-button" style="color: indigo;" onclick="setColor('indigo')">Индиго</button>
                <button type="button" class="bb-editor-button" style="color: violet;" onclick="setColor('violet')">Фиолетовый</button>
            </div>
            <div class="bb-editor-content" id="content" contenteditable="true" required></div>
        </div>
        <input type="hidden" name="create_post" value="1">
        <input type="submit" class="btn btn-primary w-100" value="Создать пост">
    </form>
</div>

<script type="text/javascript">
function insertTag(tag) {
    document.execCommand(tag, false);
}

function setColor(color) {
    document.execCommand('foreColor', false, color);
}
$(document).ready(function() {
  $('#create-post-form').submit(function(e) {
      e.preventDefault();

      var title = $('#title').val();
      var content = $('#content').html();

      $.ajax({
          type: 'POST',
          url: 'includes/engine.php?action=create_post',
          data: {
              title: title,
              content: content,
              user_id: <?=$user_id?>
          },
          success: function(response) {
              Toastify({
                  text: response.message,
                  duration: 3000,
                  gravity: 'top',
                  position: 'right',
              }).showToast();

              if (response.success) {
                  setTimeout(function() {
                      window.location.href = 'my_post.php';
                  }, 2000);
              }
          }
      });
  });
});
</script>
<?php include 'templates/footer.php'; ?>
