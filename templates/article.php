<?php include 'header.php'; ?>
    <div class="container">
        <?php if ($post): ?>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <?php echo htmlentities($post['title']); ?>
                    </h5>
                    <p class="card-text">
                        <?php echo htmlentities($post['content']); ?>
                    </p>
                </div>
                <div class="card-footer">
                    <small class="text-muted"><i class="fa fa-clock-o"></i> <?php echo htmlentities($post['created']); ?></small>
                    <small class="text-muted"><i class="fa fa-edit"></i> <?php echo htmlentities($post['updated']); ?></small>
                </div>
            </div>
        <?php else: ?>
            Post not found.
        <?php endif; ?>
    </div>
<?php include 'footer.php'; ?>