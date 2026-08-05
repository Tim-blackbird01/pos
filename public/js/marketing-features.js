document.addEventListener('DOMContentLoaded', function () {
    const revealElements = document.querySelectorAll('[data-reveal]');
    const counterElements = document.querySelectorAll('[data-count]');

    if ('IntersectionObserver' in window) {
        const revealObserver = new IntersectionObserver(
            (entries, observer) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) {
                        return;
                    }
                    entry.target.classList.add('revealed');
                    observer.unobserve(entry.target);
                });
            },
            {
                threshold: 0.15,
            }
        );

        revealElements.forEach((element) => {
            revealObserver.observe(element);
        });
    } else {
        revealElements.forEach((element) => {
            element.classList.add('revealed');
        });
    }

    counterElements.forEach((element) => {
        const target = parseInt(element.getAttribute('data-count'), 10);
        if (Number.isNaN(target)) {
            return;
        }

        let current = 0;
        const duration = 1500;
        const stepTime = Math.max(Math.floor(duration / target), 16);
        const stepCount = Math.ceil(duration / stepTime);
        const increment = target / stepCount;

        const update = () => {
            current += increment;
            if (current >= target) {
                element.textContent = target.toLocaleString();
                return;
            }
            element.textContent = Math.floor(current).toLocaleString();
            window.requestAnimationFrame(update);
        };

        const runCounter = () => {
            if (element.dataset.countStarted) {
                return;
            }
            element.dataset.countStarted = 'true';
            window.requestAnimationFrame(update);
        };

        if ('IntersectionObserver' in window) {
            const counterObserver = new IntersectionObserver(
                (entries, observer) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            runCounter();
                            observer.unobserve(entry.target);
                        }
                    });
                },
                {
                    threshold: 0.4,
                }
            );

            counterObserver.observe(element);
        } else {
            runCounter();
        }
    });
});
