<?php include 'header.php'; ?>
    <div class="container">
        <?php if ($posts): ?>
            <div class="row">
                <?php foreach ($posts as $post): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?php echo htmlentities($post['title']); ?>
                                </h5>
                                <p class="card-text">
                                    <?php echo htmlentities($post['excerpt']); ?>
                                </p>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted"><i class="fa fa-clock-o"></i> <?php echo htmlentities($post['created']); ?></small>
                                <small class="text-muted"><i class="fa fa-edit"></i> <?php echo htmlentities($post['updated']); ?></small>
                                <small><a href="/article/<?php echo $post['id']; ?>"><i class="fa fa-arrow-right"></i> Read more</a></small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            No posts found.
        <?php endif; ?>
    </div>
<?php include 'footer.php'; ?>