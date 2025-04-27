const showDeleteModal = (propertyId, propertyName) => {
    const modal = document.getElementById('deleteModal');
    const confirmDeleteButton = document.getElementById('confirmDelete');
    const propertyNameElement = document.getElementById('propertyName');

    modal.style.display = "block";

    propertyNameElement.innerText = propertyName;

    confirmDeleteButton.onclick = () => {
        deleteProperty(propertyId);
    };
};

const closeModal = () => {
    document.getElementById('deleteModal').style.display = "none";
};

const deleteProperty = (propertyId) => {
    const url = `/propriedades/deletar?id=${propertyId}`;
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ id: propertyId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`deleteForm-${propertyId}`).parentElement.remove();
            closeModal(); 
            window.location.reload();        
        } else {
            alert('Erro ao excluir a propriedade.');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao tentar excluir a propriedade.');
    });
};

window.onclick = (event) => {
    const modal = document.getElementById('deleteModal');
    if (event.target === modal) {
        closeModal();
    }
};
