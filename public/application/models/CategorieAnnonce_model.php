<?php

class CategorieAnnonce_model extends CI_Model
{
    public $idAnnonce;
    public $idCategorie;

    public function insert()
    {
        $arr=array(
            'id_annonce'=>1,
            'categorie'=>1);
        return $this->db->insert('Categorie_annonce',$arr);
    }

    public function delete($annonce,$categorie)
    {
        return $this->db->delete('Categorie_annonce',array('id_annonce'=>$annonce,'categorie'=>$categorie));
    }

    public function update($model)
    {
        $this->db->where('Categorie_annonce', $model['categorie'],$model['id_annonce']);
        unset($model['btnSubmit']);
        return $this->db->update('image',$model);
    }

    public function getAllCategorieAnnonce()
    {
        return $this->db->select('*')
            ->from('Categorie_annonce')
            ->get()
            ->result_array();
    }
    public function getCategorieAnnonce($annonce,$categorie)
    {
        return $this->db->get_where('Categorie_annonce',array('id_annonce'=>$annonce,'categorie'=>$categorie))->result_array();
    }
}