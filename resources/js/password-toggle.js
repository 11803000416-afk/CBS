/**
 * Password Toggle Functionality
 * Adds show/hide password toggle to password input fields
 */

document.addEventListener('DOMContentLoaded', function() {
  // Find all password toggle buttons
  const toggleButtons = document.querySelectorAll('[data-password-toggle]');

  toggleButtons.forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      
      const passwordField = this.getAttribute('data-password-toggle');
      const inputField = document.querySelector(`input[name="${passwordField}"]`);
      
      if (!inputField) return;

      // Toggle input type between password and text
      if (inputField.type === 'password') {
        inputField.type = 'text';
        this.innerHTML = `
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
          </svg>
        `;
        this.title = 'Hide password';
      } else {
        inputField.type = 'password';
        this.innerHTML = `
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-4.803m5.596-3.856a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8m0 8l6.364 2.121M9.172 9.172L5.964 5.964M15.828 9.172l3.208-3.208"></path>
          </svg>
        `;
        this.title = 'Show password';
      }
    });
  });
});

export default { init: () => {} };
