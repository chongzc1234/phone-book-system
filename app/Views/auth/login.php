<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<link href="/assets/css/auth.css" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main class="auth-scene" data-auth-scene>
    <nav class="auth-nav" aria-label="Primary">
        <a class="auth-brand" href="/login" aria-label="Phone Book System home">PhoneBook</a>
    </nav>

    <div class="parallax-layer aurora" data-depth="0.02" aria-hidden="true"></div>
    <div class="parallax-layer moon" data-depth="0.05" aria-hidden="true"></div>
    <div class="parallax-layer stars" data-depth="0.03" aria-hidden="true"></div>
    <div class="parallax-layer mountain mountain-back" data-depth="0.07" aria-hidden="true"></div>
    <div class="parallax-layer mountain mountain-front" data-depth="0.11" aria-hidden="true"></div>
    <div class="parallax-layer mist mist-back" data-depth="0.13" aria-hidden="true"></div>
    <div class="parallax-layer trees trees-back" data-depth="0.18" aria-hidden="true"></div>
    <div class="parallax-layer mist mist-front" data-depth="0.22" aria-hidden="true"></div>
    <div class="parallax-layer trees trees-mid" data-depth="0.28" aria-hidden="true"></div>
    <div class="parallax-layer trees trees-front" data-depth="0.42" aria-hidden="true"></div>
    <div class="parallax-layer ground" data-depth="0.5" aria-hidden="true"></div>
    <div class="parallax-layer foliage" data-depth="0.62" aria-hidden="true"></div>
    <div class="parallax-layer stag" data-depth="0.36" aria-hidden="true">
        <span class="stag-body"></span>
        <span class="stag-neck"></span>
        <span class="stag-head"></span>
        <span class="stag-antler left"></span>
        <span class="stag-antler right"></span>
        <span class="stag-leg one"></span>
        <span class="stag-leg two"></span>
        <span class="stag-leg three"></span>
        <span class="stag-leg four"></span>
    </div>
    <div class="fireflies parallax-layer" data-depth="0.46" aria-hidden="true">
        <span></span><span></span><span></span><span></span><span></span><span></span>
    </div>

    <section class="auth-panel-wrap" aria-labelledby="login-title">
        <div class="auth-panel" data-tilt-card>
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
