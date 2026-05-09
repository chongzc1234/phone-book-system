<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<link href="/assets/css/contacts.css" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main class="contacts-page" x-data="contactsBrowser()" @click="handleAjaxLink($event)">
    <section class="contacts-hero">
        <div>
            <p class="contacts-kicker">Personal directory</p>
            <h1>My Phone Book</h1>
            <p class="contacts-welcome">Welcome, <?= esc(session()->get('username')) ?>. Keep every important number within reach.</p>
        </div>
        <div class="contacts-actions">
            <button class="btn contact-primary-btn" data-bs-toggle="modal" data-bs-target="#addContactModal">
                <span aria-hidden="true">+</span>
                Add Contact
            </button>
            <a href="/logout" class="btn contact-ghost-btn">Logout</a>
        </div>
    </section>

    <section class="contacts-toolbar" aria-label="Contact tools">
        <form class="contacts-search" method="GET" action="/contacts" data-contact-search-form @submit.prevent="submitSearch($event)">
            <span aria-hidden="true"></span>
            <input type="search" id="contactSearch" name="search" value="<?= esc($search ?? '') ?>" placeholder="Search name, phone, or email" autocomplete="off" x-model="query" @input.debounce.450ms="$event.target.form.requestSubmit()">
            <button type="submit">Search</button>
            <a href="/contacts" aria-label="Clear search" data-ajax-link x-show="query.length > 0" @click="query = ''" style="display: none;">Clear</a>
        </form>
    </section>

    <?php if(session()->getFlashdata('success')): ?>
        <div id="flash-success" class="contact-alert contact-alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="contact-alert contact-alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="contacts-results" x-ref="results" :class="{ 'is-loading': loading }" aria-live="polite">
        <?= view('contacts/_list', [
            'contacts' => $contacts,
            'pager'    => $pager,
            'search'   => $search ?? '',
        ]) ?>
    </div>
</main>

<!-- Bootstrap modal for adding a contact -->
<div class="modal fade contact-modal" id="addContactModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div>
            <p class="modal-kicker">Create contact</p>
            <h5 class="modal-title">New Contact</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="/contacts/store" method="POST" enctype="multipart/form-data">
          <div class="modal-body">
              <?= csrf_field() ?>
              <div class="contact-form-grid">
              <div class="contact-form-field">
                  <label for="add_name">Name <span>*</span></label>
                  <input type="text" name="name" id="add_name" class="form-control" required>
              </div>
              <div class="contact-form-field">
                  <label for="add_phone">Phone <span>*</span></label>
                  <input type="text" name="phone" id="add_phone" class="form-control" required>
              </div>
              <div class="contact-form-field">
                  <label for="add_email">Email</label>
                  <input type="email" name="email" id="add_email" class="form-control">
              </div>
              <div class="contact-form-field">
                  <label for="add_image">Profile Image</label>
                  <input type="file" name="image" id="add_image" class="form-control" accept="image/png, image/jpeg">
              </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn contact-ghost-btn" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn contact-primary-btn">Save Contact</button>
          </div>
      </form>
    </div>
  </div>
</div>

<!-- 编辑联系人的 Bootstrap Modal -->
<div class="modal fade contact-modal" id="editContactModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div>
            <p class="modal-kicker">Update record</p>
            <h5 class="modal-title">Edit Contact</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <!-- form action 将由 JS 动态修改 -->
      <form id="editForm" method="POST" enctype="multipart/form-data">
          <div class="modal-body">
              <?= csrf_field() ?>
              <input type="hidden" name="remove_image" id="edit_remove_image" value="0">
              <div class="edit-preview">
                  <img id="editProfilePreview" src="https://ui-avatars.com/api/?name=Preview&background=17384f&color=fff&bold=true" alt="Profile preview">
                  <button type="button" class="contact-action-btn delete" id="removeProfilePictureBtn" onclick="markRemoveImage()">Remove Profile Picture</button>
              </div>
              <div class="contact-form-grid">
              <div class="contact-form-field">
                  <label for="edit_name">Name <span>*</span></label>
                  <input type="text" name="name" id="edit_name" class="form-control" required>
              </div>
              <div class="contact-form-field">
                  <label for="edit_phone">Phone <span>*</span></label>
                  <input type="text" name="phone" id="edit_phone" class="form-control" required>
              </div>
              <div class="contact-form-field">
                  <label for="edit_email">Email</label>
                  <input type="email" name="email" id="edit_email" class="form-control">
              </div>
              <div class="contact-form-field">
                  <label for="edit_image">Update Profile Image</label>
                  <input type="file" name="image" id="edit_image" class="form-control" accept="image/png, image/jpeg">
                  <small>Leave empty to keep current image.</small>
              </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn contact-ghost-btn" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn contact-primary-btn">Update Changes</button>
          </div>
      </form>
    </div>
  </div>
</div>

<!-- Ajax-->
<script>
function contactsBrowser() {
    return {
        loading: false,
        query: new URLSearchParams(window.location.search).get('search') || '',
        async load(url) {
            this.loading = true;

            try {
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Request failed');
                }

                const data = await response.json();
                const results = this.$refs.results;
                results.innerHTML = data.html;
                results.classList.remove('is-entering');
                void results.offsetWidth;
                results.classList.add('is-entering');
                setTimeout(() => results.classList.remove('is-entering'), 420);
                window.history.pushState({}, '', url);
            } catch (error) {
                Swal.fire('Error!', 'Could not load contacts.', 'error');
            } finally {
                this.loading = false;
            }
        },
        handleAjaxLink(event) {
            const link = event.target.closest('[data-ajax-link], .contacts-pagination a');

            if (!link || link.closest('.disabled') || link.closest('.active')) {
                return;
            }

            event.preventDefault();
            this.load(link.href);
        },
        submitSearch(event) {
            const form = event.target;
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            const query = params.toString();
            const url = query ? `${form.action}?${query}` : form.action;

            this.query = formData.get('search') || '';
            this.load(url);
        }
    };
}

function openEditModal(id) {
    // 1. 发送 Fetch 请求获取该联系人的数据
    fetch(`/contacts/edit/${id}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        // 2. Fill the edit modal inputs with contact data
        document.getElementById('edit_name').value = data.name;
        document.getElementById('edit_phone').value = data.phone;
        document.getElementById('edit_email').value = data.email ?? '';
        document.getElementById('edit_remove_image').value = '0';
        document.getElementById('removeProfilePictureBtn').innerText = 'Remove Profile Picture';
        document.getElementById('removeProfilePictureBtn').disabled = data.image_path === 'default.png';

        const previewSrc = data.image_path === 'default.png'
            ? `https://ui-avatars.com/api/?name=${encodeURIComponent(data.name)}&background=17384f&color=fff&bold=true`
            : `<?= base_url('uploads/') ?>${data.image_path}`;

        document.getElementById('editProfilePreview').src = previewSrc;

        // 3. Update the form action dynamically
        document.getElementById('editForm').action = `/contacts/update/${data.id}`;
        
        // 4. Show the edit modal using Bootstrap JS API
        var editModal = new bootstrap.Modal(document.getElementById('editContactModal'));
        editModal.show();
    })
    .catch(error => {
        Swal.fire('Error!', 'Could not fetch contact data.', 'error');
    });
}

function markRemoveImage() {
    document.getElementById('edit_remove_image').value = '1';
    document.getElementById('editProfilePreview').src = 'https://ui-avatars.com/api/?name=No+Image&background=17384f&color=fff&bold=true';
    document.getElementById('removeProfilePictureBtn').innerText = 'Profile Picture Will Be Removed';
}

function deleteContact(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Get CSRF token from the header
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Send Ajax fetch request (Requirement B2)
            fetch(`/contacts/delete/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    // remove card DOM，without refresh page
                    const card = document.getElementById(`contact-card-${id}`);
                    if (card) {
                        card.classList.add('is-removing');
                        setTimeout(() => {
                            card.remove();
                        }, 220);
                    }
                    Swal.fire('Deleted!', 'Contact has been removed.', 'success');
                } else {
                    Swal.fire('Error!', 'Failed to delete contact.', 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error!', 'Server error occurred.', 'error');
            });
        }
    })
}

(function () {
    const flashSuccess = document.getElementById('flash-success');

    if (flashSuccess) {
        setTimeout(() => {
            flashSuccess.style.transition = 'opacity 0.5s ease';
            flashSuccess.style.opacity = '0';
            setTimeout(() => flashSuccess.remove(), 500);
        }, 3000);
    }
})();
</script>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<?= $this->endSection() ?>
