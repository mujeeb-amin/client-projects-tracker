
<div class="mb-3">

    <h5 class="mb-3">Edit Name & Photo</h5>

    <form id="profileEditForm">

        <div id="profileEditNotice"
             class="alert d-none"
             role="status"
             aria-live="polite"></div>

        <div class="mb-3">
            <label for="editOwnName" class="form-label">
                Name
            </label>

            <input id="editOwnName"
                   name="name"
                   class="form-control"
                   value="{{ auth()->user()->name }}"
                   required
                   maxlength="255">
        </div>

        <div class="mb-3">
            <label for="editOwnAvatar" class="form-label">
                Profile Photo
            </label>

            <input id="editOwnAvatar"
                   name="avatar"
                   class="form-control"
                   type="file"
                   accept="image/jpeg,image/png,image/webp">
        </div>

        <div class="d-flex gap-2">

            <button type="submit"
                    id="saveOwnProfile"
                    class="btn btn-primary flex-fill">
                Save Profile
            </button>

            <button type="button"
                    id="cancelProfileEdit"
                    class="btn btn-light">
                Reset
            </button>

        </div>

    </form>
</div>

@push('scripts')
<script>
(function () {

    const form = document.getElementById('profileEditForm');

    if (!form) return;

    const cancel = document.getElementById('cancelProfileEdit');
    const notice = document.getElementById('profileEditNotice');
    const save = document.getElementById('saveOwnProfile');

    cancel.addEventListener('click', function () {
        form.reset();
        notice.className = 'alert d-none';
        notice.textContent = '';
    });

    form.addEventListener('submit', async function (event) {

        event.preventDefault();

        save.disabled = true;
        save.textContent = 'Saving...';

        notice.className = 'alert d-none';
        notice.textContent = '';

        const payload = new FormData(form);
        payload.append('_method', 'PUT');

        try {

            const response = await fetch(
                @json(route('account.details.update')),
                {
                    method: 'POST',
                    credentials: 'same-origin',

                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .content
                    },

                    body: payload
                }
            );

            const data = await response.json().catch(() => ({}));

            if (!response.ok) {

                throw new Error(
                    data.errors
                        ? Object.values(data.errors).flat().join('\n')
                        : (data.message || 'Profile update failed')
                );

            }

            if (data.user) {

                document.querySelectorAll('.header-user-name')
                    .forEach(function (element) {
                        element.textContent = data.user.name;
                    });

                const profileName = document.getElementById('profileName');

                if (profileName) {
                    profileName.textContent = data.user.name;
                }

                const editName = document.getElementById('editOwnName');

                if (editName) {
                    editName.value = data.user.name;
                }

                if (data.user.avatar_url) {

                    document.querySelectorAll('.header-user-icon')
                        .forEach(function (element) {

                            const image = document.createElement('img');

                            image.src = data.user.avatar_url;
                            image.alt = '';
                            image.className = 'profile-header-image';

                            element.replaceChildren(image);

                        });

                }

            }

            notice.textContent = 'Profile updated successfully.';
            notice.className = 'alert alert-success';

        } catch (error) {

            notice.textContent = error.message;
            notice.className = 'alert alert-danger';

        } finally {

            save.disabled = false;
            save.textContent = 'Save Profile';

        }

    });

})();
</script>
@endpush
