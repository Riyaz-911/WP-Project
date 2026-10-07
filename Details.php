<?php
require_once "db.php";

$stmt = $pdo->query("SELECT * FROM enquiries ORDER BY created_at DESC");
$enquiries = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Enquiries - Decoder Classes</title>
<link rel="stylesheet" href="style.css">
<style>
body{font-family:Arial,sans-serif;background:#f5f7fb;margin:0;padding:30px;color:#17203a}
.wrap-admin{max-width:1200px;margin:auto}
.table-box{background:#fff;border-radius:16px;padding:20px;overflow:auto;box-shadow:0 8px 30px rgba(0,0,0,.08)}
table{width:100%;border-collapse:collapse;min-width:900px}
th,td{padding:12px;border-bottom:1px solid #e5e9f2;text-align:left;vertical-align:top}
th{background:#f3f6fc}
h1{margin-top:0}
.empty{text-align:center;padding:30px}
</style>
</head>
<body>
<div class="wrap-admin">
<h1>Enquiry List</h1>
<div class="table-box">
<?php if (!$enquiries): ?>
  <div class="empty">No enquiries received yet.</div>
<?php else: ?>
<table>
<thead>
<tr>
<th>ID</th><th>Date</th><th>Student</th><th>Parent</th><th>Mobile</th>
<th>Class</th><th>Course</th><th>Batch</th><th>Message</th>
</tr>
</thead>
<tbody>
<?php foreach ($enquiries as $row): ?>
<tr>
<td><?= (int)$row['id'] ?></td>
<td><?= htmlspecialchars($row['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars($row['student_name'], ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars($row['parent_name'], ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars($row['mobile'], ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars($row['class_name'], ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars($row['course'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars($row['preferred_batch'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
<td><?= nl2br(htmlspecialchars($row['message'] ?? '', ENT_QUOTES, 'UTF-8')) ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php endif; ?>
</div>
</div>
</body>
</html>
