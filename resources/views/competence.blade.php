@extends('template')

@section('main')
<main class="flex-grow-1 container mt-4">

    <h1 class="mb-4">Liste des Compétences</h1>

    {{-- Formulaire d'ajout --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white fw-semibold">
            Ajouter une compétence
        </div>
        <div class="card-body">
            <form action="{{ route('web.competences.store') }}" method="POST">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="label_comp" class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('label_comp') is-invalid @enderror"
                               id="label_comp"
                               name="label_comp"
                               value="{{ old('label_comp') }}"
                               placeholder="Ex : PHP, Docker…"
                               required>
                        @error('label_comp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="description_comp" class="form-label">Description</label>
                        <input type="text"
                               class="form-control @error('description_comp') is-invalid @enderror"
                               id="description_comp"
                               name="description_comp"
                               value="{{ old('description_comp') }}"
                               placeholder="Description courte (optionnel)">
                        @error('description_comp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-dark w-100">Ajouter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tableau des compétences --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th style="width:60px">#</th>
                        <th>Nom de la compétence</th>
                        <th>Description</th>
                        <th style="width:180px" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($competences_list as $competence)
                        <tr>
                            <td>{{ $competence->code_comp }}</td>
                            <td>{{ $competence->label_comp }}</td>
                            <td>{{ $competence->description_comp ?? '—' }}</td>
                            <td class="text-center">

                                {{-- Bouton Modifier --}}
                                <button type="button"
                                        class="btn btn-warning btn-sm me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal"
                                        data-code="{{ $competence->code_comp }}"
                                        data-label="{{ $competence->label_comp }}"
                                        data-description="{{ $competence->description_comp }}">
                                    Modifier
                                </button>

                                {{-- Bouton Supprimer --}}
                                <form action="{{ route('web.competences.destroy', $competence->code_comp) }}"
                                      method="POST"
                                      class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            class="btn btn-danger btn-sm btn-delete"
                                            data-name="{{ $competence->label_comp }}">
                                        Supprimer
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Aucune compétence enregistrée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if ($competences_list->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3">
            <small class="text-muted">
                Affichage de {{ $competences_list->firstItem() }} à {{ $competences_list->lastItem() }}
                sur {{ $competences_list->total() }} compétence(s)
            </small>
            {{ $competences_list->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    @endif

</main>

{{-- Modal de modification --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="editModalLabel">Modifier la compétence</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_label_comp" class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control"
                               id="edit_label_comp"
                               name="label_comp"
                               required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description_comp" class="form-label">Description</label>
                        <textarea class="form-control"
                                  id="edit_description_comp"
                                  name="description_comp"
                                  rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Peuplement du modal Modifier
    document.getElementById('editModal').addEventListener('show.bs.modal', function (event) {
        const btn         = event.relatedTarget;
        const code        = btn.getAttribute('data-code');
        const label       = btn.getAttribute('data-label');
        const description = btn.getAttribute('data-description') || '';

        document.getElementById('editForm').action             = '/Web/competences/' + code;
        document.getElementById('edit_label_comp').value       = label;
        document.getElementById('edit_description_comp').value = description;
    });

    // Confirmation SweetAlert2 avant suppression
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function () {
            const name = this.getAttribute('data-name');
            const form = this.closest('form');

            Swal.fire({
                title: 'Confirmer la suppression',
                text: `Supprimer « ${name} » ? Cette action est irréversible.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
