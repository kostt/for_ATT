<html>
<head>
  <title></title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <script src="http://code.jquery.com/jquery-latest.js"></script>
  <script src="js/bootstrap.min.js"></script>

<?
/**
* 
* @author Мазуркевич Константин <felix-mazur@mail.ru>
* @version 1.0
* @package files
*
* Константы для подключения БД
*/
define("LH", "localhost");
define("ROOT", "root");
define("DB", "my_bd");

 /**
   * @final Класс myTest от которого нельзя сделать наследника
   */
final class myTest
{

 /**
   * Конструктор запускает 2 метода
   * метод install() создает таблицу, метод fill() заполняет таблицу
   */	

	  function __construct() {
	  	//$this->install();
	  	$this->fill();
   }

 /**
   * Защищенный метод install() создает таблицу в БД
   */
protected function install() 
{
$link = mysqli_connect(constant("LH"),constant("ROOT"), "", constant("DB")) or die("Ошибка " . mysqli_error($link)); //Подключается к БД или выводит ошибку

 /**
   * Формируем запрос на создание таблицы myTable
   * id тип integer (длинна 11) авто-инкремент
   * script_name varchar на 25 символов
   * script_execution_time double длинна 20 символов и 2 после запятой
   * script_result enum на выбор 3 значения
   */	

$query ="CREATE TABLE myTable (
  id int(11) NOT NULL AUTO_INCREMENT,
  script_name varchar(25) NOT NULL,
  script_execution_time double(20,2) NOT NULL,
  script_result enum('active','failed','success') DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";

$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link) . ";<br>");  //Пробуем выпонить запрос

if($result)
{
    echo "Создание таблицы прошло успешно<br>";
}

 /**
   * Пробуем создать таблицу, если успешно выводим сообщение и закрываем соединение с БД
   */	
 
mysqli_close($link);
}

 /**
   * Защищенный метод fill() заполняет таблицу произвольными значениями
   */
protected function fill()
{
$link = mysqli_connect(constant("LH"),constant("ROOT"), "", constant("DB")) or die("Ошибка " . mysqli_error($link));//Подключается к БД или выводит ошибку

 /**
   * Подключаемся, показываем столбцы, где поле = script_result, чтобы, например, вписывать новые значения из базы, а не лезть в код
   */	

$query = "SHOW COLUMNS FROM myTable WHERE Field = 'script_result'";//Формируем запрос для вывода значений из поля script_result
$result = mysqli_query($link, $query); //Обрабатываем запрос
$r = mysqli_fetch_array($result); //Выводим данные

/**
*@var array $matches массив (Убираем лишние символы)
*/
preg_match("/^enum\(\'(.*)\'\)$/", $r['Type'], $matches);
/**
*@var array $array массив данных (разбиваем по ',' и заносим в массив)
*/
$array = explode("','", $matches[1]);

for($i=0;$i<=500;$i++) //Цикл на 500 значений
{
/**
*@var string $tName создаём уникальные значения
*/
$tName = md5(uniqid(""));
/**
*@var string $tName обрезаем на 25 символов
*/
$tName = substr($tName, 0, 25);
/**
*@var integer $fTime создаём дробные числа рандомом
*/
$fTime = round(lcg_value()*100000,2);
/**
*@var string $eCond рандомно присваиваем значения script_result
*/
$eCond = $array[array_rand($array, 1)]; 

$query = "INSERT INTO myTable (`script_name`,`script_execution_time`,`script_result`) VALUES ('$tName','$fTime','$eCond')"; //Формируем запрос для добавления новых полей
$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link) . ";<br>");  //Пробуем выпонить запрос
}
echo "Заполнили таблицу<br>"; //Если успех - выводим сообщение
}

 /**
   * @static Открытый метод get() выводит нужные значения из таблицы
   */
static function get()
{
$link = mysqli_connect(constant("LH"),constant("ROOT"), "", constant("DB")) or die("Ошибка " . mysqli_error($link)); //Подключается к БД или выводит ошибку
$query = "SELECT * FROM myTable WHERE `script_result`='failed'"; //Выводим значения где script_result=failed
$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link) . ";<br>"); //Пробуем выпонить запрос
echo '<div class="container"><div class="row">'; //Создаем контейнер от bootstrap
while($r = mysqli_fetch_array($result)){ //Значения из БД выводим в цикле
echo '<div class="span4">'.$r['script_name'].'</div><div class="span4">'.$r['script_execution_time'].'</div><div class="span4">'.$r['script_result']."</div>"; //Помещаем значения в 3 колонки
}
echo "</div></div>";//закрываем контейнер

}

}

$obj = new myTest(); //Создаем экземпляр класса
$obj::get(); //Вызываем статический метод get для вывода данных

?>


</body>
</html>
