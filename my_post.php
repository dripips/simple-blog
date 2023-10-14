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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_post'])) {
        $post_id = $_POST['post_id'];
        $deleted = deletePost($pdo, $post_id);

        if ($deleted) {
            $message = 'Пост успешно удален.';
        } else {
            $message = 'Ошибка при удалении поста.';
        }

        echo json_encode(['message' => $message]);
        exit();
    }
}

$posts = getPostsByUserId($pdo, $user_id);
?>

<?php include 'templates/header.php'; ?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Мои посты</h1>

    <table class="table">
        <thead>
            <tr>
                <th>Заголовок</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post) : ?>
                <tr>
                    <td><?php echo $post['title']; ?></td>
                    <td>
                        <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn btn-warning edit-post">Редактировать</a>
                        <button class="btn btn-danger delete-post" data-postid="<?php echo $post['id']; ?>">Удалить</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    $('.delete-post').on('click', function() {
        var postId = $(this).data('postid');
        if (confirm('Вы уверены, что хотите удалить этот пост?')) {
            $.ajax({
                type: 'POST',
                url: 'my_post.php',
                data: {
                    delete_post: 1,
                    post_id: postId
                },
                success: function(data) {
                    var response = JSON.parse(data);
                    alert(response.message);
                    location.reload();
                }
            });
        }
    });
});
</script>

<?php include 'templates/footer.php'; ?>
