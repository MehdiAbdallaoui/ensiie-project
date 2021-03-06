<?php

/**
 * Created by PhpStorm.
 * User: standard
 * Date: 19/02/20
 * Time: 00:09
 */
class Categorie extends CI_Controller
{
	private $data=array();

	function __construct() {
		parent::__construct();

		if ($this->session->userdata('logged_in') == null && $this->uri->ruri_string() != 'authentificiation/login') {
			redirect('authentification/login');
		} elseif ($this->uri->ruri_string() != 'authentificiation/login') {
			$this->data+=array('id_user' => $this->session->userdata('logged_in')['id_user']);
			$this->data+=array('nom_user' => $this->session->userdata('logged_in')['nom']);
			$this->data+=array('prenom_user' => $this->session->userdata('logged_in')['prenom']);
			$this->data+=array('email_user' => $this->session->userdata('logged_in')['email']);
			$this->data+=array('tel_user' => $this->session->userdata('logged_in')['telephone']);
			$this->data+=array('promo' => $this->session->userdata('logged_in')['promo']);
			$this->data+=array('nb_signal_user' => $this->session->userdata('logged_in')['nb_signal_user']);
			$this->data+=array('admin_user' => $this->session->userdata('logged_in')['admin']);
		}
	}

    public function index(){
		$this->getAllCategories();
	}

    /**
	 * Fonction permettant d'ajouter une categorie par l'admin
     */

    public function ajouter_categorie()
	{
		if($this->data['admin_user']){
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			$this->form_validation->set_rules('categ', 'categ', 'required');

			if($this->input->post('categ')){
				if ($this->form_validation->run() == TRUE) {

					$this->categorie->insert($this->input->post('categ'));
					redirect('Categorie/getAllCategories');
					$this->session->set_flashdata('message', 'Catégorie ajoutée');

				}else{
					$this->session->set_flashdata('error', 'Catégorie non ajoutée, veuillez réessayer');
					$this->load->view('elements/header',$this->data);
					$this->load->view('gestion_categorie_view',$this->data);
					$this->load->view('elements/footer');
				}
			}
			else{
				$this->load->view('elements/header',$this->data);
				if(!$this->session->userdata('logged_in')['droit_publication'])
				{
					$this->load->view('error_page');
				} else
				{
					$this->load->view('gestion_categorie_view',$this->data);
				}
				$this->load->view('elements/footer');
			}
		}
		else{
			$this->session->set_flashdata('error', 'Fonction non autorisée');
			redirect('Annonce/');
		}
	}

    /**
	 * Fonction permettant de modifier une categorie
	 * 
	 * @param $id_categ id de la categorie à modifier
     */

	public function modifier_categorie($id_categ){

		if($this->data['admin_user']){

			$this->form_validation->set_error_delimiters('<p class="form_erreur">', '</p>');
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			$this->form_validation->set_rules('categ', 'categ', 'required');

			if($this->form_validation->run()){

				$value = $this->input->post('categ');
				$this->categorie->updateCategorie($id_categ, $value);
				redirect('Categorie/getAllCategories');
				$this->session->set_flashdata('message', 'Catégorie modifiée');
			}
			else{
				$categories=$this->categorie->getCategorie($id_categ);
				$this->data+=array("categories"=>$categories[0]);
				$this->load->view("elements/header",$this->data);
				$this->load->view('gestion_categorie_view',$this->data);
				$this->load->view("elements/footer");
			}
		}
		else{
			$this->session->set_flashdata('error', 'Fonction non autorisée');
			redirect('Annonce/');
		}
	}

    /**
	 * Fonction permettant de supprimer une categorie
	 * 
	 * @param $id_categ id de la categorie à modifier
     */

    public function delete($id_categ)
    {
		if($this->data['admin_user']){
			$this->categorie->delete($id_categ);
			redirect('Categorie/getAllCategories');
		}
		else{
			$this->session->set_flashdata('error', 'Fonction non autorisée');
			redirect('Annonce/');			
		}
    }

	/**
	 * Fonction permettant d'afficher toutes les categories
	 */
	public function getAllCategories()
	{
		if($this->data['admin_user']){
			$categories=$this->categorie->getAllCategorie('ASC');
			$this->load->view('elements/header',$this->data);
			$this->load->view('categorieTable',['categories'=>$categories]);
			$this->load->view('elements/footer');
		}
		else{
			$this->session->set_flashdata('error', 'Fonction non autorisée');
			redirect('Annonce/');			
		}
	}
}
