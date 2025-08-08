<?php
require 'config.php';
include 'header.php';
// Display content from DB
$stmt = $pdo->query('SELECT * FROM content ORDER BY created_at DESC');
$contents = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h1>Content Page</h1>
<?php if(isset($_SESSION['user_id'])): ?>
  <a class="btn btn-primary mb-3" href="create_content.php">Create New Content</a>
<?php endif; ?>
<?php foreach($contents as $c): ?>
  <div class="card mb-3">
    <div class="card-body">
      <h5 class="card-title"><?php echo htmlspecialchars($c['title']); ?></h5>
      <p class="card-text"><?php echo nl2br(htmlspecialchars($c['body'])); ?></p>
      <?php if($c['image']): ?>
        <img src="uploads/<?php echo htmlspecialchars($c['image']); ?>" alt="" style="max-width:200px;">
      <?php endif; ?>
      <?php if(isset($_SESSION['user_id'])): ?>
        <div class="mt-2">
          <a class="btn btn-sm btn-secondary" href="edit_content.php?id=<?php echo $c['id']; ?>">Edit</a>
          <a class="btn btn-sm btn-danger" href="delete_content.php?id=<?php echo $c['id']; ?>" onclick="return confirm('Delete?')">Delete</a>
        </div>
      <?php endif; ?>
    </div>
  </div>
<?php endforeach; ?>
<?php include 'footer.php'; ?>
