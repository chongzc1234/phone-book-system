<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="card auth-card">
    <div class="card-body p-4">
        <h3 class="text-center mb-4">Login</h3>
        
        <!-- 错误提示 -->
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <form action="/loginProcess" method="POST">
            <?= csrf_field() ?> <!-- 安全：防跨站请求伪造 -->
            
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?= set_value('username') ?>" required>
                <?php if(isset($validation) && $validation->hasError('username')): ?>
                    <small class="text-danger"><?= $validation->getError('username') ?></small>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <div class="text-center mt-3">
            <a href="/register" class="text-decoration-none">Don't have an account? Register</a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>