setInterval(() => {
    fetch('?page=notification')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Erreur de notification:', data.error);
            } else if (data.length > 0) {
                data.forEach(notification => {
                    alert(notification.message);
                });
            }
        })
        .catch(error => console.error('Erreur lors de la récupération des notifications:', error));
}, 30000);