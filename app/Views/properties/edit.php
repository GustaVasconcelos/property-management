<?php
ob_start();
?>

<div class="container__title">
    <h1 class="page__title">Editar Propriedade</h1>
    <a href="/propriedades" class="btn btn--back">
        <i class="ri-arrow-left-line"></i> Voltar
    </a>
</div>

<?php if (isset($_SESSION['errors']) && is_array($_SESSION['errors'])) : ?>
    <div class="message-box message-box--error">
        <?php foreach ($_SESSION['errors'] as $msg) : ?>
            <p><?= $msg ?></p>
        <?php endforeach; ?>
        <?php unset($_SESSION['errors']); ?>
    </div>
<?php elseif (isset($message)) : ?>
    <div class="message-box"><?= $message ?></div>
<?php endif; ?>

<div class="property-form">
    <form action="/propriedades/editar?id=<?= $property['id'] ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Nome da Propriedade:</label>
            <div class="input-with-icon">
                <i class="ri-home-line"></i>
                <input type="text" name="name" id="name" value="<?= $property['name'] ?>" placeholder="Nome da Propriedade" class="input-field">
            </div>
        </div>

        <div class="form-group">
            <label for="address">Endereço:</label>
            <div class="input-with-icon">
                <i class="ri-map-pin-line"></i>
                <input type="text" name="address" id="address" value="<?= $property['address'] ?>" placeholder="Endereço da Propriedade" class="input-field">
            </div>
        </div>

        <div class="form-group">
            <label for="image">Imagem:</label>
            <div class="input-with-icon">
                <i class="ri-image-line"></i>
                <input type="file" name="image" id="image" class="input-field">
            </div>
        </div>

        <?php if (!empty($property['image'])) : ?>
            <div class="image-preview">
                <p>Imagem Atual:</p>
                <div class="image-preview__wrapper">
                    <img src="/<?= $property['image'] ?>" alt="Imagem da propriedade" class="property-image-preview">
                    <div class="image-preview__info">
                        <p><strong>Imagem atual da propriedade</strong></p>
                        <p><?= basename($property['image']) ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="form-actions">
            <button type="submit" class="btn btn--primary">
                <i class="ri-save-line"></i> Salvar Alterações
            </button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();

include '/var/www/html/app/Views/layout.php';
?>
