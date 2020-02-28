<?php

class Image_model extends CI_Model
{
    public $idImage;
    public $url;
    
    public function insert()
    {
        $arr=array(
            'id_annonce'=>1,
            'url'=>"./public/images");
        return $this->db->insert('image',$arr);
    }

    public function delete($id)
    {
        return $this->db->delete('image',array('id_image'=>$id));
    }

    public function update($model)
    {
        $this->db->where('id_image', $model['id_image']);
        unset($model['btnSubmit']);
        return $this->db->update('image',$model);
    }
    public function getImage($id)
    {
        return $this->db->get_where('image',array('id_image' => $id))->result_array();
    }
}