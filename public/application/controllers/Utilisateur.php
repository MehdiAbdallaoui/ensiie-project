<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Utilisateur extends CI_Controller
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
            $this->data+=array('droit_publication' => $this->session->userdata('logged_in')['droit_publication']);
		}
    }

    public function index(){
        
	}

    /**
     * Fonction permettant de mettre à jour les informations de l'utilisateur
     */

    public function update(){
        if($this->input->post())
        {
            unset($_POST['submit']);
            if($this->utilisateur->update($this->input->post()))
            {
                //echo "<script>alert(\"Modification réussie\")</script>";
                if(!$this->input->post('admin'))
                {
                    redirect('utilisateur/profil');
                } else redirect('utilisateur/AllUsers');
            } else
            {
                //echo "<script>alert(\"modification failed\")</script>";
                redirect('utilisateur/update?id='.$this->input->post('id_user'));
            }
        } else
        if(isset($_GET['id']))
        {
            $user=$this->utilisateur->getUser($_GET['id']);
            if($this->session->userdata('logged_in')['admin']) {
                if($user[0]['admin'])
                {
                    $this->load->view('elements/header', $this->data);
                    $this->load->view('update_view_user', ['user' => $user]);
                    $this->load->view('elements/footer');
                } else {
                    $this->load->view('elements/header', $this->data);
                    $this->load->view('update_view', ['user' => $user]);
                    $this->load->view('elements/footer');
                }
            } else {
                $this->load->view('elements/header', $this->data);
                $this->load->view('update_view_user', ['user' => $user]);
                $this->load->view('elements/footer');
            }
        }
    }

    /**
     * Fonction permettant de supprimer un utilisateur
     * 
     * @param id_user id de l'utilisateur à supprimer
     */

    public function delete($id_user){

        if($this->data['admin_user']){
            $this->utilisateur->delete($id_user);
            redirect('Annonce/liste_annonces');
        }
        else{
            $this->session->set_flashdata('error', 'Fonction non autorisée');
			redirect('Annonce');
        }

    }
    
    /**
     * Fonction permettant de lister l'ensemble des utilsateurs
     */
    public function AllUsers(){
        if($this->data['admin_user']){
            $users=$this->utilisateur->getAllUser();
            $this->data += array("users"=>$users);
            $this->load->view('elements/header',$this->data);
            $this->load->view('userTable',$this->data);
            $this->load->view('elements/footer');
        }
        else{
            $this->session->set_flashdata('error', 'Fonction non autorisée');
			redirect('Annonce');           
        }
    }

    /**
     * Fonction permettant d'afficher les informations de l'utilisateur
     */
    public function profil(){
        if(isset($this->session->userdata['logged_in'])){

            $annonce=$infos=$this->annonce->getAnnonceByUser($this->session->userdata('logged_in')['id_user']);
			$annonces_sig=$this->annonce->get_annonces_signalees();
            $infos=$this->utilisateur->getUser($this->session->userdata('logged_in')['id_user']);
            $promo=$infos[0]['promo'];
            $telephone=$infos[0]['telephone'];
            $pseudo=$infos[0]['pseudo'];
			$admin=$infos[0]['admin'];
			$data = ["nom"=>$infos[0]['nom'],"prenom"=>$infos[0]['prenom'],"nbAnnonces" => $this->annonce->totalAnnonces(),"promo"=>$promo,"pseudo"=>$pseudo,"telephone"=>$telephone,"annonces"=>$annonce, "admin"=>$admin, "annonces_sig"=>$annonces_sig];
            $this->load->view('elements/header',$this->data);
            $this->load->view('profil',$data);
            $this->load->view('elements/footer');
        }
    }
}
