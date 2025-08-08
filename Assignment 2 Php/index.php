<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Fetch items and owner's username
$stmt = $pdo->query("SELECT items.*, users.username FROM items JOIN users ON items.user_id = users.id ORDER BY items.created_at DESC");
$items = $stmt->fetchAll();
?>
<div class="row">
  <div class="col-12">
    <h1 class="mb-3">All Items</h1>
    <?php if (empty($items)): ?>
      <div class="alert alert-info">No items yet. <?php if (is_logged_in()): ?><a href="create.php">Create one</a>.<?php endif; ?></div>
    <?php endif; ?>
  </div>
</div>

<div class="row g-3">
  <?php foreach ($items as $item): ?>
    <div class="col-md-6 col-lg-4">
      <div class="card h-100">
        <?php if (!empty($item['image'])): ?>
          <img src="uploads/<?= htmlspecialchars($item['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['title']) ?>" style="object-fit:cover; height:200px;">
        <?php else: ?>
          <div class="placeholder-img" style="height:200px; display:flex;align-items:center;justify-content:center;background:#f5f5f5;">No image</div>
        <?php endif; ?>
        <div class="card-body d-flex flex-column">
          <h5 class="card-title"><?= htmlspecialchars($item['title']) ?></h5>
          <p class="card-text small text-muted mb-1">By <?= htmlspecialchars($item['username']) ?> — <?= htmlspecialchars($item['created_at']) ?></p>
          <p class="card-text"><?= nl2br(htmlspecialchars($item['description'])) ?></p>
          <div class="mt-auto">
            <?php if (is_logged_in() && current_user_id() == $item['user_id']): ?>
              <a href="edit.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
              <a href="delete.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this item?');">Delete</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
