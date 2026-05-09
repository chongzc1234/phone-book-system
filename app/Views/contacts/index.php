<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row mt-4">
    <div class="col-12 d-flex justify-content-between align-items-center mb-4">
        <h2>My Phone Book</h2>
        <div>
            <span class="me-3">Welcome, <?= session()->get('username') ?>!</span>
            <!-- Button to trigger the add contact modal -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addContactModal">+ Add Contact</button>
            <a href="/logout" class="btn btn-outline-danger">Logout</a>
        </div>
    </div>
</div>

<!-- 闪存提示 -->
<?php if(session()->getFlashdata('success')): ?>
    <div id="flash-success" class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<!-- Contacts list (card-style responsive layout) -->
<div class="row">
    <?php if(empty($contacts)): ?>
        <div class="col-12 text-center text-muted mt-5">
            <p>No contacts found. Start adding some!</p>
        </div>
    <?php else: ?>
        <?php foreach($contacts as $contact): ?>
            <div class="col-md-6 col-lg-4 mb-4" id="contact-card-<?= $contact['id'] ?>">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <!-- Display image, use placeholder if none -->
                        <?php $imgSrc = ($contact['image_path'] == 'default.png') ? 'https://ui-avatars.com/api/?name='.urlencode($contact['name']).'&background=random' : base_url('uploads/'.$contact['image_path']); ?>
                        <img src="<?= $imgSrc ?>" class="rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #eee;">
                        
                        <h5 class="card-title"><?= esc($contact['name']) ?></h5>
                        <p class="card-text text-muted mb-1">📞 <?= esc($contact['phone']) ?></p>
                        <p class="card-text text-muted">✉️ <?= esc($contact['email']) ?? 'N/A' ?></p>
                        
                        <div class="d-flex gap-2 mt-2">
                            <button class="btn btn-sm btn-outline-primary w-100" onclick="openEditModal(<?= $contact['id'] ?>)">Edit</button>
                            <button class="btn btn-sm btn-outline-danger w-100" onclick="deleteContact(<?= $contact['id'] ?>)">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Pagination (Requirement A6) -->
<div class="d-flex justify-content-center mt-4">
    <?= $pager->links() ?>
</div>

<!-- Bootstrap modal for adding a contact -->
<div class="modal fade" id="addContactModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New Contact</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="/contacts/store" method="POST" enctype="multipart/form-data">
          <div class="modal-body">
              <?= csrf_field() ?>
              <div class="mb-3">
                  <label>Name <span class="text-danger">*</span></label>
                  <input type="text" name="name" class="form-control" required>
              </div>
              <div class="mb-3">
                  <label>Phone <span class="text-danger">*</span></label>
                  <input type="text" name="phone" class="form-control" required>
              </div>
              <div class="mb-3">
                  <label>Email</label>
                  <input type="email" name="email" class="form-control">
              </div>
              <div class="mb-3">
                  <label>Profile Image (Optional)</label>
                  <input type="file" name="image" class="form-control" accept="image/png, image/jpeg">
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save Contact</button>
          </div>
      </form>
    </div>
  </div>
</div>

<!-- 编辑联系人的 Bootstrap Modal -->
<div class="modal fade" id="editContactModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Contact</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <!-- form action 将由 JS 动态修改 -->
      <form id="editForm" method="POST" enctype="multipart/form-data">
          <div class="modal-body">
              <?= csrf_field() ?>
              <input type="hidden" name="remove_image" id="edit_remove_image" value="0">
              <div class="mb-3 text-center">
                  <img id="editProfilePreview" src="https://ui-avatars.com/api/?name=Preview&background=random" class="rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #eee;">
                  <button type="button" class="btn btn-sm btn-outline-danger" id="removeProfilePictureBtn" onclick="markRemoveImage()">Remove Profile Picture</button>
              </div>
              <div class="mb-3">
                  <label>Name <span class="text-danger">*</span></label>
                  <input type="text" name="name" id="edit_name" class="form-control" required>
              </div>
              <div class="mb-3">
                  <label>Phone <span class="text-danger">*</span></label>
                  <input type="text" name="phone" id="edit_phone" class="form-control" required>
              </div>
              <div class="mb-3">
                  <label>Email</label>
                  <input type="email" name="email" id="edit_email" class="form-control">
              </div>
              <div class="mb-3">
                  <label>Update Profile Image (Optional)</label>
                  <input type="file" name="image" class="form-control" accept="image/png, image/jpeg">
                  <small class="text-muted">Leave empty to keep current image.</small>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Update Changes</button>
          </div>
      </form>
    </div>
  </div>
</div>

<!-- Ajax-->
<script>
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
            ? `https://ui-avatars.com/api/?name=${encodeURIComponent(data.name)}&background=random`
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
    document.getElementById('editProfilePreview').src = 'https://ui-avatars.com/api/?name=No+Image&background=random';
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
                    document.getElementById(`contact-card-${id}`).remove();
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