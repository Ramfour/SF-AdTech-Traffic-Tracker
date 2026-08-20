/* SF-AdTech — drag-and-drop для канбан-досок рекламодателя */
(function () {
  'use strict';

  const cols = document.querySelectorAll('.kanban-col');
  if (!cols.length) return;

  let dragged = null;

  document.querySelectorAll('.offer-card').forEach(card => {
    card.addEventListener('dragstart', e => {
      dragged = card;
      card.classList.add('dragging');
      e.dataTransfer.effectAllowed = 'move';
    });

    card.addEventListener('dragend', () => {
      card.classList.remove('dragging');
      cols.forEach(c => c.classList.remove('drag-over'));
    });
  });

  cols.forEach(col => {
    col.addEventListener('dragover', e => {
      e.preventDefault();
      e.dataTransfer.dropEffect = 'move';
      col.classList.add('drag-over');
    });

    col.addEventListener('dragleave', () => {
      col.classList.remove('drag-over');
    });

    col.addEventListener('drop', async e => {
      e.preventDefault();
      col.classList.remove('drag-over');

      if (!dragged) return;
      const newStatus  = col.dataset.status;
      const oldStatus  = dragged.dataset.status;
      if (newStatus === oldStatus) return;

      const offerId = dragged.dataset.id;

      try {
        const res = await fetch(BASE + '/advertiser/offers/status', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ offer_id: +offerId, status: newStatus, _csrf: CSRF }),
        });

        if (!res.ok) {
          alert('Ошибка при изменении статуса');
          return;
        }

        // Переместить карточку в новую колонку
        dragged.dataset.status = newStatus;
        col.appendChild(dragged);

        // Обновить кнопки без перезагрузки
        const btn = dragged.querySelector('button[type=submit]');
        const form = dragged.querySelector('form');
        if (newStatus === 'active') {
          form.action = form.action.replace('/activate', '/deactivate');
          btn.textContent = 'Откл.';
          btn.className = 'btn btn-sm btn-outline-warning';
        } else {
          form.action = form.action.replace('/deactivate', '/activate');
          btn.textContent = 'Вкл.';
          btn.className = 'btn btn-sm btn-outline-success';
        }
      } catch (err) {
        console.error(err);
      }
    });
  });
})();
