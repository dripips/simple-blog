<?php
require 'config/database.php';
require 'includes/functions.php';

session_start();

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$postsPerPage = 10;

$totalPosts = countPosts($pdo);

$userGroup = getUserGroup($pdo, $_SESSION['user_id']);

$totalPages = ceil($totalPosts / $postsPerPage);

$posts = getPostsForPage($pdo, $page, $postsPerPage);

?>

<?php include 'templates/header.php'; ?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Добро пожаловать в мой блог</h1>
    <div class="row g-2">
    <?php foreach ($posts as $post) : ?>
      <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?php echo $post['title']; ?></h5>
                <p class="card-text"><?php echo $post['content']; ?></p>
                <a href="view/<?php echo $post['id']; ?>" class="btn btn-primary w-100">Читать полностью</a>
            </div>
        </div>
      </div>
    <?php endforeach; ?>
    <?php if ($totalPages > 1) : ?>
    <nav class="col-md-12" aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <li class="page-item <?php echo $page === 1 ? 'disabled' : ''; ?>">
                <a class="page-link" href="index.php?page=<?php echo $page - 1; ?>">Предыдущая</a>
            </li>
            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                    <a class="page-link" href="index.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?php echo $page === $totalPages ? 'disabled' : ''; ?>">
                <a class="page-link" href="index.php?page=<?php echo $page + 1; ?>">Следующая</a>
            </li>
        </ul>
    </nav>
    <?php endif; ?>
    </div>
</div>

<script>
$(document).ready(function() {
    $('ul.pagination a').on('click', function(e) {
        e.preventDefault();
        var page = $(this).attr('href').split('=')[1];
        loadPosts(page);
    });

    function loadPosts(page) {
        $.ajax({
            url: 'includes/engine.php?action=load_posts&page=' + page,
            type: 'GET',
            success: function(data) {
                $('.container').html(data);
            }
        });
    }
});
</script>

<?php include 'templates/footer.php'; ?>
