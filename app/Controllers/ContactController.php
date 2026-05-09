<?php

namespace App\Controllers;

use App\Interfaces\ContactRepositoryInterface;
use App\Repositories\ContactRepository;
use CodeIgniter\API\ResponseTrait;

class ContactController extends BaseController
{
    use ResponseTrait; 

    protected $contactRepo;

    public function __construct()
    {
        $this->contactRepo = new ContactRepository();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $search = trim((string) $this->request->getGet('search'));

        $data = [
            'contacts' => $this->contactRepo->getPaginatedContactsByUser($userId, 6, $search),
            'pager'    => $this->contactRepo->getPager(),
            'search'   => $search,
        ];

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'html' => view('contacts/_list', $data),
            ]);
        }
        
        return view('contacts/index', $data);
    }

    public function store()
    {
        // Strict form validation, including image extension and size validation (max 2MB)
        $rules = [
            'name'  => 'required|min_length[2]',
            'phone' => 'required|min_length[8]',
            'image' => 'is_image[image]|ext_in[image,png,jpg,jpeg]|max_size[image,2048]'
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->to('/contacts');
        }

        $imagePath = 'default.png';
        $file = $this->request->getFile('image');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            
            // Automatic image compression and cropping to 300x300, saving server space
            \Config\Services::image()
                ->withFile($file)
                ->fit(300, 300, 'center')
                ->save(FCPATH . 'uploads/' . $newName, 80); // 80 is the image quality

            $imagePath = $newName;
        }

        // Save to database
        $this->contactRepo->saveContact([
            'user_id'    => session()->get('user_id'),
            'name'       => $this->request->getPost('name'),
            'phone'      => $this->request->getPost('phone'),
            'email'      => $this->request->getPost('email'),
            'image_path' => $imagePath
        ]);

        session()->setFlashdata('success', 'Contact added successfully!');
        return redirect()->to('/contacts');
    }

    public function edit($id)
    {
        $userId = session()->get('user_id');
        $contact = $this->contactRepo->getContactByIdSecurely($id, $userId);

        if ($contact) {
            return $this->respond($contact);
        }
        return $this->failNotFound('Contact not found.');
    }

    public function update($id)
    {
        $userId = session()->get('user_id');
        $contact = $this->contactRepo->getContactByIdSecurely($id, $userId);

        if (!$contact) {
            session()->setFlashdata('error', 'Unauthorized access.');
            return redirect()->to('/contacts');
        }

        // validation rules, including image validation
        $rules = [
            'name'  => 'required|min_length[2]',
            'phone' => 'required|min_length[8]',
            'image' => 'is_image[image]|ext_in[image,png,jpg,jpeg]|max_size[image,2048]'
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->to('/contacts');
        }

        $updateData = [
            'name'  => $this->request->getPost('name'),
            'phone' => $this->request->getPost('phone'),
            'email' => $this->request->getPost('email'),
        ];

        $removeImage = $this->request->getPost('remove_image') === '1';
        if ($removeImage) {
            if ($contact['image_path'] !== 'default.png' && file_exists(FCPATH . 'uploads/' . $contact['image_path'])) {
                unlink(FCPATH . 'uploads/' . $contact['image_path']);
            }
            $updateData['image_path'] = 'default.png';
        }

        // upload processing
        $file = $this->request->getFile('image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            
            \Config\Services::image()
                ->withFile($file)
                ->fit(300, 300, 'center')
                ->save(FCPATH . 'uploads/' . $newName, 80);

            $updateData['image_path'] = $newName;

            // if user uploaded new image, automatically delete the old image (except for default.png)
            if ($contact['image_path'] !== 'default.png' && file_exists(FCPATH . 'uploads/' . $contact['image_path'])) {
                unlink(FCPATH . 'uploads/' . $contact['image_path']);
            }
        }

        $this->contactRepo->updateContact($id, $updateData);

        session()->setFlashdata('success', 'Contact updated successfully!');
        return redirect()->to('/contacts');
    }

    public function delete($id)
    {
        $userId = session()->get('user_id');
        $imageName = $this->contactRepo->deleteContactSecurely($id, $userId);

        if ($imageName) {
            // If not the default image, delete the physical file
            if ($imageName !== 'default.png' && file_exists(FCPATH . 'uploads/' . $imageName)) {
                unlink(FCPATH . 'uploads/' . $imageName);
            }
            return $this->respondDeleted(['status' => 'success', 'message' => 'Contact deleted.']);
        }

        return $this->failNotFound('Contact not found or access denied.');
    }
}
