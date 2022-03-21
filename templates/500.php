<?php include 'header.php'; ?>
<div class="container">
    <h1>Uncaught Exception</h1>
    <hr>
    <?php echo "{$e->getMessage()} in {$e->getFile()} line {$e->getLine()}" ?>.
</div>
<?php include 'footer.php'; ?> 