document.addEventListener('DOMContentLoaded', function() {
    const timeSelect = document.getElementById('time');
    const bookingForm = document.getElementById('bookingForm');
    const messageParagraph = document.getElementById('message');

    // Optionen von 9:00 bis 20:00 Uhr in 30-Minuten-Intervallen hinzufügen
    for (let hour = 9; hour <= 20; hour++) {
        for (let minutes = 0; minutes < 60; minutes += 30) {
            let timeString = `${String(hour).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
            let option = document.createElement('option');
            option.value = timeString;
            option.textContent = timeString;
            timeSelect.appendChild(option);
        }
    }

    bookingForm.addEventListener('submit', function(event) {
        event.preventDefault();
        
        const formData = {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            date: document.getElementById('date').value,
            time: document.getElementById('time').value
        };

        fetch('api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            messageParagraph.textContent = data.message;
            if (data.status === 'success') {
                bookingForm.reset();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            messageParagraph.textContent = 'Es gab ein Problem beim Senden Ihrer Nachricht. Bitte versuchen Sie es später erneut.';
        });
    });
});
