<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require '../config/database.php';
require '../includes/functions.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'load_posts':
        ajax_load_posts();
        break;
    case 'create_post':
        create_post();
        break;
    case 'edit_post':
        edit_post();
        break;
    case 'add_comment':
        add_comment();
        break;
    default:
        die('403');

}

function ajax_load_posts() {
    global $pdo;
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $postsPerPage = 10;

    $posts = getPostsForPage($pdo, $page, $postsPerPage);

    foreach ($posts as $post) {
        echo "<div class='col-md-4 mb-3'>";
        echo '  <div class="card">';
        echo '    <div class="card-body">';
        echo '        <h5 class="card-title">' . $post['title'] . '</h5>';
        echo '        <p class="card-text">' . $post['content'] . '</p>';
        echo '        <a href="view/' . $post['id'] . '" class="btn btn-primary">Читать полностью</a>';
        echo '    </div>';
        echo '  </div>';
        echo "</div>";
    }
    updatePagination($pdo, $page, $postsPerPage);
}

function create_post() {
    global $pdo;
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_POST['user_id'];

    $created = createPost($pdo, $user_id, $title, $content);

    if ($created) {
        $response = [
            'success' => true,
            'message' => 'Пост успешно создан.'
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Ошибка при создании поста.'
        ];
    }

    echo json_encode($response, JSON_FORCE_OBJECT);
    header('Content-Type: application/json');
    exit();
}

function edit_post() {
    global $pdo;
    $post_id = $_POST['post_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_POST['user_id'];

    if (empty($title) || empty($content) || empty($post_id)) {
        $response = [
            'success' => false,
            'message' => 'Пожалуйста, заполните все поля.'
        ];
    } else {
        $updated = updatePost($pdo, $post_id, $title, $content, $user_id);

        if ($updated) {
            $response = [
                'success' => true,
                'message' => 'Пост успешно отредактирован.'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Ошибка при редактировании поста.'
            ];
        }
    }

    echo json_encode($response, JSON_FORCE_OBJECT);
    header('Content-Type: application/json');
    exit();
}


function add_comment() {
    global $pdo;
    $comment = isset($_POST['comment']) ? $_POST['comment'] : '';
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

    if (empty($comment) || $post_id <= 0) {
        $response = [
            'success' => false,
            'message' => 'Пожалуйста, введите комментарий.'
        ];
    } else {
        $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : 0;
        $created = createComment($pdo, $post_id, $user_id, $comment);

        if ($created) {
            $response = [
                'success' => true,
                'message' => 'Комментарий успешно добавлен.'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Ошибка при добавлении комментария.'
            ];
        }
    }

    echo json_encode($response);
    header('Content-Type: application/json');
    exit();
}
