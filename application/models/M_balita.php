<?php 

/**
* 
*/
class M_balita extends CI_model
{

private $table = 'tb_balita';

//View
public function view($value='')
{
  $this->db->select ('*');
  $this->db->from ($this->table);
  $this->db->order_by('nama', 'ASC');
  return $this->db->get();
}

public function view_id($id='')
{
 return $this->db->select ('*')->from ($this->table)->where ('id_balita', $id)->get ();
}

//mengambil id urut terakhir
public function id_urut($value='')
{ 
  $this->db->select_max('id_balita');
  $this->db->from ($this->table);
}

public function add($SQLinsert){
  return $this -> db -> insert($this->table, $SQLinsert);
}

public function update($id='',$SQLupdate){
  $this->db->where('id_balita', $id);
  return $this->db-> update($this->table, $SQLupdate);
}

public function delete($id=''){
  $this->db->where('id_balita', $id);
  return $this->db-> delete($this->table);
}

}