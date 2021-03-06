<?php

/**
 * Created by PhpStorm.
 * User: longmen
 * Date: 16/4/13
 * Time: 下午9:08
 */
class home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('home_model');
        $this->load->model('addbook_model');
    }

    public function homepage()
    {
        $data['content'] = '主页';
        $this->load->view('header', $data);
        $this->load->view('page');
        $this->load->view('footer');
    }

    public function search()
    {
        $page = $this->input->get('page');
        if ($page == null) $page = 1;
        $title = $this->input->get('title');
        $category = $this->input->get('category');
        $publisher = $this->input->get('publisher');
        $author = $this->input->get('author');
        $pubdate1 = $this->input->get('date1');
        $pubdate2 = $this->input->get('date2');
        $price1 = $this->input->get('price1');
        $price2 = $this->input->get('price2');
        $data['content'] = '图书查询';
        $data['author']  = $author;
        $data['category'] = $category;
        $data['title'] = $title;
        $data['publisher'] = $publisher;
        $data['pubdate1'] = $pubdate1;
        $data['pubdate2'] = $pubdate2;
        $data['price1'] = $price1;
        $data['price2'] = $price2;
        $this->load->view('header', $data);
        $this->load->view('search', $data);
        $data['book'] = $this->addbook_model->get($page);
        $data['page'] = $page;
        $data['num'] = $this->addbook_model->count();
        $this->load->view('books', $data);
        $this->load->view('footer');
    }

    public function search_result()
    {
        $page = $this->input->get('page');
        if ($page == null) $page = 1;
        $pubdate1 = $this->input->get('date1');
        $pubdate2 = $this->input->get('date2');
        $price1 = $this->input->get('price1');
        $price2 = $this->input->get('price2');
        $title = $this->input->get('title');
        $category = $this->input->get('category');
        $publisher = $this->input->get('publisher');
        $author = $this->input->get('author');
        $data['author']  = $author;
        $data['category'] = $category;
        $data['title'] = $title;
        $data['publisher'] = $publisher;
        $data['content'] = '图书查询';
        $data['pubdate1'] = $pubdate1;
        $data['pubdate2'] = $pubdate2;
        $data['price1'] = $price1;
        $data['price2'] = $price2;
        $this->load->view('header', $data);
        $this->load->view('search', $data);
        $data['book'] = $this->addbook_model->search($page);
        $data['page'] = $page;
        $data['num'] = $this->addbook_model->count_result();
        $this->load->view('books', $data);
        $this->load->view('footer');
    }

    public function login()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|callback_check_database');
        if ($this->form_validation->run() == FALSE) {
            $data['content'] = '登陆';
            $this->load->view('header', $data);
            $this->load->view('login');
            $this->load->view('footer');
        } else {
            $data['content'] = '主页';
            $this->load->view('header', $data);
            $this->load->view('footer');
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: /library/index.php/home/homepage'); // file name where you want to redirect after logout
        exit();
    }

    function check_database($password)
    {
        //Field validation succeeded.  Validate against database
        $username = $this->input->post('username');

        if ($result = $this->home_model->login($username, $password)) {
            $sess_array = array();
            foreach ($result as $row) {
                $sess_array = array(
                    'id' => $row->id,
                    'name' => $row->name
                );
                $this->session->set_userdata($sess_array);
            }
            return TRUE;
        } else {
            $this->form_validation->set_message('check_database', 'Invalid username or password');
            return false;
        }
    }
}