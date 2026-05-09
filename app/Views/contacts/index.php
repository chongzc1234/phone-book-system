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
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
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
                        
                        <button class="btn btn-sm btn-outline-danger w-100 mt-2" onclick="deleteContact(<?= $contact['id'] ?>)">Delete</button>
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

<!-- Ajax-->
<script>
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
</script>
<?= $this->endSection() ?>