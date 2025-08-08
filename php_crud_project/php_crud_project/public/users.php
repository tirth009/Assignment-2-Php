<?php
require 'config.php';
if(!isset($_SESSION['user_id'])){ header('Location: index.php'); exit; }
include 'header.php';

// Handle deletion
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    $pdo->prepare('DELETE FROM users WHERE id = ?')->execute([$id]);
    echo '<div class="alert alert-success">User deleted.</div>';
}

$stmt = $pdo->query('SELECT id,name,email,avatar,created_at FROM users ORDER BY created_at DESC');
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Registered Users</h1>
<table class="table">
  <thead><tr><th>Avatar</th><th>Name</th><th>Email</th><th>Joined</th><th>Actions</th></tr></thead>
  <tbody>
  <?php foreach($users as $u): ?>
    <tr>
      <td><?php if($u['avatar']): ?><img src="uploads/<?php echo htmlspecialchars($u['avatar']); ?>" style="width:50px;"><?php endif; ?></td>
      <td><?php echo htmlspecialchars($u['name']); ?></td>
      <td><?php echo htmlspecialchars($u['email']); ?></td>
      <td><?php echo htmlspecialchars($u['created_at']); ?></td>
      <td>
        <a class="btn btn-sm btn-primary" href="edit_user.php?id=<?php echo $u['id']; ?>">Edit</a>
        <a class="btn btn-sm btn-danger" href="users.php?delete=<?php echo $u['id']; ?>" onclick="return confirm('Delete user?')">Delete</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<?php include 'footer.php'; ?>
