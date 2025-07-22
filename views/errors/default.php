<?php require_once APP_ROOT . '/views/header.php'; ?>

<?php if($errors){?>
<div class="alert alert-danger">
    <?=$errors;?>
</div>
<?php }?>

<?php require_once APP_ROOT . '/views/footer.php'; ?>
