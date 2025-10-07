<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contacts extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Contact_model');
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('form_validation', 'upload', 'session'));
    }

    public function index()
    {
        $data['contacts'] = $this->Contact_model->get_all();
        $data['success'] = $this->session->flashdata('success');
        $data['error'] = $this->session->flashdata('error');
        $this->load->view('contacts/index', $data);
    }

    public function create()
    {
        $data['error'] = '';
        $this->load->view('contacts/create', $data);
    }

    public function store()
    {
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('nomor_telepon', 'Nomor Telepon', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['error'] = validation_errors();
            $this->load->view('contacts/create', $data);
        } else {
            $data = array(
                'nama' => $this->input->post('nama'),
                'email' => $this->input->post('email'),
                'nomor_telepon' => $this->input->post('nomor_telepon'),
            );

            // Handle file upload
            if (!empty($_FILES['foto']['name'])) {
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_size'] = 2048; // 2MB
                $config['encrypt_name'] = TRUE;

                $this->upload->initialize($config);

                if ($this->upload->do_upload('foto')) {
                    $upload_data = $this->upload->data();
                    $data['foto'] = $upload_data['file_name'];
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('contacts/create');
                    return;
                }
            }

            if ($this->Contact_model->insert($data)) {
                $this->session->set_flashdata('success', 'Kontak berhasil ditambahkan!');
                redirect('contacts');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan kontak!');
                redirect('contacts/create');
            }
        }
    }

    public function edit($id)
    {
        $data['contact'] = $this->Contact_model->get_by_id($id);
        if (!$data['contact']) {
            show_404();
        }
        $data['error'] = '';
        $this->load->view('contacts/edit', $data);
    }

    public function update($id)
    {
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('nomor_telepon', 'Nomor Telepon', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['contact'] = $this->Contact_model->get_by_id($id);
            $data['error'] = validation_errors();
            $this->load->view('contacts/edit', $data);
        } else {
            $contact = $this->Contact_model->get_by_id($id);
            
            $data = array(
                'nama' => $this->input->post('nama'),
                'email' => $this->input->post('email'),
                'nomor_telepon' => $this->input->post('nomor_telepon'),
            );

            // Handle file upload
            if (!empty($_FILES['foto']['name'])) {
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_size'] = 2048; // 2MB
                $config['encrypt_name'] = TRUE;

                $this->upload->initialize($config);

                if ($this->upload->do_upload('foto')) {
                    // Delete old photo if exists
                    if ($contact->foto && file_exists('./uploads/' . $contact->foto)) {
                        unlink('./uploads/' . $contact->foto);
                    }
                    
                    $upload_data = $this->upload->data();
                    $data['foto'] = $upload_data['file_name'];
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('contacts/edit/' . $id);
                    return;
                }
            }

            if ($this->Contact_model->update($id, $data)) {
                $this->session->set_flashdata('success', 'Kontak berhasil diupdate!');
                redirect('contacts');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate kontak!');
                redirect('contacts/edit/' . $id);
            }
        }
    }

    public function delete($id)
    {
        $contact = $this->Contact_model->get_by_id($id);
        
        if ($contact) {
            // Delete photo if exists
            if ($contact->foto && file_exists('./uploads/' . $contact->foto)) {
                unlink('./uploads/' . $contact->foto);
            }
            
            if ($this->Contact_model->delete($id)) {
                $this->session->set_flashdata('success', 'Kontak berhasil dihapus!');
            } else {
                $this->session->set_flashdata('error', 'Gagal menghapus kontak!');
            }
        }
        
        redirect('contacts');
    }
}
