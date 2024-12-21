document.addEventListener('DOMContentLoaded', function() {
    const deleteForms = document.querySelectorAll('.delete-form');
    
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            
            const articleTitle = this.getAttribute('data-article-title');
            const linkTitle = this.getAttribute('data-link-title');
            
            let confirmMessage;
            if (articleTitle) {
                confirmMessage = `Êtes-vous sûr de vouloir supprimer l'article "${articleTitle}" ?`;
            } else if (linkTitle) {
                confirmMessage = `Êtes-vous sûr de vouloir supprimer le lien "${linkTitle}" ?`;
            }
            
            if (confirmMessage && confirm(confirmMessage)) {
                this.submit();
            }
        });
    });
});