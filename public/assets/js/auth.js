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

    // Password validation feedback (only on register page)
    passwordInputs.forEach(function (passwordInput) {
        var field = passwordInput.closest('.auth-field');
        if (!field) return;

        var form = passwordInput.closest('.auth-form');
        if (!form || !form.hasAttribute('data-register-form')) {
            return; // Skip if not on register page
        }

        var feedbackContainer = document.createElement('div');
        feedbackContainer.className = 'password-feedback';
        feedbackContainer.innerHTML = '<ul class="password-requirements">' +
            '<li class="req-length"><span class="req-icon">✓</span> At least 8 characters</li>' +
            '<li class="req-number"><span class="req-icon">✓</span> At least 1 number</li>' +
            '<li class="req-special"><span class="req-icon">✓</span> At least 1 special character (!@#$%^&* etc)</li>' +
            '</ul>';
        
        var existingFeedback = field.querySelector('.password-feedback');
        if (!existingFeedback) {
            field.appendChild(feedbackContainer);
        }

        passwordInput.addEventListener('input', function () {
            var password = passwordInput.value;
            var feedback = field.querySelector('.password-feedback');
            
            // Check requirements
            var hasMinLength = password.length >= 8;
            var hasNumber = /[0-9]/.test(password);
            var hasSpecial = /[!@#$%^&*()_+\-=\[\]{};:'",.\/<>?]/.test(password);

            // Update UI
            updateRequirement(feedback, 'req-length', hasMinLength);
            updateRequirement(feedback, 'req-number', hasNumber);
            updateRequirement(feedback, 'req-special', hasSpecial);
        });
    });

    function updateRequirement(feedback, className, isMet) {
        var element = feedback.querySelector('.' + className);
        if (isMet) {
            element.classList.add('met');
            element.classList.remove('unmet');
        } else {
            element.classList.add('unmet');
            element.classList.remove('met');
        }
    }

})();
