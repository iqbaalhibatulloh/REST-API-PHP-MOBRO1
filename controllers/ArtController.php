<?php
include_once '../models/Art.php';

class ArtController {
    private $db;
    public $art;

    public function __construct(){
        $this->db = include('../config/Database.php');
        $this->art = new Art($this->db);
    }

    public function read($email){
        $this->art->email = $email;
        $result = $this->art->read();
        $art = array();
        while ($row = $result->fetch_assoc()){
            $art[] = $row;
        }
        return $art;
    }

    public function create($data, $email){
        $this->art->email = $email;
        $this->art->judul = $data['judul'];
        $this->art->artis = $data['artis'];
        $this->art->jenisKarya = $data['jenisKarya'];
        
        if (isset($_FILES['image'])) {
            $imageId = $this->art->generateUniqueImageId();
            $fileName = $imageId . ".jpeg";
            $directory = "../images/";
                        
            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
            }

            $targetFilePath = $directory . $fileName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                $this->art->imageId = $imageId;
                if($this->art->create()){
                    return true;
                }
            }
            return false;
        }
        return false;
    }

    public function delete($id, $email){
        $this->art->id = $id;
        $this->art->email = $email; 

        // Delete the memory record
        if($this->art->delete()){
            return true;
        }
        return false;
    }     
    
}
?>

