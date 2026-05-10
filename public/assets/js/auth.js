(function () {
    var authScene = document.querySelector('.auth-scene');
    var passwordToggles = document.querySelectorAll('[data-password-toggle]');
    var passwordInputs = document.querySelectorAll('input[type="password"]');

    if (authScene) {
        initInteractiveBackground(authScene);
    }

    initAlienScenes();

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
        feedbackContainer.hidden = true;
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
            var hasMinLength = password.length >= 8;
            var hasNumber = /[0-9]/.test(password);
            var hasSpecial = /[!@#$%^&*()_+\-=\[\]{};:'",.\/<>?]/.test(password);
            var requirements = [
                { className: 'req-length', isMet: hasMinLength },
                { className: 'req-number', isMet: hasNumber },
                { className: 'req-special', isMet: hasSpecial }
            ];
            var hasUnmetRequirement = password.length > 0 && requirements.some(function (requirement) {
                return !requirement.isMet;
            });

            feedback.hidden = !hasUnmetRequirement;

            requirements.forEach(function (requirement) {
                updateRequirement(feedback, requirement.className, requirement.isMet);
            });
        });
    });

    function updateRequirement(feedback, className, isMet) {
        var element = feedback.querySelector('.' + className);
        if (isMet) {
            element.classList.add('met');
            element.classList.remove('unmet');
            element.hidden = true;
        } else {
            element.classList.add('unmet');
            element.classList.remove('met');
            element.hidden = false;
        }
    }

    function initInteractiveBackground(scene) {
        var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        var background = document.createElement('div');
        var nodes = [
            { x: '18%', y: '28%', depth: '0.18', accent: '#22baaa' },
            { x: '78%', y: '24%', depth: '-0.13', accent: '#ffb74d' },
            { x: '15%', y: '73%', depth: '-0.1', accent: '#e55c5c' },
            { x: '84%', y: '70%', depth: '0.16', accent: '#4f7cff' },
            { x: '50%', y: '16%', depth: '0.09', accent: '#9a6cff' }
        ];
        var lines = [
            { x: '18%', y: '28%', width: '32vw', rotate: '18deg' },
            { x: '48%', y: '22%', width: '30vw', rotate: '155deg' },
            { x: '17%', y: '72%', width: '38vw', rotate: '-24deg' },
            { x: '54%', y: '78%', width: '30vw', rotate: '-148deg' }
        ];
        var nodeElements = [];

        background.className = 'auth-background';
        background.setAttribute('aria-hidden', 'true');

        lines.forEach(function (line) {
            var lineElement = document.createElement('span');
            lineElement.className = 'contact-line';
            lineElement.style.setProperty('--line-x', line.x);
            lineElement.style.setProperty('--line-y', line.y);
            lineElement.style.setProperty('--line-width', line.width);
            lineElement.style.setProperty('--line-rotate', line.rotate);
            background.appendChild(lineElement);
        });

        nodes.forEach(function (node) {
            var nodeElement = document.createElement('span');
            nodeElement.className = 'contact-node';
            nodeElement.style.setProperty('--node-x', node.x);
            nodeElement.style.setProperty('--node-y', node.y);
            nodeElement.style.setProperty('--depth', node.depth);
            nodeElement.style.setProperty('--node-accent', node.accent);
            background.appendChild(nodeElement);
            nodeElements.push(nodeElement);
        });

        scene.insertBefore(background, scene.firstChild);

        if (prefersReducedMotion) {
            return;
        }

        scene.addEventListener('pointermove', function (event) {
            var rect = scene.getBoundingClientRect();
            var x = ((event.clientX - rect.left) / rect.width) * 100;
            var y = ((event.clientY - rect.top) / rect.height) * 100;
            var shiftX = ((x - 50) / 50) * 22;
            var shiftY = ((y - 50) / 50) * 18;

            scene.style.setProperty('--pointer-x', x + '%');
            scene.style.setProperty('--pointer-y', y + '%');
            scene.style.setProperty('--shift-x', shiftX + 'px');
            scene.style.setProperty('--shift-y', shiftY + 'px');

            nodeElements.forEach(function (nodeElement) {
                var nodeRect = nodeElement.getBoundingClientRect();
                var nodeCenterX = nodeRect.left + (nodeRect.width / 2);
                var nodeCenterY = nodeRect.top + (nodeRect.height / 2);
                var distance = Math.hypot(event.clientX - nodeCenterX, event.clientY - nodeCenterY);

                nodeElement.classList.toggle('is-near', distance < 190);
            });
        });

        scene.addEventListener('pointerleave', function () {
            scene.style.setProperty('--pointer-x', '50%');
            scene.style.setProperty('--pointer-y', '42%');
            scene.style.setProperty('--shift-x', '0px');
            scene.style.setProperty('--shift-y', '0px');

            nodeElements.forEach(function (nodeElement) {
                nodeElement.classList.remove('is-near');
            });
        });
    }

    function initAlienScenes() {
        var scenes = document.querySelectorAll('[data-alien-scene]');
        var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (!scenes.length || prefersReducedMotion) {
            return;
        }

        scenes.forEach(function (scene) {
            var aliens = scene.querySelectorAll('.alien');
            var mouseX = window.innerWidth / 2;
            var mouseY = window.innerHeight / 2;
            var smoothX = mouseX;
            var smoothY = mouseY;
            var animationFrame = null;

            function updatePointer(clientX, clientY) {
                mouseX = clientX;
                mouseY = clientY;
            }

            document.addEventListener('mousemove', function (event) {
                updatePointer(event.clientX, event.clientY);
            });

            document.addEventListener('touchmove', function (event) {
                if (event.touches.length > 0) {
                    updatePointer(event.touches[0].clientX, event.touches[0].clientY);
                }
            }, { passive: true });

            function animate() {
                smoothX += (mouseX - smoothX) * 0.12;
                smoothY += (mouseY - smoothY) * 0.12;

                aliens.forEach(function (alien) {
                    var rect = alien.getBoundingClientRect();
                    var centerX = rect.left + (rect.width / 2);
                    var centerY = rect.top + (rect.height / 2);
                    var dx = smoothX - centerX;
                    var dy = smoothY - centerY;
                    var distance = Math.sqrt((dx * dx) + (dy * dy)) || 1;
                    var nx = dx / distance;
                    var ny = dy / distance;
                    var strength = Number(alien.dataset.strength || 0.5);
                    var closeAmount = clamp(1 - (distance / 350), 0, 1);
                    var bodyX = clamp(nx * 8 * strength, -8, 8);
                    var bodyY = clamp(ny * 5 * strength, -5, 5);
                    var rotate = clamp(nx * 5 * strength, -5, 5);
                    var body = alien.querySelector('.alien-body');

                    alien.style.transform = 'translate(' + bodyX + 'px, ' + bodyY + 'px) rotate(' + rotate + 'deg)';

                    if (body) {
                        body.style.transform = 'translate(' + (nx * 5 * strength) + 'px, ' + (ny * 3 * strength) + 'px)';
                    }

                    alien.querySelectorAll('.alien-pupil').forEach(function (pupil) {
                        moveElement(pupil, clamp(nx * 6, -6, 6), clamp(ny * 6, -6, 6));
                    });

                    alien.querySelectorAll('.alien-eye').forEach(function (eye) {
                        moveElement(eye, clamp(nx * 3 * strength, -4, 4), clamp(ny * 3 * strength, -4, 4));
                    });

                    alien.querySelectorAll('.alien-mouth').forEach(function (mouth) {
                        var centerDeadZone = 0.12;
                        var mouthNX = Math.abs(nx) < centerDeadZone ? 0 : nx;
                        var mouthNY = Math.abs(ny) < centerDeadZone ? 0 : ny;
                        var mouthX = clamp(mouthNX * 9 * strength, -9, 9);
                        var mouthY = clamp(mouthNY * 3 * strength, -3, 3);
                        var openScale = 1 + (closeAmount * 1.2);

                        mouth.style.transform = 'translate(' + mouthX + 'px, ' + mouthY + 'px) scaleY(' + openScale + ')';
                    });

                    alien.querySelectorAll('.alien-leg').forEach(function (leg, index) {
                        var offset = index === 0 ? -1 : 1;

                        moveElement(
                            leg,
                            clamp(nx * 4 * strength * offset, -5, 5),
                            clamp(ny * 3 * strength, -4, 4),
                            clamp(nx * 8 * offset, -8, 8)
                        );
                    });

                    alien.querySelectorAll('.alien-foot').forEach(function (foot, index) {
                        var offset = index === 0 ? -1 : 1;

                        moveElement(
                            foot,
                            clamp(nx * 3 * strength * offset, -4, 4),
                            clamp(ny * 2 * strength, -3, 3)
                        );
                    });

                    alien.querySelectorAll('.alien-spike').forEach(function (spike, index) {
                        var offset = index % 2 === 0 ? 1 : -1;

                        moveElement(
                            spike,
                            clamp(nx * 2 * strength * offset, -3, 3),
                            clamp(ny * 2 * strength, -3, 3)
                        );
                    });

                    alien.querySelectorAll('.alien-horn').forEach(function (horn, index) {
                        var offset = index === 0 ? -1 : 1;

                        moveElement(
                            horn,
                            clamp(nx * 2 * strength * offset, -3, 3),
                            clamp(ny * 2 * strength, -3, 3),
                            clamp(nx * 8 * offset, -10, 10)
                        );
                    });

                    alien.querySelectorAll('.alien-ear').forEach(function (ear, index) {
                        var offset = index === 0 ? -1 : 1;

                        moveElement(
                            ear,
                            clamp(nx * 2 * strength * offset, -3, 3),
                            clamp(ny * 2 * strength, -3, 3),
                            clamp(nx * 5 * offset, -7, 7)
                        );
                    });
                });

                animationFrame = window.requestAnimationFrame(animate);
            }

            animationFrame = window.requestAnimationFrame(animate);

            window.addEventListener('beforeunload', function () {
                if (animationFrame) {
                    window.cancelAnimationFrame(animationFrame);
                }
            });
        });
    }

    function clamp(value, min, max) {
        return Math.max(min, Math.min(max, value));
    }

    function moveElement(element, x, y, rotate) {
        element.style.transform = 'translate(' + x + 'px, ' + y + 'px) rotate(' + (rotate || 0) + 'deg)';
    }

})();
