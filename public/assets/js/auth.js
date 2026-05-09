(function () {
    var scene = document.querySelector('[data-auth-scene]');
    var card = document.querySelector('[data-tilt-card]');
    var passwordToggles = document.querySelectorAll('[data-password-toggle]');
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var frameId = null;

    function setSceneOffset(x, y) {
        if (!scene || reduceMotion) {
            return;
        }

        if (frameId) {
            window.cancelAnimationFrame(frameId);
        }

        frameId = window.requestAnimationFrame(function () {
            scene.style.setProperty('--scene-x', x.toFixed(2) + 'px');
            scene.style.setProperty('--scene-y', y.toFixed(2) + 'px');
        });
    }

    if (scene) {
        scene.addEventListener('pointermove', function (event) {
            var rect = scene.getBoundingClientRect();
            var x = event.clientX - rect.left - rect.width / 2;
            var y = event.clientY - rect.top - rect.height / 2;

            setSceneOffset(x * 0.035, y * 0.035);
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

})();
