<?php
require 'config/database.php';
require 'includes/functions.php';

session_start();

$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($post_id <= 0) {
    header('Location: index.php');
    exit();
}

$userGroup = getUserGroup($pdo, $_SESSION['user_id']);
$post = getPostById($pdo, $post_id);
$comments = getCommentsForPost($pdo, $post_id);
?>

<?php include 'templates/header.php'; ?>

<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h2 class="card-title"><?php echo $post['title']; ?></h2>
            <p class="card-text"><?php echo $post['content']; ?></p>
        </div>
    </div>

    <h3 class="mt-4">Комментарии</h3>
    <div id="comments" class="mt-4 mb-3">
        <?php foreach ($comments as $comment) : ?>
            <div class="card">
                <div class="card-body">
                    <p class="card-text"><?php echo $comment['content']; ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if (isset($_SESSION['user_id'])) : ?>
        <form id="comment-form" method="POST" class="mt-3">
            <div class="form-group">
                <textarea name="comment" class="form-control" rows="3" required></textarea>
            </div>
            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
            <button type="submit" class="btn btn-primary">Добавить комментарий</button>
        </form>
    <?php else : ?>
        <p>Чтобы оставить комментарий, пожалуйста, <a href="login.php">войдите</a>.</p>
    <?php endif; ?>
</div>

<script>
$(document).ready(function() {
    $('#comment-form').submit(function(e) {
        e.preventDefault();

        var comment = $('textarea[name="comment"]').val();
        var post_id = $('input[name="post_id"]').val();

        $.ajax({
            type: 'POST',
            url: 'includes/engine.php?action=add_comment',
            data: {
                comment: comment,
                post_id: post_id,
                user_id: <?= $_SESSION['user_id']?>
            },
            success: function(response) {
                Toastify({
                    text: response.message,
                    duration: 3000,
                    gravity: 'top',
                    position: 'right',
                }).showToast();

                if (response.success) {
                    // Очистить поле комментария после успешной отправки
                    $('textarea[name="comment"]').val('');
                }
            }
        });
    });
});
</script>

<?php include 'templates/footer.php'; ?>
