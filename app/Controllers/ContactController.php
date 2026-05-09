<?php

namespace App\Controllers;

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
        $data = [
            'contacts' => $this->contactRepo->getPaginatedContactsByUser($userId, 5),
            'pager'    => $this->contactRepo->getPager()
        ];
        
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