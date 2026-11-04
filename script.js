// StreamZone front-end enhancements. PHP remains responsible for all database updates.
document.addEventListener('DOMContentLoaded', () => {
  // Filter the already-loaded movie cards as the visitor types.
  const search = document.querySelector('input[name="search"]');
  const genre = document.querySelector('select[name="genre"]');
  const cards = [...document.querySelectorAll('#movieList .movie-card')];
  const status = document.querySelector('#liveSearchStatus');
  if (search && cards.length) {
    const filterMovies = () => {
      const term = search.value.trim().toLowerCase();
      const selectedGenre = genre ? genre.value.toLowerCase() : '';
      let count = 0;
      cards.forEach(card => {
        const match = card.dataset.title.toLowerCase().includes(term) &&
          (!selectedGenre || card.dataset.genre.toLowerCase() === selectedGenre);
        card.hidden = !match;
        if (match) count++;
      });
      if (status) status.textContent = `${count} movie${count === 1 ? '' : 's'} shown`;
    };
    search.addEventListener('input', filterMovies);
    if (genre) genre.addEventListener('change', filterMovies);
    filterMovies();
  }

  // Confirm destructive actions; canceling prevents the PHP form submission.
  document.querySelectorAll('form[data-confirm]').forEach(form => {
    form.addEventListener('submit', event => {
      if (!window.confirm(form.dataset.confirm)) event.preventDefault();
    });
  });

  // Helpful client-side check; PHP also validates registrations securely.
  const registration = document.querySelector('#registerForm');
  if (registration) registration.addEventListener('submit', event => {
    const password = registration.querySelector('input[name="password"]');
    if (password && password.value.length < 8) {
      event.preventDefault();
      password.setCustomValidity('Use at least 8 characters.');
      password.reportValidity();
    }
  });
  const password = registration?.querySelector('input[name="password"]');
  password?.addEventListener('input', () => password.setCustomValidity(''));
});
