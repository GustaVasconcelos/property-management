<?php
ob_start(); 
?>

<div class="container__title">
    <h1 class="page__title">Propriedades</h1>
    <a href="/propriedades/criar" class="create-link">Criar Nova Propriedade</a>
</div>

<?php if (!empty($message)) : ?>
    <div class="message-box"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<?php if (isset($properties) && count($properties) > 0) : ?>
    <div class="properties-list">
        <?php foreach ($properties as $property) : ?>
            <div class="property-card">
                <div class="property-card__image">
                    <img src="/<?= $property['image'] ?>" alt="<?= $property['name'] ?>" class="property-card__img">
                </div>
                <div class="property-card__details">
                    <h3 class="property-card__name"><?= $property['name'] ?></h3>
                    <p class="property-card__address"><?= $property['address'] ?? 'Sem endereço' ?></p>
                </div>
                <div class="property-card__actions">
                    <a href="/propriedades/editar?id=<?= $property['id'] ?>" class="property-card__link">
                        <i class="ri-edit-line"></i> Editar
                    </a>
                    <a href="#" style="display:inline;" id="deleteForm-<?= $property['id'] ?>">
                        <button type="button" class="property-card__delete-btn" onclick="showDeleteModal(<?= $property['id'] ?>, '<?= $property['name'] ?>')">
                            <i class="ri-delete-bin-line"></i> Deletar
                        </button>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
    </div>

    <?php else : ?>
        <p class="no-data-message">Nenhuma propriedade encontrada.</p>
    <?php endif; ?>

<div id="deleteModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Tem certeza que deseja excluir esta propriedade?</h2>
        <p id="propertyName"></p>
        <div class="modal-actions">
            <button id="confirmDelete" class="btn btn--danger">Confirmar</button>
            <button class="btn btn--secondary" onclick="closeModal()">Cancelar</button>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

include '/var/www/html/app/Views/layout.php';
?>
