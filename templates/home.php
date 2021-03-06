<?php include 'header.php'; ?>
    <div class="container">
        <?php if ($posts): ?>
            <div class="row">
                <?php foreach ($posts as $post): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?php echo htmlentities($post->getTitle()); ?>
                                </h5>
                                <p class="card-text">
                                    <?php echo htmlentities($post->getExcerpt()); ?>
                                </p>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted"><i class="fa fa-clock-o"></i> <?php echo htmlentities($post->getCreated()->format('Y-m-d')); ?></small>
                                <small class="text-muted"><i class="fa fa-edit"></i> <?php echo htmlentities($post->getUpdated()->format('Y-m-d')); ?></small>
                                <small><a href="/article/<?php echo $post->getId(); ?>"><i class="fa fa-arrow-right"></i> Read more</a></small>
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