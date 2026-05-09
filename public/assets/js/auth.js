(function () {
    var scene = document.querySelector('[data-auth-scene]');
    var card = document.querySelector('[data-tilt-card]');
    var passwordToggles = document.querySelectorAll('[data-password-toggle]');
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function setSceneOffset(x, y) {
        if (!scene || reduceMotion) {
            return;
        }

        scene.style.setProperty('--scene-x', x.toFixed(2) + 'px');
        scene.style.setProperty('--scene-y', y.toFixed(2) + 'px');
    }

    if (scene) {
        scene.addEventListener('pointermove', function (event) {
            var rect = scene.getBoundingClientRect();
            var x = event.clientX - rect.left - rect.width / 2;
            var y = event.clientY - rect.top - rect.height / 2;

            setSceneOffset(x * 0.12, y * 0.12);
        });

        scene.addEventListener('pointerleave', function () {
            setSceneOffset(0, 0);
        });
    }

    if (card) {
        card.addEventListener('pointermove', function (event) {
            var rect = card.getBoundingClientRect();
            var x = (event.clientX - rect.left) / rect.width - 0.5;
            var y = (event.clientY - rect.top) / rect.height - 0.5;

            card.style.setProperty('--tilt-x', (x * 8).toFixed(2) + 'deg');
            card.style.setProperty('--tilt-y', (y * -8).toFixed(2) + 'deg');
        });

        card.addEventListener('pointerleave', function () {
            card.style.setProperty('--tilt-x', '0deg');
            card.style.setProperty('--tilt-y', '0deg');
        });
    }

    passwordToggles.forEach(function (passwordToggle) {
        passwordToggle.addEventListener('click', function () {
            var field = passwordToggle.closest('.auth-field');
            var passwordInput = field ? field.querySelector('input') : null;

            if (!passwordInput) {
                return;
            }

            var shouldShow = passwordInput.type === 'password';
            passwordInput.type = shouldShow ? 'text' : 'password';
            passwordToggle.textContent = shouldShow ? 'Hide' : 'Show';
            passwordToggle.setAttribute('aria-label', shouldShow ? 'Hide password' : 'Show password');
            passwordInput.focus();
        });
    });

    if (window.DeviceOrientationEvent && scene && !reduceMotion) {
        window.addEventListener('deviceorientation', function (event) {
            var gamma = event.gamma || 0;
            var beta = event.beta || 0;
            var x = Math.max(-18, Math.min(18, gamma));
            var y = Math.max(-18, Math.min(18, beta - 45));

            setSceneOffset(x * 1.1, y * 0.8);
        });
    }
})();
