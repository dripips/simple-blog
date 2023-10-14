<?php
function isUserExists($pdo, $username) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function registerUser($pdo, $username, $password) {
    $password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password, user_group) VALUES (?, ?, 2)");
    if ($stmt->execute([$username, $password])) {
        $user_id = $pdo->lastInsertId();

        $_SESSION['user_id'] = $user_id;
        return true;
    }

    return false;
}

function loginUser($pdo, $username, $password) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        return true;
    }

    return false;
}

function getPostsForPage($pdo, $page, $postsPerPage) {
    $offset = ($page - 1) * $postsPerPage;
    $stmt = $pdo->prepare("SELECT * FROM posts LIMIT ?, ?");
    $stmt->bindParam(1, $offset, PDO::PARAM_INT);
    $stmt->bindParam(2, $postsPerPage, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updatePagination($pdo, $currentPage, $postsPerPage) {
    $totalPosts = countPosts($pdo);
    $totalPages = ceil($totalPosts / $postsPerPage);

    echo '<nav class="col-md-12" aria-label="Page navigation">';
    echo '<ul class="pagination justify-content-center">';

    if ($currentPage > 1) {
        echo '<li class="page-item">';
        echo '<a class="page-link" href="index.php?page=' . ($currentPage - 1) . '">Предыдущая</a>';
        echo '</li>';
    }

    for ($i = 1; $i <= $totalPages; $i++) {
        echo '<li class="page-item ' . ($i === $currentPage ? 'active' : '') . '">';
        echo '<a class="page-link" href="index.php?page=' . $i . '">' . $i . '</a>';
        echo '</li>';
    }

    if ($currentPage < $totalPages) {
        echo '<li class="page-item">';
        echo '<a class="page-link" href="index.php?page=' . ($currentPage + 1) . '">Следующая</a>';
        echo '</li>';
    }

    echo '</ul>';
    echo '</nav>';
}

function countPosts($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM posts");
    return $stmt->fetchColumn();
}

function getUserGroup($pdo, $userId) {
    $stmt = $pdo->prepare("SELECT user_group FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $group = $stmt->fetch(PDO::FETCH_ASSOC);
    return $group['user_group'];
}

function createPost($pdo, $user_id, $title, $content) {
    $stmt = $pdo->prepare("INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)");
    return $stmt->execute([$user_id, $title, $content]);
}

function getPostsByUserId($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPostById($pdo, $post_id) {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$post_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updatePost($pdo, $post_id, $title, $content, $user_id) {
    $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ? AND user_id = ?");

    if ($stmt->execute([$title, $content, $post_id, $user_id])) {
        return true;
    }

    return false;
}

function createComment($pdo, $post_id, $user_id, $comment) {
    $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
    return $stmt->execute([$post_id, $user_id, $comment]);
}

function getCommentsForPost($pdo, $post_id) {
    $stmt = $pdo->prepare("SELECT * FROM comments WHERE post_id = ?");
    $stmt->execute([$post_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
