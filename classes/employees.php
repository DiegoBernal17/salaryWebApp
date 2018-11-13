<?php
/**
 * Clase de empleados
 * Esta clase se encarga de manejar la hoja de cálculo de empleados,
 * contiene todos los métodos y variables para su manejo correcto.
 */
namespace Employees;

// Llama a lo que se usará de PHPSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory as IOFactory;

class Employees {
  
  // Ruta del archivo de excel de empleados
  private static $file = PATH."/data/employees.xlsx";
  // Variables privadas para el manejo de la base de datos
  private $spreadsheet;
  private $sheet;
  private $last_sheet;
  private $writer;
  private $day_column;

  // Variables
  public $name;
  public $surnames;
  public $id_card;
  public $workstation;
  public $monday;
  public $tuesday;
  public $wednesday;
  public $thursday;
  public $friday;
  public $total_hours;
  public $salary_by_hour;
  public $subtotal;
  public $deduction_CCSS;
  public $deduction_AS;
  public $net_salary;

  // Constructor de la clase
  function __construct() {
    // Si el archivo existe entonces solo lo lee y guarda en la variable
    if (file_exists(self::$file)) {
      $this->spreadsheet = $this->readFile();
    } else {
      // Si no existe crea el archivo
      $this->spreadsheet = new Spreadsheet();
      // Cambia el nombre de la primer hoja a Semana 1
      $this->spreadsheet->getActiveSheet()->setTitle('Semana 1');
    }
    // Abre el documento para su escritura
    $this->writer = IOFactory::createWriter($this->spreadsheet, "Xlsx");
    // Y poderlo guardar
    $this->writer->save(self::$file);
    // Guarda la hoja actual activa en la variable 'sheet'
    $this->sheet = $this->spreadsheet->getActiveSheet();
  }

  // Método que sirve para registrar un nuevo empleado
  function registerEmployee() {
    // Obtene el número máximo de filas en el archivo
    $num_rows = $this->sheet->getHighestRow();
    // Para agregar una extra debajo de la actual
    $this->sheet->insertNewRowBefore($num_rows+1, 1);
    // En la fila creada asigna valores en las celdas correspondientes
    $this->sheet->setCellValue('A'.$num_rows, $this->name);
    $this->sheet->setCellValue('B'.$num_rows, $this->surnames);
    $this->sheet->setCellValue('C'.$num_rows, $this->id_card);
    $this->sheet->setCellValue('D'.$num_rows, $this->workstation);
    $this->sheet->setCellValue('K'.$num_rows, $this->salary_by_hour);
    // Guarda el archivo
    $this->writer->save(self::$file);
  }

  // Método que se encarga de hacer calculos para las celdas
  // Suma el total de horas, subtotal, deducción de CCSS y AS y el salario neto
  // Y después guarda todo ello en las celdas correspondientes
  function calculate() {
    // Ciclo para recorrer la hoja de calculo
    foreach($this->sheet->getRowIterator() as $row) {
      $cellIterator = $row->getCellIterator();
      $cellIterator->setIterateOnlyExistingCells(false);
      // Asigna el vario de horas totales a 0
      $this->total_hours = 0;
      foreach($cellIterator as $cell) {
        // Checa que la columna actual coincida con alguna E,F,G,H,I
        // que son las celdas de los días de la semana
        if($cell->getColumn() == 'E' || 
           $cell->getColumn() == 'F' || 
           $cell->getColumn() == 'G' || 
           $cell->getColumn() == 'H' || 
           $cell->getColumn() == 'I') {
          // Suma la variable de total_hours con el valor de la celda actual
          $this->total_hours += $cell->getValue();
        }
      }
      // Realiza las operaciones correspondientes una vez sumada las horas
      // Asigna en la variable subtotal la operación de: total_hours + valor de la celda K que es el salario por hora
      $this->subtotal = $this->total_hours * $this->sheet->getCell('K'.$row->getRowIndex())->getValue();
      // Deducción de CCSS se calcula el subtotal por 14.33%
      $this->deduction_CCSS = $this->subtotal / 100 * 14.33;
      // Deducción de AS se calcula el subtotal por 10% (Porcentaje falso asignado)
      $this->deduction_AS = $this->subtotal / 100 * 10;
      // El salario neto se saca con el subtotal menos las deducciones
      $this->net_salary = $this->subtotal - $this->deduction_CCSS - $this->deduction_AS;
      // Invoca al método saveOperations para guardar las variables anteriores en la hoja de calculo
      // pasando el número de fila actual
      $this->saveOperations($row->getRowIndex());
    }
  }

  // Método para guardar operaciones
  // Recibe el numero de fila
  function saveOperations($row) {
    // Asigna valores a celdas correspondientes
    $this->sheet->setCellValue('J'.$row, $this->total_hours);
    $this->sheet->setCellValue('L'.$row, $this->subtotal);
    $this->sheet->setCellValue('M'.$row, $this->deduction_CCSS);
    $this->sheet->setCellValue('N'.$row, $this->deduction_AS);
    $this->sheet->setCellValue('O'.$row, $this->net_salary);
    // Guarda archivo
    $this->writer->save(self::$file);
  }

  // Crea nueva hoja de calculo en el archivo actual
  // La nueva hoja representaría una nueva semana
  function createSheetOfWeek() {
    // Asigna a la variable 'sheet_count' el total de hojas actuales
    $sheet_count = $this->spreadsheet->getSheetCount();
    // Asigna a la variable 'sheet_name' el nombre que tendrá la nueva hoja
    $sheet_name = "Semana " . ($sheet_count + 1);
    // Guarda la hoja actual en una variable para después pasar los datos a la nueva
    $this->last_sheet = $this->sheet;
    // Es creada una nueva hoja de calculo y se asigna como la hoja actual
    $this->sheet = new Worksheet($this->spreadsheet, $sheet_name);
    // Se guarda la hoja actual creada en el archivo actual
    $this->spreadsheet->addSheet($this->sheet);
    // Se le asigna la hoja actual del documento la creada
    $this->spreadsheet->setActiveSheetIndex($sheet_count);
    // Se llama al método fillSheet para pasar los datos de la anterior a la nueva
    $this->fillSheet();
    // Se guarda el archivo
    $this->writer->save(self::$file);
  }

  // Este método se encarga de pasar los datos de la hoja anterior a la nueva actual
  function fillSheet() {
    // Un ciclo para pasar por cada fila
    foreach($this->last_sheet->getRowIterator() as $row) {
      $cellIterator = $row->getCellIterator();
      $cellIterator->setIterateOnlyExistingCells(false);
      // Ciclo para leer cada celda
      foreach($cellIterator as $cell) {
        // Un switch que pasa la columna actual
        switch($cell->getColumn()) {
          // Si la columna actual es 'A' se le asigna el valor actual a la variable 'name'
          case 'A':
            $this->name = $cell->getValue();
          break;
          // Si la columna actual es 'B' se le asigna el valor actual a la variable 'surname'
          case 'B':
            $this->surnames = $cell->getValue();
          break;
          // Si la columna actual es 'C' se le asigna el valor actual a la variable 'id_card'
          case 'C':
            $this->id_card = $cell->getValue();
          break;
          // Si la columna actual es 'D' se le asigna el valor actual a la variable 'workstation'
          case 'D':
            $this->workstation = $cell->getValue();
          break;
          // Si la columna actual es 'K' se le asigna el valor actual a la variable 'salary_by_hour'
          case 'K':
            $this->salary_by_hour = $cell->getValue();
        }
      }
      // Después de pasar por todas las celdas, antes de cambiar a la siguiente fila
      // se reigstra el empleado
      $this->registerEmployee();
    }
  }

  // Método muy indispensable que sirve para leer el archivo
  function readFile() {
    // Crea el nuevo objeto para leer archivos xlsx (hojas de calculo)
    $reader = new Xlsx();
    // No será solo de lectura
    $reader->setReadDataOnly(false);
    // Se carga el archivo actual al lector y se guarda en variable
    $spreadsheet = $reader->load(self::$file);

    // Se lee la hoja actual y se guarda en variable
    $this->sheet = $spreadsheet->getActiveSheet();
    // Regresa el archivo cargado
    return $spreadsheet;
  }

  // Este metodo es especial para poder actualizar celdas
  // que en este caso son las celdas de los días para dar las horas trabajadas
  function updateHours($cell, $value) {
    // asigana valor y guarda
    $this->sheet->setCellValue($cell, $value);
    $this->writer->save(self::$file);
  }

  // Muestra la tabla de las horas
  // Muestra el nombre, apellido y una caja de texto del dia actual
  function showTableHoursDay() {
    echo '<table>';
    echo '<thead>
            <tr>
              <td>Nombre</td>
              <td>Apellidos</td>
              <td>Horas de '.$this->dayOfWeek().'</td>
            </tr>
          </thead>
          <tbody>';
    foreach($this->sheet->getRowIterator() as $row) {
      $cellIterator = $row->getCellIterator();
      $cellIterator->setIterateOnlyExistingCells(false);
      echo '<tr>';
      foreach($cellIterator as $cell) {
        $column = $cell->getColumn();
        if(!is_null($cell) &&
           ($column == 'A' || 
           $column == 'B')) {
          echo '<td>' . $cell->getValue(). '</td>';
        } else if ($column == $this->day_column ) {
          echo '<td><input type="number" name="'.$column.$cell->getRow().'" onblur="addHours(this)" ';
          if(!empty($cell->getValue())) { 
            echo 'value="'.$cell->getValue().'" disabled><img src="resources/icons/edit.png" alt="*" onclick="enable(\''.$column.$cell->getRow().'\')">';
          } else  {
            echo '>';
          }
          echo '</td>';
        }
      }
      echo '</tr>';
    }
    echo '</tbody></table>';
  }

  // Muestra una tabla con el nombre, apellidos y los días de la semana a trabajar
  function showTable() {
    $this->readFile();
    echo '<table>';
    echo '<thead>
            <tr>
              <td>Nombre</td>
              <td>Apellidos</td>
              <td>Lunes</td>
              <td>Martes</td>
              <td>Miercoles</td>
              <td>Jueves</td>
              <td>Viernes</td>
            </tr>
          </thead>';
    foreach($this->sheet->getRowIterator() as $row) {
      $cellIterator = $row->getCellIterator();
      $cellIterator->setIterateOnlyExistingCells(false);
      echo '<tr>';
      foreach($cellIterator as $cell) {
        if(!is_null($cell) &&
           ($cell->getColumn() == 'A' || 
           $cell->getColumn() == 'B' || 
           $cell->getColumn() == 'E' || 
           $cell->getColumn() == 'F' || 
           $cell->getColumn() == 'G' || 
           $cell->getColumn() == 'H' || 
           $cell->getColumn() == 'I')) {

          $value = $cell->getValue();
          echo '<td>' . $value . '</td>';
        }
      }
      echo '</tr>';
    }
    echo '</table>';
  }

  // Muestra una tabla con todos los datos 
  // y ya con los totales definidos
  function showSalaryTable() {
    $this->readFile();
    echo '<table>';
    echo '<thead>
            <tr>
              <td>Nombre</td>
              <td>Apellidos</td>
              <td>Lunes</td>
              <td>Martes</td>
              <td>Miercoles</td>
              <td>Jueves</td>
              <td>Viernes</td>
              <td>Totales</td>
              <td>CCSS</td>
              <td>A.S.</td>
              <td>S. Neto</td>
            </tr>
          </thead>';
    foreach($this->sheet->getRowIterator() as $row) {
      $cellIterator = $row->getCellIterator();
      $cellIterator->setIterateOnlyExistingCells(false);
      echo '<tr>';
      foreach($cellIterator as $cell) {
        if(!is_null($cell) &&
           ($cell->getColumn() == 'A' || 
           $cell->getColumn() == 'B' || 
           $cell->getColumn() == 'E' || 
           $cell->getColumn() == 'F' || 
           $cell->getColumn() == 'G' || 
           $cell->getColumn() == 'H' || 
           $cell->getColumn() == 'I'|| 
           $cell->getColumn() == 'J'||
           $cell->getColumn() == 'M'|| 
           $cell->getColumn() == 'N'|| 
           $cell->getColumn() == 'O')) {

          $value = $cell->getValue();
          echo '<td>' . $value . '</td>';
        }
      }
      echo '</tr>';
    }
    echo '</table>';
  }

  // Obtiene la ultima semana (El nombre de la hoja actual)
  function getLastWeek() {
    $this->readFile();
    return $this->sheet->getTitle();
  }

  // Función que obtiene el nombre en español del dia de la semana de hoy
  function dayOfWeek() {
    switch(date('N')) {
      case 1:
        $this->day_column = 'E';
        return "Lunes";
      case 2:
        $this->day_column = 'F';
        return "Martes";
      case 3:
        $this->day_column = 'G';
        return "Miércoles";
      case 4:
        $this->day_column = 'H';
        return "Jueves";
      case 5:
        $this->day_column = 'I';
        return "Viernes";
      case 6:
        return "Sábado";
      case 7:
        return "Domingo";
    }
  }

  // Obtiene la fecha actual
  function getDay() {
    $day = $this->dayOfWeek();
    return $day." ".date('d/m/Y');
  }
}
?>