<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="card auth-card">
    <div class="card-body p-4">
        <h3 class="text-center mb-4">Register</h3>

        <form action="/registerProcess" method="POST">
            <?= csrf_field() ?>
            
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
                <?php if(isset($validation) && $validation->hasError('password')): ?>
                    <small class="text-danger"><?= $validation->getError('password') ?></small>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required>
                <?php if(isset($validation) && $validation->hasError('confirm_password')): ?>
                    <small class="text-danger"><?= $validation->getError('confirm_password') ?></small>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-success w-100">Register</button>
        </form>
        <div class="text-center mt-3">
            <a href="/login" class="text-decoration-none">Already have an account? Login</a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>