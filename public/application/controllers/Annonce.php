<?php

class Annonce extends CI_Controller {

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

	function index(){
		$this->liste_annonces();
	}

	public function liste_annonces(){
		$min = $this->annonce->minPrice();
		$max = $this->annonce->maxPrice();
		$annonces = $this->annonce->getAllAnnonce();
		$this->data += array('annonces'=>$annonces, 'min'=>$min, 'max'=>$max);

		$categories=array_column($this->categorie->getAllCategorie(),'categorie');
		$this->data+=array('categories'=>$categories);

		$this->load->view('elements/header',$this->data);
		$this->load->view('annonces_view', $this->data);
		$this->load->view('elements/footer');
	}

	public function a_propos(){
		$this->load->view('elements/header',$this->data);
		$this->load->view('aProposTable');
		$this->load->view('elements/footer');
	}

	/**
	 * Fonction permettant d'afficher les annonces de l'utilisateur connecté
	 */
	public function mes_annonces(){

		$min = $this->annonce->minPrice();
		$max = $this->annonce->maxPrice();
		$this->data += array('min'=>$min, 'max'=>$max);
		$mes_annonces=$this->annonce->getUserAnnonce($this->data['id_user']);

		$categories=array_column($this->categorie->getAllCategorie(),'categorie');
		$this->data+=array('categories'=>$categories);
		
		$this->data+=array('mes_annonces'=>$mes_annonces);
		$this->load->view('elements/header',$this->data);
		$this->load->view('mes_annonces_view',$this->data);
		$this->load->view('elements/footer');
	}

	public function ajouter_annonce(){
  
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		$this->form_validation->set_rules('titre', 'Titre', 'required');
		$this->form_validation->set_rules('description', 'Description', 'required');
		$this->form_validation->set_rules('prix', 'Prix', 'required|numeric');

		$etats=array_column($this->etat->getAllEtat(), 'etat');
		$this->data+=array("etats"=>$etats);
		$categories=array_column($this->categorie->getAllCategorie(),'categorie');
		//$categories=array_combine(range(1, count($categories)), array_values($categories));

		$this->data+=array("categories"=>$categories);

		if($this->input->post('titre')){
			if ($this->form_validation->run() == TRUE) {

				$cat_annonce = explode(",",$this->input->post('categorie'));

				$this->annonce->insertAnnonce(
								$this->data['id_user'],
								$this->input->post('titre'),
								$this->input->post('description'),
								$this->input->post('prix'),
								$this->input->post('etat'),
								$this->input->post('image'),
								$cat_annonce);
				
				$this->session->set_flashdata('message', 'Annonce ajoutée');
				redirect('Annonce/liste_annonces');
	
			}else{
				$this->session->set_flashdata('error', 'Annonce non ajoutée, veuillez réessayer');
				$this->load->view('elements/header',$this->data);
				$this->load->view('gestion_annonce_view',$this->data);
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
				$this->load->view('gestion_annonce_view',$this->data);
			}
			$this->load->view('elements/footer');			
		}

	}

	/**
	 * Fonction permettant de modifier une annonce
	 * 
	 * @param $id_annonce Id de l'annonce à modifier
	 */
	public function modifier_annonce($id_annonce){

		$id_user_annonce=$this->annonce->getAnnonce($id_annonce)[0]['id_user'];
		
		if($this->data['id_user']==$id_user_annonce){

			$this->form_validation->set_error_delimiters('<p class="form_erreur">', '</p>');
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			$this->form_validation->set_rules('titre', 'Titre', 'required');
			$this->form_validation->set_rules('description', 'Description', 'required');
			$this->form_validation->set_rules('prix', 'Prix', 'required|numeric');

			$etats=array_column($this->etat->getAllEtat(), 'etat');
			$etats=array_combine(range(1, count($etats)), array_values($etats));
			$this->data+=array("etats"=>$etats);

			$categories=array_column($this->categorie->getAllCategorie(),'categorie');
			//$categories=array_combine(range(1, count($categories)), array_values($categories));

			$this->data+=array("categories"=>$categories);

			if($this->form_validation->run()){

				$cat_annonce = explode(",",$this->input->post('categorie'));
				
				$this->annonce->updateAnnonce($id_annonce,
					$this->data['id_user'],
					$this->input->post('titre'),
					$this->input->post('description'),
					$this->input->post('prix'),
					$this->input->post('etat'),
					$this->input->post('image'),
					$cat_annonce);

				$this->session->set_flashdata('message', 'Annonce modifiée');		  
				redirect('Annonce/liste_annonces');
			}
			else{
			$annonce=$this->annonce->getAnnonce($id_annonce);
			$mes_categories = array_column($this->categorieAnnonce->getAllCategorieAnnonce($id_annonce),'categorie');
			$this->data+=array("annonce_modif"=>$annonce[0]);
			$this->data+=array("categorie_modif"=>$mes_categories);
			$this->load->view("elements/header",$this->data);
			$this->load->view('gestion_annonce_view',$this->data);
			$this->load->view("elements/footer");
			}

		}else{
			$this->session->set_flashdata('error', 'Modification non autorisée');		  
			redirect('Annonce/liste_annonces');			
		}
 
	 }

	/**
	 * Fonction permettant de signaler une annonce
	 *
	 * @param $id_annonce Id de l'annonce à signaler
	 */
	public function signaler_annonce($id_annonce){

		$this->annonce->signaler($id_annonce);
		$this->session->set_flashdata('message', 'Annonce signalée');	  
		redirect('Annonce/liste_annonces');
	}

	public function getAnnoncesSignalees(){

		if($this->data['admin_user']){
			$annonces=$this->annonce->get_annonces_signalees();
			$this->load->view('elements/header',$this->data);
			$this->load->view('annonceTable',['annonces'=>$annonces]);
			$this->load->view('elements/footer');
		}
		else{
			$this->session->set_flashdata('error', 'Fonction non autorisée');
			redirect('Annonce');
		}

	}

	/**
	 * Fonction permettant de supprimer une annonce
	 * 
	 * @param $id_annonce Id de l'annonce à supprimer
	 */
	public function supprimer_annonce($id_annonce){
		$id_user_annonce=$this->annonce->getAnnonce($id_annonce)[0]['id_user'];
		
		if($this->data['id_user']==$id_user_annonce){
			$this->annonce->deleteAnnonce($id_annonce);
			$this->session->set_flashdata('message', 'Annonce supprimée');	  
			redirect('Annonce/mes_annonces');
		}
		else{
			$this->session->set_flashdata('error', 'Suppression non autorisée');
			redirect('Annonce');
		}

	  
	}

	/**
	 * Fonction permettant de supprimer une annonce
	 * 
	 * @param $id_annonce Id de l'annonce à supprimer
	 */
	public function supprimer_annonceSignale($id_annonce){

		if($this->data['admin_user']){
			$this->session->set_flashdata('message', 'Annonce supprimée');	  
			$this->annonce->deleteAnnonce($id_annonce);
			redirect('Annonce/getAnnoncesSignalees');
		}else{
			$this->session->set_flashdata('error', 'Suppression non autorisée');
			redirect('Annonce');
		}
	}

	/**
	 * Fonction permettant d'afficher le détail d'une annonce
	 * 
	 * @param $id Id de l'annonce
	 */
	public function details_annonce($id){

		$annonce = $this->annonce->getAnnonce($id);
		$image = $this->image->getImage($id);
		$user_annonce = $this->utilisateur->getUser($annonce[0]['id_user']);
		$etat_annonce = $this->etat->getEtat($annonce[0]['id_etat']);
		$categorie_annonce = $this->categorieAnnonce->getAllCategorieAnnonce($id);

		$this->data+=array('details_annonce'=>$annonce);
		$this->data+=array('image'=>$image);
		$this->data+=array('user_annonce'=>$user_annonce);
		$this->data+=array('etat_annonce'=>$etat_annonce);
		$this->data+=array('categorie_annonce'=>$categorie_annonce);

		$this->load->view('elements/header',$this->data);
		$this->load->view('details_annonce_view.php',$this->data);
		$this->load->view('elements/footer');	
	}

	// File upload
	public function fileUpload(){

		if(!empty($_FILES['file']['name'])){
	 
		  // Set preference
		  $config['upload_path'] = 'assets/images/'; 
		  $config['allowed_types'] = 'jpg|jpeg|png|gif';
		  //$config['max_size'] = '2024'; // max_size in kb
		  $config['file_name'] = $_FILES['file']['name'];
		  $this->data+=array('files'=>$_FILES['file']['name']);
	 
		  //Load upload library
		  $this->load->library('upload',$config); 
	 
		  // File upload
		  if($this->upload->do_upload('file')){
			// Get data about the file
			$uploadData = $this->upload->data();
			$this->data+=array('data_img'=>$uploadData);
		  }
		  print_r($this->data);
		}
	}

}
?>
