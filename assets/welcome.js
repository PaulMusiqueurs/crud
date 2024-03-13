// welcome.js

document.addEventListener('DOMContentLoaded', function() {
    var deleteButtons = document.querySelectorAll('.delete-btn');

    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function(event) {
            var confirmDelete = confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');
            if (!confirmDelete) {
                event.preventDefault();
            }
        });
    });
});