<?php require_once APP_ROOT . '/views/header.php'; 
/* @var $blog \App\Models\Blog */
?>

<h1><?=$blog->getTitle(); ?></h1>
<p><?=$blog->getDescription(); ?></p>

<?php require_once APP_ROOT. '/views/footer.php'; ?>
