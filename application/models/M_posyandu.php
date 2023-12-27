<?php 

/**
* 
*/
class M_posyandu extends CI_model
{

private $table = 'tb_posyandu';
private $table2 = 'tb_balita';

//View
public function view($bulan='',$tahun='')
{
  $this->db->select ('*');
  $this->db->from ($this->table);
  $this->db->join($this->table2, 'tb_posyandu.id_balita = tb_balita.id_balita');
  $this->db->where('bulan', $bulan);
  $this->db->where('tahun', $tahun);
  $this->db->order_by('id_posyandu', 'ASC');
  return $this->db->get();
}

public function view_id($id='')
{
 return $this->db->select ('*')->from ($this->table)->where ('id_posyandu', $id)->get ();
}

//mengambil id urut terakhir
public function id_urut($value='')
{ 
  $this->db->select_max('id_posyandu');
  $this->db->from ($this->table);
}

public function add($SQLinsert){
  return $this -> db -> insert($this->table, $SQLinsert);
}

public function update($id='',$SQLupdate){
  $this->db->where('id_posyandu', $id);
  return $this->db-> update($this->table, $SQLupdate);
}

public function delete($id=''){
  $this->db->where('id_posyandu', $id);
  return $this->db-> delete($this->table);
}

}