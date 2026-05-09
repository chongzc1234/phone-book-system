<?php

namespace App\Repositories;

use App\Models\ContactModel;
use Config\Services;

class ContactRepository
{
    protected ContactModel $contactModel;
    protected $pager;

    public function __construct()
    {
        $this->contactModel = new ContactModel();
    }

    public function getPaginatedContactsByUser(int $userId, int $perPage = 5)
    {
        $contacts = $this->contactModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage);

        $this->pager = $this->contactModel->pager;

        return $contacts;
    }

    public function getPager()
    {
        return $this->pager ?? Services::pager();
    }

    public function saveContact(array $data)
    {
        return $this->contactModel->save($data);
    }

    public function getContactByIdSecurely($contactId, $userId)
    {
        return $this->contactModel->where(['id' => $contactId, 'user_id' => $userId])->first();
    }

    public function updateContact($id, $data)
    {
        return $this->contactModel->update($id, $data);
    }

    public function deleteContactSecurely(int $id, int $userId)
    {
        $contact = $this->contactModel
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$contact) {
            return false;
        }

        $this->contactModel
            ->where('id', $id)
            ->where('user_id', $userId)
            ->delete();

        return $contact['image_path'] ?? null;
    }
}
