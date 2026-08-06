<!-- el bashboard tendrá 2 bloques: .silder, .content -->
<div class="slider">
  <div class="logoContent">
    <div class="logo">
      <i class="bx bxl-c-plus-plus"></i>
      <span class="logoName">Davilo</span>
    </div>
    <i class="bx bx-menu" id="btn"></i>
  </div>
  <ul class="navList">
    <li>
      <a href="#">
        <i class="bx bx-grid-alt"></i>
        <span class="linksName">Dashboard</span>
      </a>
      <span class="tooltip">Dashboard</span>
    </li>
    <li>
      <a href="#">
        <i class="bx bx-user"></i>
        <span class="linksName">Usuarios</span>
      </a>
      <span class="tooltip">Usuarios</span>
    </li>
    <li>
      <a href="">
        <i class="bx bx-chat"></i>
        <span class="linksName">Mensajes</span>
      </a>
      <span class="tooltip">Mensajes</span>
    </li>
    <li>
      <a href="">
        <i class="bx bx-cart"></i>
        <span class="linksName">Productos</span>
      </a>
      <span class="tooltip">Productos</span>
    </li>
    <!-- configuración -->
    <li>
      <a href="">
        <i class="bx bx-cog"></i>
        <span class="linksName">configuración</span>
      </a>
      <span class="tooltip">configuración</span>
    </li>
  </ul>
  <div class="profile">
    <div class="profile-details">
      <!-- verificar si el usuario tiene foto -->
      <?php
        if($foto_conectado):
      ?>
      <img src="../imgCargadas/<?php echo $foto_conectado ?>" alt="profileImg" />
      <?php
        else:
      ?>
      <img src="../img/imagen.png" alt="profileImg" />
      <?php
        endif;
      ?>
      <div class="name_job">
        <div class="name">
          <?php echo mb_convert_case($usuario_conectado, MB_CASE_TITLE, "UTF-8"); ?>
        </div>
        <!-- <div class="job">Web Developer</div> -->
      </div>
    </div>
    <a href="../controlador/logOut.php">
      <i class="bx bx-log-out" id="log_out"></i>
    </a>
  </div>
</div>
