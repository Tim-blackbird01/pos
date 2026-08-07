document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('.contact-card');
    if (!form) {
        return;
    }

    form.addEventListener('submit', function () {
        var button = form.querySelector('button[type="submit"]');
        if (button) {
            button.disabled = true;
            button.textContent = 'Sending...';
        }
    });

    var overlay = document.getElementById('successOverlay');
    if (overlay && overlay.dataset.success === 'true') {
        var split = document.querySelector('.split');
        overlay.classList.add('active');
        if (split) {
            split.classList.add('blurred');
        }

        window.setTimeout(function () {
            overlay.classList.remove('active');
            if (split) {
                split.classList.remove('blurred');
            }
        }, 3800);
    }
});
