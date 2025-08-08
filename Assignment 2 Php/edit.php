<?php
require_once 'includes/db.php';
require_once 'includes/header.php';
require_once 'includes/auth.php';

require_login();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM items WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch();

if (!$item) {
    exit('Item not found.');
}

// Ownership check
if ($item['user_id'] != current_user_id()) {
    exit('Unauthorized.');
}

$errors = [];
$title = $item['title'];
$description = $item['description'];
$imageFilename = $item['image'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($title === '') {
        $errors[] = 'Title is required.';
    }

    // Image replacement handling
    if (!empty($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Error uploading image.';
        } else {
            $allowed = ['image/jpeg','image/png','image/gif'];
            $maxSize = 2 * 1024 * 1024; // 2MB
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $_FILES['image']['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mime, $allowed)) {
                $errors[] = 'Only JPG, PNG or GIF images allowed.';
            }
            if ($_FILES['image']['size'] > $maxSize) {
                $errors[] = 'Image too large (max 2MB).';
            }

            if (empty($errors)) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $newFilename = uniqid('img_', true) . '.' . $ext;
                $dest = __DIR__ . '/uploads/' . $newFilename;
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                    $errors[] = 'Failed to move uploaded image.';
                } else {
                    // remove old image file if exists
                    if (!empty($imageFilename) && file_exists(__DIR__ . '/uploads/' . $imageFilename)) {
                        @unlink(__DIR__ . '/uploads/' . $imageFilename);
                    }
                    $imageFilename = $newFilename;
                }
            }
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE items SET title = ?, description = ?, image = ? WHERE id = ?");
        $stmt->execute([$title, $description, $imageFilename, $id]);
        header('Location: index.php');
        exit;
    }
}
?>

<div class="row">
  <div class="col-md-8 offset-md-2">
    <h2>Edit Item</h2>

    <?php if (!empty($errors)): ?>
      <div class="alert alert-danger">
        <ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
      </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" novalidate>
      <div class="mb-3">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input name="title" class="form-control" required value="<?= htmlspecialchars($title) ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="5"><?= htmlspecialchars($description) ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Current image</label>
        <div>
          <?php if (!empty($imageFilename)): ?>
            <img src="uploads/<?= htmlspecialchars($imageFilename) ?>" alt="current" style="max-width:200px;">
          <?php else: ?>
            <div class="small text-muted">No image</div>
          <?php endif; ?>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Replace image (optional)</label>
        <input type="file" name="image" accept="image/*" class="form-control">
        <small class="form-text text-muted">Uploading a new image will replace the old one. Max 2MB.</small>
      </div>

      <button class="btn btn-primary">Save changes</button>
      <a href="index.php" class="btn btn-link">Cancel</a>
    </form>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>
