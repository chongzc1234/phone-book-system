<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<link href="/assets/css/auth.css" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main class="auth-scene">
    <nav class="auth-nav" aria-label="Primary">
        <a class="auth-brand" href="/login" aria-label="Phone Book System home">PhoneBook</a>
    </nav>

    <section class="auth-panel-wrap auth-panel-wrap-register" aria-labelledby="register-title">
        <div class="auth-panel">
            <p class="auth-kicker">Start your phone book</p>
            <h1 id="register-title">Register</h1>

            <form action="/registerProcess" method="POST" class="auth-form" data-register-form>
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
                    <input id="password" type="password" name="password" required autocomplete="new-password" placeholder=" ">
                    <label for="password">Password</label>
                    <button class="field-icon field-button" type="button" data-password-toggle aria-label="Show password">Show</button>
                    <?php if(isset($validation) && $validation->hasError('password')): ?>
                        <small class="auth-error"><?= esc($validation->getError('password')) ?></small>
                    <?php endif; ?>
                </div>

                <div class="auth-field">
                    <input id="confirm_password" type="password" name="confirm_password" required autocomplete="new-password" placeholder=" ">
                    <label for="confirm_password">Confirm Password</label>
                    <button class="field-icon field-button" type="button" data-password-toggle aria-label="Show confirm password">Show</button>
                    <?php if(isset($validation) && $validation->hasError('confirm_password')): ?>
                        <small class="auth-error"><?= esc($validation->getError('confirm_password')) ?></small>
                    <?php endif; ?>
                </div>

                <button type="submit" class="auth-submit">
                    <span>Register</span>
                    <span class="submit-arrow" aria-hidden="true">-></span>
                </button>
            </form>

            <p class="auth-switch">Already have an account? <a href="/login">Login</a></p>
        </div>
    </div>
</main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="/assets/js/auth.js"></script>
<?= $this->endSection() ?>
