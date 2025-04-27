<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Propriedade</title>
    <link rel="stylesheet" href="/assets/styles/layout.css">
    <link rel="stylesheet" href="/assets/styles/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css">
</head>
<body>

    <header class="header" id="header">
         <div class="header__container">
            <a href="/propriedades" class="header__logo">
               <i class="ri-building-line"></i>
               <span>G&amp;N IMÓVEIS</span>
            </a>
            
            <button class="header__toggle" id="header-toggle">
               <i class="ri-menu-line"></i>
            </button>
         </div>
      </header>

      <nav class="sidebar" id="sidebar">
         <div class="sidebar__container">
            <div class="sidebar__content">
               <div>
                  <h3 class="sidebar__title">iMÓVEIS</h3>

                  <div class="sidebar__list">
                     <a href="/propriedades" class="sidebar__link active-link">
                        <i class="ri-building-line"></i>
                        <span>Página Inicial</span>
                     </a>
                  </div>
               </div>
            </div>

            <div class="sidebar__actions">
               <button>
                  <i class="ri-moon-clear-fill sidebar__link sidebar__theme" id="theme-button">
                     <span>Tema</span>
                  </i>
               </button>
            </div>
         </div>
      </nav>

      <main class="main container" id="main">
        <?php echo $content; ?>
      </main>
      

    <div class="content">
    </div>

</body>
<script src="/assets/js/layout.js"></script>
<script src="/assets/js/modal.js"></script>
</html>
