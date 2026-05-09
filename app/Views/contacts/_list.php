<section class="contacts-grid" id="contactsGrid">
    <?php if(empty($contacts)): ?>
        <div class="contacts-empty" id="contactsEmpty">
            <div class="empty-icon" aria-hidden="true"><?= empty($search) ? '+' : '?' ?></div>
            <h2><?= empty($search) ? 'No contacts yet' : 'No matching contacts' ?></h2>
            <p><?= empty($search) ? 'Add your first contact and build a cleaner phone book.' : 'Try searching a different name, phone number, or email.' ?></p>
            <?php if(empty($search)): ?>
                <button class="btn contact-primary-btn" data-bs-toggle="modal" data-bs-target="#addContactModal">Add Contact</button>
            <?php else: ?>
                <a class="btn contact-primary-btn" href="/contacts" data-ajax-link>Clear Search</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <?php foreach($contacts as $contact): ?>
            <?php
                $email = $contact['email'] ?? '';
                $imgSrc = ($contact['image_path'] == 'default.png')
                    ? 'https://ui-avatars.com/api/?name='.urlencode($contact['name']).'&background=17384f&color=fff&bold=true'
                    : base_url('uploads/'.$contact['image_path']);
                $searchText = strtolower(trim($contact['name'].' '.$contact['phone'].' '.$email));
            ?>
            <article class="contact-card" id="contact-card-<?= $contact['id'] ?>" data-search="<?= esc($searchText, 'attr') ?>">
                <div class="contact-card-top">
                    <img src="<?= esc($imgSrc) ?>" alt="<?= esc($contact['name']) ?> profile image" class="contact-avatar">
                    <div class="contact-card-title">
                        <h2><?= esc($contact['name']) ?></h2>
                        <span><?= $email ? esc($email) : 'No email saved' ?></span>
                    </div>
                </div>

                <dl class="contact-details">
                    <div>
                        <dt>Phone</dt>
                        <dd><a href="tel:<?= esc($contact['phone'], 'attr') ?>"><?= esc($contact['phone']) ?></a></dd>
                    </div>
                    <div>
                        <dt>Email</dt>
                        <dd>
                            <?php if($email): ?>
                                <a href="mailto:<?= esc($email, 'attr') ?>"><?= esc($email) ?></a>
                            <?php else: ?>
                                <span>N/A</span>
                            <?php endif; ?>
                        </dd>
                    </div>
                </dl>

                <div class="contact-card-actions">
                    <button class="contact-action-btn edit" type="button" onclick="openEditModal(<?= $contact['id'] ?>)">Edit</button>
                    <button class="contact-action-btn delete" type="button" onclick="deleteContact(<?= $contact['id'] ?>)">Delete</button>
                </div>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</section>

<div class="contacts-pagination">
    <?= !empty($search) ? $pager->only(['search'])->links() : $pager->links() ?>
</div>
