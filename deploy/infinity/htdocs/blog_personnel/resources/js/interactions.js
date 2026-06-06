const STYLES = {
    'article-like': {
        active: ['border-rose-200', 'bg-rose-50', 'text-rose-600', 'hover:bg-rose-100'],
        inactive: ['border-slate-200', 'bg-white', 'text-slate-500', 'hover:border-rose-200', 'hover:text-rose-500'],
        labelActive: 'Retirer le like',
        labelInactive: 'Ajouter un like',
    },
    'article-favori': {
        active: ['border-amber-200', 'bg-amber-50', 'text-amber-600', 'hover:bg-amber-100'],
        inactive: ['border-slate-200', 'bg-white', 'text-slate-500', 'hover:border-amber-200', 'hover:text-amber-500'],
        labelActive: 'Retirer des favoris',
        labelInactive: 'Ajouter aux favoris',
    },
    'comment-like': {
        active: ['text-brand-600'],
        inactive: ['text-slate-400', 'hover:text-brand-600'],
        labelActive: 'Retirer le like',
        labelInactive: 'Ajouter un like',
    },
};

const BASE_BUTTON = ['inline-flex', 'items-center', 'justify-center', 'rounded', 'transition'];

function appliquerEtatInteraction(form, active) {
    const kind = form.dataset.interactionToggle;
    const styles = STYLES[kind];

    if (!styles) {
        return;
    }

    form.dataset.active = active ? '1' : '0';

    const button = form.querySelector('[data-interaction-button]');

    if (!button) {
        return;
    }

    button.className = [
        ...BASE_BUTTON,
        ...(kind === 'comment-like' ? ['p-0.5'] : ['rounded-full', 'border', 'p-2.5']),
        ...(active ? styles.active : styles.inactive),
    ].join(' ');

    button.setAttribute('aria-label', active ? styles.labelActive : styles.labelInactive);
}

function csrfToken(form) {
    return document.querySelector('meta[name="csrf-token"]')?.content
        ?? form.querySelector('input[name="_token"]')?.value;
}

document.addEventListener('submit', async (event) => {
    const form = event.target.closest('form[data-interaction-toggle]');

    if (!form) {
        return;
    }

    event.preventDefault();

    const countEl = form.querySelector('[data-interaction-count]');

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken(form),
            },
        });

        if (!response.ok) {
            form.submit();

            return;
        }

        const data = await response.json();

        appliquerEtatInteraction(form, data.active);

        if (countEl && typeof data.count === 'number') {
            countEl.textContent = String(data.count);
        }
    } catch {
        form.submit();
    }
});

document.querySelectorAll('form[data-interaction-toggle]').forEach((form) => {
    appliquerEtatInteraction(form, form.dataset.active === '1');
});
