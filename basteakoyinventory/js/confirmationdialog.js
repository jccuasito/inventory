function confirmExit(event) {
    const confirmation = confirm('Are you sure you want to exit? You will be logged out.');
    if (!confirmation) {
        event.preventDefault();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const logoutLinks = document.querySelectorAll('.logout a');
    logoutLinks.forEach(link => {
        link.addEventListener('click', confirmExit);
    });
});