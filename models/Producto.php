
public function save(){
    // Escapar datos para evitar inyecciones SQL simples si no usas PDO bindparam estricto
    // Asumiendo que usas mysqli en tu $this->db
    $titulo = $this->db->real_escape_string($this->titulo);
    $artista = $this->db->real_escape_string($this->artista);
    $descripcion = $this->db->real_escape_string($this->descripcion);
    
    $sql = "INSERT INTO productos VALUES(NULL, '{$this->getTipo()}', '$titulo', '$artista', {$this->getPrecio()}, '{$this->getImagen()}', '$descripcion', {$this->getStock()});";
    
    $save = $this->db->query($sql);

    $result = false;
    if($save){
        $result = true;
    }
    return $result;
}