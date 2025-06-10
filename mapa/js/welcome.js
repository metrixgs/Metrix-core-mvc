document.addEventListener('DOMContentLoaded', () => {
    const welcomeOverlay = document.querySelector('.welcome-overlay');
    const enterButton = document.getElementById('enter-button');

    // Check if user has seen welcome screen
    const hasSeenWelcome = localStorage.getItem('hasSeenWelcome');
    
    if (hasSeenWelcome) {
        welcomeOverlay.style.display = 'none';
    } else {
        document.body.style.overflow = 'hidden';
    }

    enterButton.addEventListener('click', () => {
        welcomeOverlay.classList.add('fade-out');
        document.body.style.overflow = '';
        
        // Store that user has seen welcome screen
        localStorage.setItem('hasSeenWelcome', 'true');
        
        // Remove overlay after animation
        setTimeout(() => {
            welcomeOverlay.remove();
        }, 500);
    });
});