(function () {
    const pills = document.querySelectorAll('.pill');
    const posts = document.querySelectorAll('[data-topic]');
    const noResults = document.querySelector('[data-no-results]');

    if (!pills.length || !posts.length) return;

    pills.forEach(function (pill) {
        pill.addEventListener('click', function () {
            const filter = pill.getAttribute('data-filter');

            pills.forEach(function (p) {
                p.classList.remove('pill--active');
                p.setAttribute('aria-selected', 'false');
            });
            pill.classList.add('pill--active');
            pill.setAttribute('aria-selected', 'true');

            let visibleCount = 0;

            posts.forEach(function (post) {
                const matches = filter === 'all' || post.getAttribute('data-topic') === filter;
                post.classList.toggle('post--hidden', !matches);
                if (matches) visibleCount++;
            });

            if (noResults) noResults.hidden = visibleCount > 0;
        });
    });
})();
