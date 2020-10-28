<?php include __DIR__ .'/../header.php'; ?>
    <div class="container">
        <form method="POST">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" name="title" id="title" placeholder="Enter title" value="<?php echo htmlentities($post->getTitle()); ?>">
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea name="content" id="content" cols="30" rows="10" class="form-control" placeholder="Enter content"><?php echo $post->getContent(); ?></textarea>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="draft"<?php echo $post->getStatus() === 'draft' ? ' selected': ''; ?>>Draft</option>
                    <option value="publish"<?php echo $post->getStatus() === 'publish' ? ' selected': ''; ?>>Publish</option>
                </select>
            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
<?php include __DIR__ . '/../footer.php'; ?>