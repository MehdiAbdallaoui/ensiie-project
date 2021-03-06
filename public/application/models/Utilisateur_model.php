<?php

class Utilisateur_model extends CI_Model
{
    public $id_user;
    public $nom;
    public $prenom;
    public $pseudo;
    public $password;
    public $email;
    public $telephone;
    public $promo;
    public $droit_publication;
    public $nb_signal_user;
    public $admin;

    public function insert($arr)
    {
        return $this->db->insert('utilisateur',$arr);
    }

    public function delete($id)
    {
        return $this->db->delete('utilisateur',array('id_user'=>$id));
    }

    public function update($model)
    {
        $this->db->where('id_user', $model['id_user']);
        unset($model['btnSubmit']);
        return $this->db->update('utilisateur',$model);

    }

    public function getAllUser()
    {
        return $this->db->select('*')
            ->from('utilisateur')
            ->get()
            ->result_array();
    }

    /**
     * Fonction permettant de récupérer un utilisateur
     * 
     * @param $id Id de l'utilisateur
     * 
     * @return
     * utilisation sous forme d'un array
     */
    public function getUser($id){
        return $this->db->get_where('utilisateur',array('id_user' => $id))->result_array();
    }

    public function login($data,$passvalid)
    {
        if($passvalid) {
            return !empty($this->db->get_where('utilisateur', array('email' => $data['mail']))->result_array());
        } else return false;
    }

    public function userByEmail($email) {
        return $this->db->get_where('utilisateur',array('email'=>$email))->result_array();
    }
}
