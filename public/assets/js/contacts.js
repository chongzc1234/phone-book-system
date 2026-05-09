(function () {
    // Phone validation regex - allows numbers, +, -, (), spaces, and dots
    const phoneRegex = /^[\d+\-\(\)\s\.]+$/;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // Initialize validation for add contact form
    const addContactForm = document.querySelector('form[action="/contacts/store"]');
    if (addContactForm) {
        initializeFormValidation(addContactForm);
    }

    // Initialize validation for edit contact form
    const editForm = document.getElementById('editForm');
    if (editForm) {
        initializeFormValidation(editForm);
    }

    function initializeFormValidation(form) {
        const phoneInput = form.querySelector('input[name="phone"]');
        const emailInput = form.querySelector('input[name="email"]');

        if (phoneInput) {
            phoneInput.addEventListener('blur', function () {
                validatePhoneField(this);
            });
            phoneInput.addEventListener('input', function () {
                clearFieldError(this);
            });
        }

        if (emailInput) {
            emailInput.addEventListener('blur', function () {
                validateEmailField(this);
            });
            emailInput.addEventListener('input', function () {
                clearFieldError(this);
            });
        }

        form.addEventListener('submit', function (e) {
            let isValid = true;

            if (phoneInput) {
                if (!validatePhoneField(phoneInput)) {
                    isValid = false;
                }
            }

            if (emailInput && emailInput.value.trim() !== '') {
                if (!validateEmailField(emailInput)) {
                    isValid = false;
                }
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    }

    function validatePhoneField(field) {
        const value = field.value.trim();

        if (!value) {
            showFieldError(field, 'Phone is required.');
            return false;
        }

        if (value.length < 8) {
            showFieldError(field, 'Phone must be at least 8 characters long.');
            return false;
        }

        if (!phoneRegex.test(value)) {
            showFieldError(field, 'Phone format is invalid. Use numbers, +, -, (), or spaces.');
            return false;
        }

        clearFieldError(field);
        return true;
    }

    function validateEmailField(field) {
        const value = field.value.trim();

        if (value && !emailRegex.test(value)) {
            showFieldError(field, 'Please enter a valid email address.');
            return false;
        }

        clearFieldError(field);
        return true;
    }

    function showFieldError(field, message) {
        clearFieldError(field);
        
        field.classList.add('is-invalid');
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'contact-field-error';
        errorDiv.textContent = message;
        
        field.parentNode.insertBefore(errorDiv, field.nextSibling);
    }

    function clearFieldError(field) {
        field.classList.remove('is-invalid');
        
        const errorDiv = field.parentNode.querySelector('.contact-field-error');
        if (errorDiv) {
            errorDiv.remove();
        }
    }
})();
