<!DOCTYPE html> 
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <title>Главная</title>
  <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <?php
    setcookie('ordersearchtext', $ordersearchtext, time() - 3600, "/"); //удаление куки, используемых другими страницами
    setcookie('consumption_type', $consumption_type, time() - 3600, "/");
    setcookie('reporting_type', $consumption_type, time() - 3600, "/");
    setcookie('reporting_from', $reporting_from, time() - 3600, "/");
    setcookie('reporting_to', $reporting_to, time() - 3600, "/");
    if($_COOKIE['worker_id'] == ''): //если нет cookie, открывается форма авторизации
  ?>
    <!-- авторизация -->
    <?php
      if($_COOKIE['error'] == 'wrongpass'): //если пароль был неправильно введен выводится ошибка
    ?>
    <div class="errorbox">
      Неверный логин или пароль
    </div>
    <?php
      setcookie('error', 0, time() - 3600, "/");
      endif;
    ?>
    <!-- форма авторизации -->
    <login-form>
      <div class="main-content, center">
          <form role="form" method="POST" action="php/login.php">
              <div class="form-container form-login">
                  <label for="login"><b>Логин</b></label>
                  <input type="login" placeholder="Введите логин" name="login" id="login" required>
                  <label for="password"><b>Пароль</b></label>
                  <input type="password" placeholder="Введите пароль" name="password" id="password" required>
                  <button type="submit" class="formbtn-login">Войти</button>
              </div>
          </form>
      </div>
    </login-form> 
  <?php
    else: //если есть cookie открывается главная страница web-сервиса
    $link = new mysqli('localhost', 'root', 'root', 'knizhnik_db');
    if (!$link) {
        echo 'Не могу соединиться с БД. Код ошибки: ' . mysqli_connect_errno() . ', ошибка: ' . mysqli_connect_error();
        exit;
    }
    if ($link) {
      $worker_id = filter_var(trim($_COOKIE["worker_id"]),FILTER_SANITIZE_STRING);
      $result = $link->query("SELECT * FROM `workers` WHERE `worker_id` = '$worker_id'");
      $worker = $result->fetch_assoc();
      $worker_name = $worker['worker_name'];
      $worker_role = $worker['role'];
    }
  ?>
    <!-- главная страница -->
    <div class="container contain" id="container">
      <header id="header">
          <img src="images/logo_wide.png" class="logo"/>
          <h1>Книги для всей семьи!</h1>
          <div class="header-worker">
            <!-- вывод ФИО и должности работника -->
            <div class="header-box-left"><a href="" class="header-name"><?php echo $worker_name; ?> — <?php echo $worker_role; ?></a></div>
            <div class="header-box-right"><a href="php/exit.php" class="logout">Выйти</a></div>
          </div>
      </header>
      <!-- навигационное меню -->
      <nav id="nav"> 
        <div class="nav-content nav-scroll">
          <ul class="nav-menu">
            <?php if($worker_role == 'Администратор') {
            echo '<li><a href="/" class="current">Склад</a></li>';
            echo '<li><a href="supplies.php">Приемка товара</a></li>';
            echo '<li><a href="consumption.php">Расход</a></li>';
            echo '<li><a href="orders.php">Заказы</a></li>';
            echo '<li><a href="reporting.php">Отчетность</a></li>';
            echo '<li><a href="info.php">Инфо</a></li>';
            } else if($worker_role == 'Кладовщик') {
            echo '<li><a href="/" class="current">Склад</a></li>';
            echo '<li><a href="supplies.php">Приемка товара</a></li>';
            echo '<li><a href="consumption.php">Расход</a></li>';
            echo '<li><a href="reporting.php">Отчетность</a></li>';
            echo '<li><a href="info.php">Инфо</a></li>';
            } else if($worker_role == 'Продавец') {
            echo '<li><a href="/" class="current">Склад</a></li>';
            echo '<li><a href="consumption.php">Расход</a></li>';
            echo '<li><a href="orders.php">Заказы</a></li>';
            echo '<li><a href="reporting.php">Отчетность</a></li>';
            echo '<li><a href="info.php">Инфо</a></li>';
            }?>
          </ul>
        </div>
      </nav>
      <main>
        <div class="main-content">
          <!-- форма поиска продукции -->
          <form role="form" method="POST" action="">
              <div class="form-container form-search form-search-two-buttons">
                <p>Искать по
                  <select name="serach-type" id="serach-type">
                    <option selected value="code">Артикулу</option>
                    <option value="product_name">Наименованию</option>
                    <option value="barcode">Штрихкоду</option>
                  </select>
                </p>
                <input type="search-text" placeholder="" name="search-text" id="search-text">
                <button type="submit" name="btn_stock" id="btn_stock" class="formbtn-search">Поиск</button>
                <button type="submit" name="btn_reset" id="btn_reset" class="formbtn-search">Сброс</button>
              </div>
              <?php
                  /* Функции кнопок */
                  function btn_stock()
                  {
                    $serachtype = filter_input(INPUT_POST, 'serach-type', FILTER_SANITIZE_STRING);
                    $searchtext = filter_var(trim($_POST['search-text']), FILTER_SANITIZE_STRING);
                    setcookie('serachtype', $serachtype, time() + 3600, "/");
                    setcookie('searchtext', $searchtext, time() + 3600, "/");
                    header('location: /');
                  }
                  if(array_key_exists('btn_stock',$_POST)){
                    btn_stock();
                  }
                  function btn_reset()
                  {
                    setcookie('serachtype', $serachtype, time() - 3600, "/");
                    setcookie('searchtext', $searchtext, time() - 3600, "/");
                    header('location: /');
                  }
                  if(array_key_exists('btn_reset',$_POST)){
                    btn_reset();
                  }
                ?>
          </form>
          <div class="table-container table-scroll">
            <table>
                <?php
                  $link = new mysqli('localhost', 'root', 'root', 'knizhnik_db');
                  if (!$link) {
                      echo 'Не могу соединиться с БД. Код ошибки: ' . mysqli_connect_errno() . ', ошибка: ' . mysqli_connect_error();
                      exit;
                  }
                  if ($link) {
                    /* Все варианты сортировки */
                    $sort_list = array(
                      'code_asc'   => '`code`',
                      'code_desc'  => '`code` DESC',
                      'barcode_asc'  => '`barcode`',
                      'barcode_desc' => '`barcode` DESC',
                      'product_name_asc'   => '`product_name`',
                      'product_name_desc'  => '`product_name` DESC',
                      'publisher_name_asc'   => '`publisher_name`',
                      'publisher_name_desc'  => '`publisher_name` DESC',
                      'year_of_publishing_asc'   => '`year_of_publishing`',
                      'year_of_publishing_desc'  => '`year_of_publishing` DESC',
                      'author_name_asc'   => '`author_name`',
                      'author_name_desc'  => '`author_name` DESC',
                      'group_concat(genres.genre_name)_asc'   => '`group_concat(genres.genre_name)`',
                      'group_concat(genres.genre_name)_desc'  => '`group_concat(genres.genre_name)` DESC',
                      'sell_price_asc'   => '`sell_price`',
                      'sell_price_desc'  => '`sell_price` DESC',
                      'stock_quantity_asc'   => '`stock_quantity`',
                      'stock_quantity_desc'  => '`stock_quantity` DESC',
                    );
                    /* Проверка GET-переменной */
                    $sort = @$_GET['sort'];
                    if (array_key_exists($sort, $sort_list)) {
                      $sort_sql = $sort_list[$sort];
                    } 
                    else {
                      $sort_sql = reset($sort_list);
                    }
                    /* запрос при отсутствии параметров поиска */
                    if($_COOKIE['searchtext'] == '') {
                      $sql = "SELECT products.*, publishers.publisher_name, group_concat(genres.genre_name), authors.author_name, stock.stock_quantity
                              FROM products
                              LEFT JOIN publishers
                              ON publishers.publisher_id=products.publisher_id
                              LEFT JOIN products_genres
                              ON products_genres.product_id=products.product_id
                              LEFT JOIN genres 
                              ON genres.genre_id=products_genres.genre_id
                              LEFT JOIN products_authors 
                              ON products_authors.product_id=products.product_id
                              LEFT JOIN authors
                              ON authors.author_id=products_authors.author_id
                              LEFT JOIN stock
                              ON stock.stock_id=products.product_id
                              GROUP BY products.product_id, products_authors.author_id
                              ORDER BY $sort_sql";
                    }
                    /* запрос при наличии параметров поиска */
                    else {
                      $serachtype = $_COOKIE['serachtype'];
                      $searchtext =  $_COOKIE['searchtext'];
                      $sql = "SELECT products.*, publishers.publisher_name, group_concat(genres.genre_name), authors.author_name, stock.stock_quantity
                              FROM products
                              LEFT JOIN publishers
                              ON publishers.publisher_id=products.publisher_id
                              LEFT JOIN products_genres
                              ON products_genres.product_id=products.product_id
                              LEFT JOIN genres 
                              ON genres.genre_id=products_genres.genre_id
                              LEFT JOIN products_authors 
                              ON products_authors.product_id=products.product_id
                              LEFT JOIN authors
                              ON authors.author_id=products_authors.author_id
                              LEFT JOIN stock
                              ON stock.stock_id=products.product_id
                              WHERE $serachtype like '%$searchtext%'
                              GROUP BY products.product_id, products_authors.author_id
                              ORDER BY $sort_sql";
                    }
                    /* Запрос в БД */
                    $dbh = new PDO('mysql:dbname=knizhnik_db;host=localhost', 'root', 'root');
                    $sth = $dbh->prepare($sql);
                    $sth->execute();
                    $list = $sth->fetchAll(PDO::FETCH_ASSOC);
                    /* Функция вывода ссылок */
                    function sort_link_th($title, $a, $b) {
                      $sort = @$_GET['sort'];
                      if ($sort == $a) {
                        return '<a class="active" href="?sort=' . $b . '">' . $title . ' <i>▲</i></a>';
                      } elseif ($sort == $b) {
                        return '<a class="active" href="?sort=' . $a . '">' . $title . ' <i>▼</i></a>';  
                      } else {
                        return '<a href="?sort=' . $a . '">' . $title . '</a>';  
                      }
                    }
                  }
                ?>
                <!-- Вывод таблицы -->
                <tr>
                  <th>№</th>
                  <th><?php echo sort_link_th('Артикул', 'code_asc', 'code_desc'); ?></th>
                  <th><?php echo sort_link_th('Штрихкод', 'barcode_asc', 'barcode_desc'); ?></th>
                  <th><?php echo sort_link_th('Наименование', 'product_name_asc', 'product_name_desc'); ?></th>
                  <th><?php echo sort_link_th('Издатель', 'publisher_name_asc', 'publisher_name_desc'); ?></th>
                  <th><?php echo sort_link_th('Год издания', 'year_of_publishing_asc', 'year_of_publishing_desc'); ?></th>
                  <th><?php echo sort_link_th('Автор', 'author_name_asc', 'author_name_desc'); ?></th>
                  <th><?php echo sort_link_th('Жанр', 'group_concat(genres.genre_name)_asc', 'group_concat(genres.genre_name)_desc'); ?></th>
                  <th><?php echo sort_link_th('Цена продажи', 'sell_price_asc', 'sell_price_desc'); ?></th>
                  <th><?php echo sort_link_th('Кол-во на складе', 'stock_quantity_asc', 'stock_quantity_desc'); ?></th>
                </tr>
                <?php $count=1; foreach ($list as $row): ?>
                <tr>
                  <td><?php echo $count; $count=$count+1; ?></td>
                  <td><?php echo $row["code"] ?></td>
                  <td><?php echo $row["barcode"] ?></td>
                  <td><?php echo $row["product_name"] ?></td>
                  <td><?php echo $row["publisher_name"] ?></td>
                  <td><?php echo $row["year_of_publishing"] ?></td>
                  <td><?php echo $row["author_name"] ?></td>
                  <td><?php echo $row["group_concat(genres.genre_name)"] ?></td>
                  <td><?php echo $row["sell_price"],' ₽' ?></td>
                  <td><?php echo $row["stock_quantity"] ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
          </div>
        </div>
      </main>
      <footer>
        <div class="footer-content">
          <div class="footer-box footer-box-right">
            <div>Разработано Анисимовым Максимом</div>
            <div>Компания Печатный Мир г. Сургут</div>
          </div>
        </div>
      </footer>
      <script>
        // скрипт для липкой навигации
        $(document).ready(function () {
            const nav = $('#nav');
            const navOffset = nav.offset().top;
            const navHeight = nav.height();
            $(window).scroll(function(){
                const scrolled = $(this).scrollTop();
                if (scrolled > navOffset) {
                    $('#container').addClass('nav-fixed');
                    $('#header').css({marginBotton: navHeight});
                }
                else if (scrolled < navOffset) {
                    $('#container').removeClass('nav-fixed');
                    $('#header').css({marginBotton: 0});
                }
            });
        });
      </script>
    </div>
  <?php
    endif;
  ?>
</body>
</html>