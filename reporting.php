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
        setcookie('serachtype', $serachtype, time() - 3600, "/");
        setcookie('stocksearchtext', $stocksearchtext, time() - 3600, "/");
        setcookie('ordersearchtext', $ordersearchtext, time() - 3600, "/");
        setcookie('consumption_type', $consumption_type, time() - 3600, "/");
        if($_COOKIE['worker_id'] == ''): //если нет cookie   
          header('location: /');
        else: //если есть cookie
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
        <!-- страница отчетность -->
        <div class="container contain" id="container">
          <header id="header">
              <img src="images/logo_wide.png" class="logo"/>
              <h1>Книги для всей семьи!</h1>
              <div class="header-worker">
                <div class="header-box-left"><a href="XXX.php" class="header-name"><?php echo $worker_name; ?> — <?php echo $worker_role; ?></a></div>
                <div class="header-box-right"><a href="php/exit.php" class="logout">Выйти</a></div>
              </div>
          </header>
          <nav id="nav">
            <div class="nav-content nav-scroll">
              <ul class="nav-menu">
                <li><a href="/">Склад</a></li>
                <li><a href="supplies.php">Приемка товара</a></li>
                <li><a href="consumption.php">Расход</a></li>
                <li><a href="orders.php">Заказы</a></li>
                <li><a href="reporting.php" class="current">Отчетность</a></li>
                <li><a href="info.php">Инфо</a></li>
              </ul>
            </div>
          </nav>
          <main>
            <div class="main-content">
              <form role="form" method="POST" action="">
                <div class="form-container form-search form-reporting-type">
                  <button type="submit" name="btn_reporting_type_personal" id="btn_reporting_type_personal" class="formbtn-search <?php if( $_COOKIE['reporting_type'] == 'personal') echo "formbtn-current" ?>">Личная</button>
                  <button type="submit" name="btn_reporting_type_general" id="btn_reporting_type_general" class="formbtn-search <?php if($_COOKIE['reporting_type'] == 'general') echo "formbtn-current" ?>">Общая</button>
                </div>
                <?php
                      function btn_reporting_type_personal()
                      {
                        setcookie('reporting_type', 'personal', time() + 3600, "/");
                        header("Refresh:0");
                      }
                      if(array_key_exists('btn_reporting_type_personal',$_POST)){
                        btn_reporting_type_personal();
                      }
                      function btn_reporting_type_general()
                      {
                        setcookie('reporting_type', 'general', time() + 3600, "/");
                        header("Refresh:0");
                      }
                      if(array_key_exists('btn_reporting_type_general',$_POST)){
                        btn_reporting_type_general();
                      }
                  ?>
              </form>
              <?php if($_COOKIE['reporting_type'] == '') {
                } else {?>
                <form role="form" method="POST" action="" class="padding-eight">
                  <div class="form-container form-search form-reporting">  
                  Выберите период
                  <p class="ta-right">C
                  <input type="date" name="reporting-from" id="reporting-from"></p>
                  <p class="ta-right">по
                  <input type="date" name="reporting-to" id="reporting-to"></p> 
                  <button type="submit" name="btn_stock" id="btn_stock" class="formbtn-search">Отчет</button>
                  </div>
                  <?php
                    ?>
                </form>
              <?php } ?>
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