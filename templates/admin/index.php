<?php include __DIR__ . '/../header.php'; ?>
    <div class="container">
        <div class="text-right mb-5">
            <a href="/admin/create" class="btn btn-primary">Create</a>
        </div>
        <?php if ($posts): ?>
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th style="width: 33%;">Title</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Updated</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td><?php echo $post->getId(); ?></td>
                            <td><?php echo $post->getTitle(); ?></td>
                            <td><?php echo $post->getStatus(); ?></td>
                            <td><?php echo $post->getCreated()->format('Y-m-d'); ?></td>
                            <td><?php echo $post->getUpdated()->format('Y-m-d'); ?></td>
                            <td class="text-right">
                                <a href="/admin/update/<?php echo $post->getId(); ?>" class="btn btn-light"><i class="fa fa-edit"></i> Edit</a>
                                <a href="/admin/delete/<?php echo $post->getId(); ?>" class="btn btn-light"><i class="fa fa-trash"></i> Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            No records.
        <?php endif; ?>
    </div>
<?php include __DIR__ . '/../footer.php'; ?>