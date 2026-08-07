document.addEventListener('DOMContentLoaded', function () {
    var revealEls = document.querySelectorAll('[data-reveal]');
    if ('IntersectionObserver' in window && revealEls.length) {
        var observer = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.15 }
        );

        revealEls.forEach(function (el) {
            observer.observe(el);
        });
    } else {
        revealEls.forEach(function (el) {
            el.classList.add('is-visible');
        });
    }
});
