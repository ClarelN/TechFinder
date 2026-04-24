@extends('template')

@section('main')
<main class="flex-grow-1 container mt-4">

    <h1 class="mb-4">Liste des Utilisateurs</h1>

    {{-- Formulaire d'ajout --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white fw-semibold">
            Ajouter un utilisateur
        </div>
        <div class="card-body">
            <form id="addForm">
                @csrf
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Matricule</label>
                        <div class="input-group">
                            <input type="text"
                                   class="form-control bg-light fw-semibold text-primary fst-italic"
                                   id="code_user_display"
                                   readonly
                                   placeholder="Chargement…">
                            <button type="button"
                                    class="btn btn-outline-secondary"
                                    id="refreshCode"
                                    title="Actualiser le matricule">
                                ↺
                            </button>
                        </div>
                        <small class="text-muted">Généré automatiquement</small>
                    </div>
                    <div class="col-md-3">
                        <label for="prenom_user" class="form-label">Prénom <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control"
                               id="prenom_user"
                               name="prenom_user"
                               placeholder="Prénom"
                               required>
                    </div>
                    <div class="col-md-3">
                        <label for="nom_user" class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control"
                               id="nom_user"
                               name="nom_user"
                               placeholder="Nom"
                               required>
                    </div>
                    <div class="col-md-3">
                        <label for="role_user" class="form-label">Rôle <span class="text-danger">*</span></label>
                        <select class="form-select"
                                id="role_user"
                                name="role_user"
                                required>
                            <option value="">Sélectionner un rôle</option>
                            <option value="admin">Admin</option>
                            <option value="technicien">Technicien</option>
                            <option value="client">Client</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="login_user" class="form-label">Login <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control"
                               id="login_user"
                               name="login_user"
                               placeholder="Login"
                               required>
                    </div>
                    <div class="col-md-3">
                        <label for="password_user" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                        <input type="password"
                               class="form-control"
                               id="password_user"
                               name="password_user"
                               placeholder="Mot de passe"
                               required>
                    </div>
                    <div class="col-md-3">
                        <label for="tel_user" class="form-label">Téléphone</label>
                        <input type="text"
                               class="form-control"
                               id="tel_user"
                               name="tel_user"
                               placeholder="Téléphone">
                    </div>
                    <div class="col-md-3">
                        <label for="sexe_user" class="form-label">Sexe</label>
                        <select class="form-select"
                                id="sexe_user"
                                name="sexe_user">
                            <option value="">Sélectionner</option>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-dark">Ajouter</button>
                    </div>
                </div>
                <div id="formErrors" class="mt-3"></div>
            </form>
        </div>
    </div>

    {{-- Tableau des utilisateurs --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-2">
            <label for="filterRole" class="form-label mb-0 fw-semibold">Filtrer par rôle :</label>
            <select id="filterRole" class="form-select form-select-sm w-auto">
                <option value="tous">Tous</option>
                <option value="admin">Admin</option>
                <option value="client">Client</option>
                <option value="technicien">Technicien</option>
            </select>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Code</th>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Login</th>
                        <th>Rôle</th>
                        <th>Téléphone</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="utilisateursTable">
                    @forelse($utilisateurs_list as $utilisateur)
                        <tr data-id="{{ $utilisateur->code_user }}">
                            <td>{{ $utilisateur->code_user }}</td>
                            <td>{{ $utilisateur->prenom_user }}</td>
                            <td>{{ $utilisateur->nom_user }}</td>
                            <td>{{ $utilisateur->login_user }}</td>
                            <td>
                                <span class="badge {{ $utilisateur->role_user === 'admin' ? 'bg-danger' : 'bg-info' }}">
                                    {{ ucfirst($utilisateur->role_user) }}
                                </span>
                            </td>
                            <td>{{ $utilisateur->tel_user ?? '—' }}</td>
                            <td class="text-center">
                                {{-- Bouton Modifier --}}
                                <button type="button"
                                        class="btn btn-warning btn-sm me-1 btn-edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal"
                                        data-code="{{ $utilisateur->code_user }}"
                                        data-prenom="{{ $utilisateur->prenom_user }}"
                                        data-nom="{{ $utilisateur->nom_user }}"
                                        data-login="{{ $utilisateur->login_user }}"
                                        data-role="{{ $utilisateur->role_user }}"
                                        data-tel="{{ $utilisateur->tel_user }}"
                                        data-sexe="{{ $utilisateur->sexe_user }}">
                                    Modifier
                                </button>

                                {{-- Bouton Supprimer --}}
                                <button type="button"
                                        class="btn btn-danger btn-sm btn-delete"
                                        data-code="{{ $utilisateur->code_user }}"
                                        data-name="{{ $utilisateur->prenom_user }} {{ $utilisateur->nom_user }}">
                                    Supprimer
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr class="empty-row">
                            <td colspan="7" class="text-center text-muted py-4">Aucun utilisateur enregistré.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div id="paginationContainer" class="d-flex justify-content-between align-items-center mt-3"></div>

</main>

{{-- Modal de modification --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="editModalLabel">Modifier l'utilisateur</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_prenom_user" class="form-label">Prénom <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control"
                               id="edit_prenom_user"
                               name="prenom_user"
                               required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_nom_user" class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control"
                               id="edit_nom_user"
                               name="nom_user"
                               required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_login_user" class="form-label">Login <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control"
                               id="edit_login_user"
                               name="login_user"
                               required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_role_user" class="form-label">Rôle <span class="text-danger">*</span></label>
                        <select class="form-select"
                                id="edit_role_user"
                                name="role_user"
                                required>
                            <option value="admin">Admin</option>
                            <option value="technicien">Technicien</option>
                            <option value="client">Client</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_tel_user" class="form-label">Téléphone</label>
                        <input type="text"
                               class="form-control"
                               id="edit_tel_user"
                               name="tel_user">
                    </div>
                    <div class="mb-3">
                        <label for="edit_sexe_user" class="form-label">Sexe</label>
                        <select class="form-select"
                                id="edit_sexe_user"
                                name="sexe_user">
                            <option value="">Sélectionner</option>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                    <div id="editFormErrors" class="mt-3"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Scripts pour les toasts, filtre, pagination et AJAX --}}
<script>
    // ===== Toasts =====
    function showToast(message, type = 'success') {
        if (type === 'success') toastr.success(message);
        else if (type === 'error') toastr.error(message);
        else if (type === 'warning') toastr.warning(message);
        else toastr.info(message);
    }

    function showErrors(errors, containerId) {
        const container = document.getElementById(containerId);
        container.innerHTML = '';
        if (errors && typeof errors === 'object') {
            Object.keys(errors).forEach(field => {
                const messages = errors[field];
                if (Array.isArray(messages)) {
                    messages.forEach(msg => {
                        const div = document.createElement('div');
                        div.className = 'alert alert-danger alert-dismissible fade show mb-2';
                        div.innerHTML = `${msg}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
                        container.appendChild(div);
                    });
                }
            });
        }
    }

    // ===== Pagination & Filtre (côté client) =====
    const ROWS_PER_PAGE = 5;
    let currentPage = 1;
    let currentFilter = 'tous';

    function getFilteredRows() {
        const rows = Array.from(document.querySelectorAll('#utilisateursTable tr[data-id]'));
        if (currentFilter === 'tous') return rows;
        return rows.filter(row => {
            const badge = row.querySelector('.badge');
            return badge && badge.textContent.trim().toLowerCase() === currentFilter;
        });
    }

    function renderPage() {
        const allDataRows = Array.from(document.querySelectorAll('#utilisateursTable tr[data-id]'));
        const filteredRows = getFilteredRows();
        const totalPages = Math.max(1, Math.ceil(filteredRows.length / ROWS_PER_PAGE));

        if (currentPage > totalPages) currentPage = totalPages;

        const start = (currentPage - 1) * ROWS_PER_PAGE;
        const end   = Math.min(start + ROWS_PER_PAGE, filteredRows.length);

        // Masquer toutes les lignes de données, puis afficher celles de la page
        allDataRows.forEach(row => row.style.display = 'none');
        filteredRows.slice(start, end).forEach(row => row.style.display = '');

        // Ligne "aucun résultat"
        const tbody = document.getElementById('utilisateursTable');
        let emptyRow = tbody.querySelector('.empty-row');
        if (filteredRows.length === 0) {
            if (!emptyRow) {
                emptyRow = document.createElement('tr');
                emptyRow.className = 'empty-row';
                tbody.appendChild(emptyRow);
            }
            emptyRow.innerHTML = allDataRows.length === 0
                ? '<td colspan="7" class="text-center text-muted py-4">Aucun utilisateur enregistré.</td>'
                : '<td colspan="7" class="text-center text-muted py-4">Aucun utilisateur pour ce filtre.</td>';
            emptyRow.style.display = '';
        } else if (emptyRow) {
            emptyRow.style.display = 'none';
        }

        renderPagination(totalPages, filteredRows.length, start, end);
    }

    function renderPagination(totalPages, total, start, end) {
        const container = document.getElementById('paginationContainer');
        if (total === 0) { container.innerHTML = ''; return; }

        const info = `<small class="text-muted">Affichage de ${start + 1} à ${end} sur ${total} utilisateur(s)</small>`;

        if (totalPages <= 1) {
            container.innerHTML = `${info}<div></div>`;
            return;
        }

        let pages = '';
        for (let i = 1; i <= totalPages; i++) {
            pages += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>`;
        }

        container.innerHTML = `
            ${info}
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="${currentPage - 1}">&laquo;</a>
                    </li>
                    ${pages}
                    <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="${currentPage + 1}">&raquo;</a>
                    </li>
                </ul>
            </nav>
        `;

        container.querySelectorAll('.page-link').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const page = parseInt(this.dataset.page);
                if (page >= 1 && page <= totalPages) {
                    currentPage = page;
                    renderPage();
                }
            });
        });
    }

    document.getElementById('filterRole').addEventListener('change', function () {
        currentFilter = this.value;
        currentPage = 1;
        renderPage();
    });

    // ===== Ajout d'utilisateur =====
    document.getElementById('addForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(this));
        try {
            const response = await fetch('{{ route("web.utilisateurs.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value
                },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            if (response.ok) {
                showToast(result.message, 'success');
                this.reset();
                document.getElementById('formErrors').innerHTML = '';
                const tbody = document.getElementById('utilisateursTable');
                const newRow = document.createElement('tr');
                newRow.setAttribute('data-id', result.data.code_user);
                newRow.innerHTML = rowHtml(result.data);
                attachDeleteListener(newRow.querySelector('.btn-delete'));
                tbody.insertBefore(newRow, tbody.firstChild);
                renderPage();
                fetchNextCode();
            } else {
                showToast(result.message || 'Erreur lors de l\'ajout', 'error');
                if (result.errors) showErrors(result.errors, 'formErrors');
            }
        } catch (error) {
            showToast('Erreur serveur: ' + error.message, 'error');
        }
    });

    // ===== Modification d'utilisateur =====
    document.getElementById('editModal').addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        document.getElementById('editForm').action          = '/Web/utilisateurs/' + btn.dataset.code;
        document.getElementById('edit_prenom_user').value  = btn.dataset.prenom;
        document.getElementById('edit_nom_user').value     = btn.dataset.nom;
        document.getElementById('edit_login_user').value   = btn.dataset.login;
        document.getElementById('edit_role_user').value    = btn.dataset.role;
        document.getElementById('edit_tel_user').value     = btn.dataset.tel || '';
        document.getElementById('edit_sexe_user').value    = btn.dataset.sexe || '';
        document.getElementById('editFormErrors').innerHTML = '';
    });

    document.getElementById('editForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const action = this.getAttribute('action');
        const data = Object.fromEntries(new FormData(this));
        try {
            const response = await fetch(action, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value
                },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            if (response.ok) {
                showToast(result.message, 'success');
                bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
                const code = action.split('/').pop();
                const row = document.querySelector(`tr[data-id="${code}"]`);
                if (row) {
                    row.innerHTML = rowHtml(result.data);
                    attachDeleteListener(row.querySelector('.btn-delete'));
                    renderPage();
                }
            } else {
                showToast(result.message || 'Erreur lors de la modification', 'error');
                if (result.errors) showErrors(result.errors, 'editFormErrors');
            }
        } catch (error) {
            showToast('Erreur serveur: ' + error.message, 'error');
        }
    });

    // ===== Suppression d'utilisateur =====
    function attachDeleteListener(btn) {
        btn.addEventListener('click', async function () {
            const code = this.dataset.code;
            const name = this.dataset.name;
            if (!confirm(`Supprimer « ${name} » ?`)) return;
            try {
                const response = await fetch(`/Web/utilisateurs/${code}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value
                    }
                });
                const result = await response.json();
                if (response.ok) {
                    showToast(result.message, 'success');
                    const row = document.querySelector(`tr[data-id="${code}"]`);
                    if (row) { row.remove(); renderPage(); }
                } else {
                    showToast(result.message || 'Erreur lors de la suppression', 'error');
                }
            } catch (error) {
                showToast('Erreur serveur: ' + error.message, 'error');
            }
        });
    }

    // ===== Helper : génère le HTML intérieur d'une ligne =====
    function rowHtml(u) {
        const badge = u.role_user === 'admin' ? 'bg-danger' : 'bg-info';
        const role  = u.role_user.charAt(0).toUpperCase() + u.role_user.slice(1);
        return `
            <td>${u.code_user}</td>
            <td>${u.prenom_user}</td>
            <td>${u.nom_user}</td>
            <td>${u.login_user}</td>
            <td><span class="badge ${badge}">${role}</span></td>
            <td>${u.tel_user || '—'}</td>
            <td class="text-center">
                <button type="button"
                        class="btn btn-warning btn-sm me-1 btn-edit"
                        data-bs-toggle="modal"
                        data-bs-target="#editModal"
                        data-code="${u.code_user}"
                        data-prenom="${u.prenom_user}"
                        data-nom="${u.nom_user}"
                        data-login="${u.login_user}"
                        data-role="${u.role_user}"
                        data-tel="${u.tel_user || ''}"
                        data-sexe="${u.sexe_user || ''}">
                    Modifier
                </button>
                <button type="button"
                        class="btn btn-danger btn-sm btn-delete"
                        data-code="${u.code_user}"
                        data-name="${u.prenom_user} ${u.nom_user}">
                    Supprimer
                </button>
            </td>
        `;
    }

    // ===== Aperçu du prochain matricule =====
    async function fetchNextCode() {
        const btn   = document.getElementById('refreshCode');
        const field = document.getElementById('code_user_display');
        btn.disabled = true;
        field.value  = '…';
        try {
            const res  = await fetch('{{ route("web.utilisateurs.next-code") }}', {
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();
            field.value = data.code;
        } catch {
            field.value = 'Erreur';
        } finally {
            btn.disabled = false;
        }
    }

    document.getElementById('refreshCode').addEventListener('click', fetchNextCode);

    // ===== Initialisation =====
    document.querySelectorAll('.btn-delete').forEach(btn => attachDeleteListener(btn));
    fetchNextCode();
    renderPage();
</script>
@endsection
