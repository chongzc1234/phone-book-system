<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phone Book System</title>
    <!-- 引入 Bootstrap 5 (Requirement B1: Responsive & Attractive) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- 引入 SweetAlert2 (用于后续的高级弹窗) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #f4f7f6; }
        .auth-card { max-width: 400px; margin: 80px auto; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

    <div class="container">
        <!-- 这里是具体页面内容插入的地方 -->
        <?= $this->renderSection('content') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>