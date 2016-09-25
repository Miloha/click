<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('login_model');
		$this->load->library(array('session','form_validation'));
		$this->load->helper(array('url','form'));
		$this->load->database('default');
		// Include the facebook api php libraries
        $this->load->library('facebook', array('appId' => '559317274253947', 'secret' => '45cf5daedbd0e9528db8785a7ad3b07a'));
        $this->user = $this->facebook->getUser();
        
	}

	public function index()
	{

		
		switch ($this->session->userdata('perfil')) 
		{
			case '':

				

				$data['login_url'] = $this->facebook->getLoginUrl(array('redirect_uri'=>base_url() . 'login/new_user','scope'=>'email'));
				$data['token'] = $this->token();
				$data['titulo'] = 'Login con roles de usuario en codeigniter';
				$this->load->view('templates/header',$data);
				$this->load->view('login_view',$data);
				$this->load->view('templates/footer',$data);
				break;
			case 'administrador':

				redirect(base_url().'listperfil');
				break;
			case 'editor' :
				redirect(base_url().'listperfil');
				//redirect(base_url().'editor');
				break;
			case 'suscriptor':
				redirect(base_url().'perfil/index/edit/' . $this->session->userdata('id_usuario'));
				break;
			default:
				redirect(base_url().'login');
				break;
		}
	}

	public function new_user()
	{
		if($this->input->post('token') && $this->input->post('token') == $this->session->userdata('token'))
		{
			$this->form_validation->set_rules('username', 'Email', 'required|valid_email|trim|min_length[6]|max_length[150]|xss_clean');
			$this->form_validation->set_rules('password', 'password', 'required|trim|min_length[5]|max_length[150]|xss_clean');

			//lanzamos mensajes de error si es que los hay

			if($this->form_validation->run() == FALSE)
			{
				$this->index();
			}else{
				$username = $this->input->post('username');
				$password = sha1($this->input->post('password'));
				$check_user = $this->login_model->login_user($username,$password);
				if($check_user == TRUE)
				{
					$data = array(
                    'is_logued_in'     =>         TRUE,
                    'id_usuario'     =>         $check_user->id,
                    'perfil'        =>        $check_user->perfil,
                    'username'         =>         $check_user->username,
					'id_area'         =>         $check_user->id_area,
					'nombre'         =>         $check_user->nombre
					);
					$this->session->set_userdata($data);
					$this->index();
				}
			}
		}elseif ($this->user) {
			$dataf['user_profile'] = $this->facebook->api('/me?fields=id,first_name,last_name,email,gender,locale,picture');
			
			$username = $dataf['user_profile']['email'];
			$check_user = $this->login_model->flogin_user($username);
				if($check_user == TRUE)
				{
					$data = array(
                    'is_logued_in'     =>         TRUE,
                    'id_usuario'     =>         $check_user->id,
                    'perfil'        =>        $check_user->perfil,
                    'username'         =>         $check_user->username,
					'id_area'         =>         $check_user->id_area,
					'nombre'         =>         $check_user->nombre
					);
					$this->session->set_userdata($data);
					$this->index();
				}else{

					$datan['nombre'] = $dataf['user_profile']['first_name'] . ' ' . $dataf['user_profile']['last_name'];
					$datan['username'] = $dataf['user_profile']['email'];
					$datan['password'] = sha1(rand());
					$datan['perfil'] = 'suscriptor';
					$datan['id_area'] = '1';
					$datan['id'] = $this->login_model->fuser($datan);
					$data = array(
                    'is_logued_in'     =>         TRUE,
                    'id_usuario'     =>         $datan['id'],
                    'perfil'        =>        $datan['perfil'],
                    'username'         =>         $datan['username'],
					'id_area'         =>         1,
					'nombre'         =>         $datan['nombre']
					);
					$this->session->set_userdata($data);
					$this->index();
				}
		}
		else{
			redirect(base_url().'login');
		}
	}

	public function logfacebook()
	{
		$this->user = $this->facebook->getUser();
		echo $this->user . 'mm';
		if ($this->user) {
			$data['user_profile'] = $this->facebook->api('/me/');
			
		}

		$this->load->view('templates/header',$data);
		$this->load->view('login_view',$data);
		$this->load->view('templates/footer',$data);

	}

	public function token()
	{
		$token = md5(uniqid(rand(),true));
		$this->session->set_userdata('token',$token);
		return $token;
	}

	public function logout_ci()
	{
		$this->session->sess_destroy();
		redirect(base_url().'login');
		//$this->index();
	}
}
