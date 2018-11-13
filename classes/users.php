<?php
/**
 * Esta clase se encarga de manejar los usuarios que ingresaran a a la aplicación
 */
namespace Users;

// Se usan las clases necesarias de PHPSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory as IOFactory;

class Users {
  
  // Esta es la ruta del archivo de usuarios
  private static $file = PATH."/data/users.xlsx";
  private $spreadsheet;
  private $sheet;
  private $writer;
  // Variables de la clave que requiere el usuario
  public $id_user;
  public $name;
  public $last_name;
  public $email;
  public $password;

  // Constructor de la clase
  function __construct() {
    // Comprueba si el archivo existe
    if (file_exists(self::$file)) {
      // Si existe lo lee solamente
      $this->spreadsheet = $this->readFile();
    } else {
      // Si no existe crea el archivo vacio
      $this->spreadsheet = new Spreadsheet();
    }
    // Para poder escribir en el archivo
    $this->writer = IOFactory::createWriter($this->spreadsheet, "Xlsx");
    // Solamente lo guarda
    $this->writer->save(self::$file);
    // Y asigna variables de la hoja actual
    $this->sheet = $this->spreadsheet->getActiveSheet();
  }

  // Método para leer el archivo
  function readFile() {
    // Crea un lector
    $reader = new Xlsx();
    // NO será solo para leer, puede haber operaciones
    $reader->setReadDataOnly(false);
    // Carga el archivo y asigna a variable
    $spreadsheet = $reader->load(self::$file);
    // retorna la variable
    return $spreadsheet;
  }

  // Este método sirve para registrar al usuario
  function register() {
    // Lee el total de filas
    $num_rows = $this->sheet->getHighestRow();
    // Inserta una nueva fila debajo de la actual
    $this->sheet->insertNewRowBefore($num_rows+1, 1);
    // Crea un id especial que solo es el numero de filas + 1
    $this->id_user = $num_rows+1;
    // Guarda valores en las celdas correspondientes
    $this->sheet->setCellValue('A'.$num_rows, $this->name);
    $this->sheet->setCellValue('B'.$num_rows, $this->last_name);
    $this->sheet->setCellValue('C'.$num_rows, $this->email);
    // Encripta la contraseña
    $this->sheet->setCellValue('D'.$num_rows, password_hash($this->password, PASSWORD_BCRYPT));
    // Guarda el archivo
    $this->writer->save(self::$file);
  }


  // Método que revisa si el correo existe
  function existEmail() {
    // Ciclo para recorrer todas las celdas
    foreach($this->sheet->getRowIterator() as $row) {
      $cellIterator = $row->getCellIterator();
      $cellIterator->setIterateOnlyExistingCells(false);

      foreach($cellIterator as $cell) {
        // Si la celda actual tiene como columna 'C' que es la del correo
        if($cell->getColumn() == 'C') {
          // entonces ahora checa si el valor de dicha celda es igual al del correo dado
          if($cell->getValue() == $this->email) {
            // Si es igual entonces asigna el numero de la celda al id_user
            $this->id_user = $cell->getRow();
            // retorna verdadero porque si lo encontró
            return true;
          }
        }
      }
    }
    // si no retorna verdadero llegará hasta aquí y retornará falso
    return false;
  }

  // Método que regresa la información que contiene una columna
  function getDataByColumn($column, $row = USER_ID) {
    // obtiene el valor de la celda con la fila y columna, ejemplo: A1
    $data = $this->sheet->getCell($column.$row);
    return $data;
  }

  // Método que verifica la contraseña
  function checkPassword() {
    // Obtiene la celda D + el numero de id ejemplo: D1 que es el campo
    // en la hoja de la contraseña y lo guarda en una variable
    $password_in_db = $this->sheet->getCell("D".$this->id_user);

    // verifica con el metodo de password_verify (que checa contraseñas encriptadas)
    // que las contraseñas coincidan
    if(password_verify($this->password, $password_in_db)) {
      // si coinciden retorna verdadero
      return true;
    }
    // y si no entonces falso
    return false;
  }

  // Esta funcion solo se encarga de crear la sesión 'logged' y 'id'
  function login() {
    $_SESSION['logged'] = true;
    $_SESSION['id'] = $this->id_user;
  }

  // Obtiene el nombre del usuario solo pasando el numero de la fila
  function getName($row = USER_ID) {
    return $this->getDataByColumn("A", $row);
  }
}

?>