
{{-- Shared AJAX Delete Confirmation Modal --}}

<dialog
    id="ajaxDeleteDialog"
    class="ajax-delete-dialog"
    aria-labelledby="ajaxDeleteTitle"
    aria-describedby="ajaxDeleteText">

    <div class="ajax-delete-header">

        <h5 id="ajaxDeleteTitle" class="mb-0">
            Confirm deletion
        </h5>

        <button
            type="button"
            id="ajaxDeleteClose"
            class="btn-close"
            aria-label="Close">
        </button>

    </div>

    <div class="ajax-delete-body">

        <p id="ajaxDeleteText" class="mb-3">
            Are you sure you want to delete this record?
            This action cannot be undone.
        </p>

        <div
            id="ajaxDeleteError"
            class="alert alert-danger d-none"
            role="alert">
        </div>

    </div>

    <div class="ajax-delete-footer">

        <button
            type="button"
            id="ajaxDeleteCancel"
            class="btn btn-secondary">
            Cancel
        </button>

        <button
            type="button"
            id="ajaxDeleteConfirm"
            class="btn btn-danger">
            Yes, Delete
        </button>

    </div>

</dialog>


<div
    id="ajaxDeleteNotice"
    class="alert alert-success mt-3 d-none"
    role="status">
</div>


{{-- ================= CSS ================= --}}

@push('styles')

<style>

.ajax-delete-dialog {
    width: min(92vw, 470px);
    max-width: 470px;
    border: 0;
    border-radius: 12px;
    padding: 0;
    box-shadow: 0 22px 60px rgba(0,0,0,.28);
}

.ajax-delete-dialog::backdrop {
    background: rgba(15,23,42,.58);
}

.ajax-delete-header,
.ajax-delete-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 16px 20px;
}

.ajax-delete-header {
    border-bottom: 1px solid #e5e7eb;
}

.ajax-delete-body {
    padding: 18px 20px;
}

.ajax-delete-footer {
    border-top: 1px solid #e5e7eb;
    justify-content: flex-end;
    flex-wrap: wrap;
}

@media (max-width: 576px) {

    .ajax-delete-header,
    .ajax-delete-footer {
        padding: 14px;
    }

    .ajax-delete-body {
        padding: 16px 14px;
    }

}

</style>

@endpush


{{-- ================= JAVASCRIPT ================= --}}

@push('scripts')

<script>

(() => {

    const dialog = document.getElementById('ajaxDeleteDialog');

    if (!dialog) return;

    const errorBox = document.getElementById('ajaxDeleteError');

    const notice = document.getElementById('ajaxDeleteNotice');

    const confirmBtn = document.getElementById('ajaxDeleteConfirm');

    const closeBtn = document.getElementById('ajaxDeleteClose');

    const cancelBtn = document.getElementById('ajaxDeleteCancel');

    let targetForm = null;

    let busy = false;


    // ================= ERROR MESSAGE =================

    function showError(message) {

        errorBox.textContent = message;

        errorBox.classList.remove('d-none');

    }


    function clearError() {

        errorBox.textContent = '';

        errorBox.classList.add('d-none');

    }


    // ================= CLOSE MODAL =================

    function closeDialog() {

        if (busy) return;

        dialog.close();

        targetForm = null;

        clearError();

    }


    closeBtn.addEventListener('click', closeDialog);

    cancelBtn.addEventListener('click', closeDialog);


    dialog.addEventListener('click', event => {

        if (event.target === dialog) {

            closeDialog();

        }

    });


    dialog.addEventListener('cancel', event => {

        if (busy) {

            event.preventDefault();

        }

    });


    // ================= OPEN DELETE MODAL =================

    document.addEventListener('submit', event => {

        const form = event.target.closest('form.js-ajax-delete');

        if (!form) return;

        event.preventDefault();

        if (busy) return;

        targetForm = form;

        clearError();

        notice.classList.add('d-none');

        dialog.showModal();

    });


    // ================= CONFIRM DELETE =================

    confirmBtn.addEventListener('click', async () => {

        if (!targetForm || busy) return;

        busy = true;

        confirmBtn.disabled = true;

        confirmBtn.textContent = 'Deleting...';

        clearError();


        try {

            const response = await fetch(targetForm.action, {

                method: 'POST',

                credentials: 'same-origin',

                headers: {

                    'Accept': 'application/json',

                    'X-Requested-With': 'XMLHttpRequest'

                },

                body: new FormData(targetForm)

            });


            const result = await response.json().catch(() => ({}));


            if (!response.ok) {

                throw new Error(
                    result.message ||
                    `Delete failed (HTTP ${response.status}).`
                );

            }


            // ================= REMOVE TABLE ROW =================

            const row = targetForm.closest('tr');

            const tbody = row?.parentElement;

            const table = tbody?.closest('table');

            const columnCount = table
                ?.querySelectorAll('thead th').length || 1;


            row?.remove();


            // ================= EMPTY TABLE MESSAGE =================

            if (tbody && !tbody.querySelector('tr')) {

                const emptyRow = document.createElement('tr');

                const cell = document.createElement('td');

                cell.colSpan = columnCount;

                cell.className = 'text-center text-muted py-4';

                cell.textContent = 'No records found.';

                emptyRow.appendChild(cell);

                tbody.appendChild(emptyRow);

            }


            // ================= SUCCESS =================

            dialog.close();

            notice.textContent = result.message ||
                'Deleted successfully.';

            notice.classList.remove('d-none');

            targetForm = null;


        } catch (error) {

            showError(error.message);

        } finally {

            busy = false;

            confirmBtn.disabled = false;

            confirmBtn.textContent = 'Yes, Delete';

        }

    });

})();

</script>

@endpush
