(function () {
    var passwordToggles = document.querySelectorAll('[data-password-toggle]');

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
