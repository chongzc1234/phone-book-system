<?php

namespace App\Interfaces;

interface ContactRepositoryInterface
{
    public function getPaginatedContactsByUser(int $userId, int $perPage = 5, string $search = '');
    public function getPager();
    public function saveContact(array $data);
    public function getContactByIdSecurely(int $contactId, int $userId);
    public function updateContact(int $id, array $data);
    public function deleteContactSecurely(int $contactId, int $userId);
}
