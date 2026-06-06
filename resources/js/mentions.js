document.addEventListener('DOMContentLoaded', () => {
    const section = document.querySelector('[data-commentaires][data-mentions-url]');

    if (! section) {
        return;
    }

    const url = section.dataset.mentionsUrl;
    const listes = new Map();

    section.querySelectorAll('.comment-mention-textarea').forEach((textarea) => {
        const liste = document.createElement('div');
        liste.className = 'comment-mention-suggestions hidden';
        liste.setAttribute('role', 'listbox');
        textarea.insertAdjacentElement('afterend', liste);
        listes.set(textarea, liste);

        textarea.addEventListener('input', () => afficherSuggestions(textarea, liste, url));
        textarea.addEventListener('blur', () => {
            window.setTimeout(() => liste.classList.add('hidden'), 150);
        });
    });

    document.addEventListener('click', (event) => {
        const bouton = event.target.closest('[data-mention-username]');

        if (! bouton) {
            return;
        }

        const liste = bouton.closest('.comment-mention-suggestions');
        const textarea = [...listes.entries()].find(([, element]) => element === liste)?.[0];

        if (! textarea) {
            return;
        }

        insererMention(textarea, bouton.dataset.mentionUsername);
        liste.classList.add('hidden');
        textarea.focus();
    });
});

function afficherSuggestions(textarea, liste, url) {
    const pseudo = pseudoEnCours(textarea);

    if (pseudo === null) {
        liste.classList.add('hidden');
        liste.innerHTML = '';

        return;
    }

    fetch(`${url}?q=${encodeURIComponent(pseudo)}`, {
        headers: { Accept: 'application/json' },
    })
        .then((response) => response.json())
        .then((utilisateurs) => {
            if (utilisateurs.length === 0) {
                liste.classList.add('hidden');
                liste.innerHTML = '';

                return;
            }

            liste.innerHTML = utilisateurs
                .map(
                    (utilisateur) => `
                        <button type="button" class="comment-mention-suggestion" data-mention-username="${utilisateur.username}">
                            <span class="font-semibold">@${utilisateur.username}</span>
                            <span class="text-slate-500">${utilisateur.name}</span>
                        </button>
                    `
                )
                .join('');
            liste.classList.remove('hidden');
        })
        .catch(() => liste.classList.add('hidden'));
}

function pseudoEnCours(textarea) {
    const valeur = textarea.value.slice(0, textarea.selectionStart);
    const correspondance = valeur.match(/@([a-zA-Z][a-zA-Z0-9_]*)$/);

    if (! correspondance) {
        return null;
    }

    return correspondance[1];
}

function insererMention(textarea, username) {
    const debut = textarea.value.slice(0, textarea.selectionStart).lastIndexOf('@');
    const fin = textarea.selectionStart;
    const avant = textarea.value.slice(0, debut);
    const apres = textarea.value.slice(fin);

    textarea.value = `${avant}@${username} ${apres}`;
    const position = debut + username.length + 2;
    textarea.setSelectionRange(position, position);
    textarea.dispatchEvent(new Event('input', { bubbles: true }));
}
