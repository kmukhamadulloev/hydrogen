<?php

// Configuration
$storage_path = 'storage';
$max_upload_size = 2 * 1024 * 1024; // 2 MB

// Functions
function get_file_path($file) {
    global $storage_path;
    return realpath($storage_path) . '/' . basename($file);
}

function get_files() {
    global $storage_path;
    $files = [];
    if (is_dir($storage_path)) {
        $items = scandir($storage_path);
        foreach ($items as $item) {
            if ($item !== '.' && $item !== '..') {
                $files[] = $item;
            }
        }
    }
    return $files;
}

function handle_upload() {
    global $storage_path, $max_upload_size;
    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];
        if ($file['error'] === UPLOAD_ERR_OK) {
            if ($file['size'] <= $max_upload_size) {
                $dest_path = get_file_path($file['name']);
                if (move_uploaded_file($file['tmp_name'], $dest_path)) {
                    header('Location: .');
                    exit;
                } else {
                    echo '<div class="alert alert-danger">Failed to move uploaded file.</div>';
                }
            } else {
                echo '<div class="alert alert-danger">File is too large. Max size is 2MB.</div>';
            }
        } else {
            echo '<div class="alert alert-danger">File upload error.</div>';
        }
    }
}

function handle_delete() {
    if (isset($_GET['delete'])) {
        $file_path = get_file_path($_GET['delete']);
        if (file_exists($file_path)) {
            if (is_dir($file_path)) {
                rmdir($file_path);
            } else {
                unlink($file_path);
            }
        }
        header('Location: .');
        exit;
    }
}

function handle_edit() {
    if (isset($_POST['edit_file']) && isset($_POST['content'])) {
        $file_path = get_file_path($_POST['edit_file']);
        if (file_exists($file_path) && is_writable($file_path)) {
            file_put_contents($file_path, $_POST['content']);
        }
        header('Location: .');
        exit;
    }
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handle_upload();
    handle_edit();
}

// Handle GET requests
handle_delete();

$files = get_files();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
        }
        .file-manager {
            max-width: 800px;
            margin: 0 auto;
        }
        .file-actions {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>
    <div class="file-manager">
        <h1 class="mb-4">File Manager</h1>

        <!-- Upload Form -->
        <div class="card mb-4">
            <div class="card-header">Upload File</div>
            <div class="card-body">
                <form action="." method="post" enctype="multipart/form-data">
                    <div class="input-group">
                        <input type="file" class="form-control" name="file" required>
                        <button class="btn btn-primary" type="submit">Upload</button>
                    </div>
                    <small class="form-text text-muted">Max file size: 2MB</small>
                </form>
            </div>
        </div>

        <!-- File List -->
        <div class="card">
            <div class="card-header">Files in `storage`</div>
            <ul class="list-group list-group-flush">
                <?php if (empty($files)): ?>
                    <li class="list-group-item text-center text-muted">No files found.</li>
                <?php else: ?>
                    <?php foreach ($files as $file): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>
                                <?php if (is_dir(get_file_path($file))): ?>
                                    <strong><?= htmlspecialchars($file) ?>/</strong>
                                <?php else: ?>
                                    <?= htmlspecialchars($file) ?>
                                <?php endif; ?>
                            </span>
                            <div class="file-actions">
                                <a href="/storage/<?= urlencode($file) ?>" target="_blank" class="btn btn-sm btn-primary">Download</a>
                                <?php if (!is_dir(get_file_path($file))): ?>
                                <a href="?edit=<?= urlencode($file) ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <?php endif; ?>
                                <a href="?delete=<?= urlencode($file) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this file?')">Delete</a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <?php if (isset($_GET['edit'])): ?>
        <?php
        $edit_file = $_GET['edit'];
        $file_path = get_file_path($edit_file);
        $file_url = $storage_path . '/' . urlencode($edit_file);
        $content = file_exists($file_path) && !is_dir($file_path) ? file_get_contents($file_path) : '';
        $file_extension = pathinfo($edit_file, PATHINFO_EXTENSION);
        $editable_extensions = ['txt', 'conf', 'php', 'js', 'css', 'html', 'json', 'xml', 'md'];
        $is_editable = in_array($file_extension, $editable_extensions);
        $is_image = in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif']);
        $is_video = in_array($file_extension, ['mp4', 'webm', 'ogg']);
        ?>
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= $is_editable ? 'Edit File' : 'View File' ?>: <?= htmlspecialchars($edit_file) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php if ($is_editable): ?>
                        <form action="." method="post">
                            <input type="hidden" name="edit_file" value="<?= htmlspecialchars($edit_file) ?>">
                            <textarea name="content" class="form-control" rows="15"><?= htmlspecialchars($content) ?></textarea>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    <?php elseif ($is_image): ?>
                        <img src="<?= $file_url ?>" class="img-fluid" alt="<?= htmlspecialchars($edit_file) ?>">
                    <?php elseif ($is_video): ?>
                        <video src="<?= $file_url ?>" class="w-100" controls></video>
                    <?php else: ?>
                        <p>This file type cannot be edited or previewed.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php if (isset($_GET['edit'])): ?>
    <script>
        const editModal = new bootstrap.Modal(document.getElementById('editModal'));
        editModal.show();
    </script>
    <?php endif; ?>
</body>
</html>
