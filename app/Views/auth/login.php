<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<link href="/assets/css/auth.css" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main class="auth-scene">
    <nav class="auth-nav" aria-label="Primary">
        <a class="auth-brand" href="/login" aria-label="Phone Book System home">PhoneBook</a>
    </nav>

    <section class="auth-panel-wrap" aria-labelledby="login-title">
        <div class="auth-panel">
            <p class="auth-kicker">Welcome back</p>
            <h1 id="login-title">Login</h1>

            <?php if(session()->getFlashdata('error')): ?>
                <div class="auth-alert auth-alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>
            <?php if(session()->getFlashdata('success')): ?>
                <div class="auth-alert auth-alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
            <?php endif; ?>

            <form id="login-form" action="/loginProcess" method="POST" class="auth-form">
                <?= csrf_field() ?>

                <div class="auth-field">
                    <input id="username" type="text" name="username" value="<?= esc(set_value('username')) ?>" required autocomplete="username" placeholder=" ">
                    <label for="username">Username</label>
                    <span class="field-icon" aria-hidden="true">@</span>
                    <?php if(isset($validation) && $validation->hasError('username')): ?>
                        <small class="auth-error"><?= esc($validation->getError('username')) ?></small>
                    <?php endif; ?>
                </div>

                <div class="auth-field">
                    <input id="password" type="password" name="password" required autocomplete="current-password" placeholder=" ">
                    <label for="password">Password</label>
                    <button class="field-icon field-button" type="button" data-password-toggle aria-label="Show password">Show</button>
                    <?php if(isset($validation) && $validation->hasError('password')): ?>
                        <small class="auth-error"><?= esc($validation->getError('password')) ?></small>
                    <?php endif; ?>
                </div>

                <div class="auth-options">
                    <label class="remember-check">
                        <input type="checkbox" name="remember" checked>
                        <span>Remember me</span>
                    </label>
                    <a href="/register">Create account</a>
                </div>

                <button type="submit" class="auth-submit">
                    <span>Login</span>
                    <span class="submit-arrow" aria-hidden="true">-></span>
                </button>
            </form>

            <p class="auth-switch">Don't have an account? <a href="/register">Register</a></p>
        </div>
    </div>
</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="/assets/js/auth.js"></script>
<?= $this->endSection() ?>
