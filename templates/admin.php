<?php include 'header.php'; ?>
    <div class="container">
        <?php if (!$action): ?>
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
                                <td><?php echo $post['id']; ?></td>
                                <td><?php echo $post['title']; ?></td>
                                <td><?php echo $post['status']; ?></td>
                                <td><?php echo $post['created']; ?></td>
                                <td><?php echo $post['updated']; ?></td>
                                <td class="text-right">
                                    <a href="/admin/update/<?php echo $post['id']; ?>" class="btn btn-light"><i class="fa fa-edit"></i> Edit</a>
                                    <a href="/admin/delete/<?php echo $post['id']; ?>" class="btn btn-light"><i class="fa fa-trash"></i> Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                No records.
            <?php endif; ?>
        <?php endif; ?>
        <?php if (in_array($action, ['create', 'update'])): ?>
            <form method="POST">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" name="title" id="title" placeholder="Enter title" value="<?php echo htmlentities($post['title']); ?>">
                </div>
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea name="content" id="content" cols="30" rows="10" class="form-control" placeholder="Enter content"><?php echo $post['content']; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="draft"<?php echo $post['status'] === 'draft' ? ' selected': ''; ?>>Draft</option>
                        <option value="publish"<?php echo $post['status'] === 'publish' ? ' selected': ''; ?>>publish</option>
                    </select>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
<?php include 'footer.php'; ?>