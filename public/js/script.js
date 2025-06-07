// Page navigation functionality
function showPage(pageType) {
    // Hide all pages
    document.querySelectorAll('.page').forEach(page => {
        page.classList.remove('active');
    });
    
    // Remove active class from all nav buttons
    document.querySelectorAll('.nav-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected page
    document.getElementById(pageType + '-page').classList.add('active');
    
    // Add active class to selected nav button
    event.target.classList.add('active');
    
    // Reset forms and messages
    resetForms();
}

// Reset all forms and error states
function resetForms() {
    document.querySelectorAll('form').forEach(form => form.reset());
    document.querySelectorAll('.error-message').forEach(msg => msg.classList.remove('show'));
    document.querySelectorAll('.success-message').forEach(msg => msg.classList.remove('show'));
    document.querySelectorAll('.form-input').forEach(input => {
        input.classList.remove('error', 'success');
    });
}

// Password toggle functionality
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const button = input.nextElementSibling;
    
    if (input.type === 'password') {
        input.type = 'text';
        button.textContent = '👁️‍🗨️'; 
    } else {
        input.type = 'password';
        button.textContent = '👁';
    }
}

// Field validation
function validateField(field) {
    const errorElement = document.getElementById(field.name + '_error') || 
                        document.getElementById(field.id + '_error');
    let isValid = true;
    let errorMessage = '';

    // Clear previous states
    field.classList.remove('error', 'success');
    if (errorElement) errorElement.classList.remove('show');

    // Skip validation for empty fields on blur
    if (field.value.trim() === '') {
        return true;
    }

    // Validate based on field type
    const fieldName = field.name || field.id.replace('login_', '').replace('forgot_', '');
    
    switch (fieldName) {
        case 'first_name':
        case 'last_name':
            if (field.value.trim().length < 2) {
                errorMessage = 'Must be at least 2 characters';
                isValid = false;
            }
            break;

        case 'email':
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(field.value)) {
                errorMessage = 'Please enter a valid email address';
                isValid = false;
            }
            break;

        case 'password':
            if (field.value.length < 8) {
                errorMessage = 'Password must be at least 8 characters';
                isValid = false;
            }
            break;
    }

    // Apply validation results
    if (!isValid && errorElement) {
        field.classList.add('error');
        errorElement.textContent = errorMessage;
        errorElement.classList.add('show');
    } else if (field.value.trim() !== '') {
        field.classList.add('success');
    }

    return isValid;
}

// Form submission handler
function handleFormSubmission(formId, buttonId, successMessage) {
    const form = document.getElementById(formId);
    const button = document.getElementById(buttonId);
    
    if (!form || !button) return;
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate all required fields
        const inputs = form.querySelectorAll('.form-input[required]');
        let allValid = true;
        
        inputs.forEach(input => {
            if (input.value.trim() === '') {
                input.classList.add('error');
                const errorElement = document.getElementById(input.name + '_error') || 
                                   document.getElementById(input.id + '_error');
                if (errorElement) {
                    errorElement.textContent = 'This field is required';
                    errorElement.classList.add('show');
                }
                allValid = false;
            } else if (!validateField(input)) {
                allValid = false;
            }
        });

        if (!allValid) return;

        // Show loading state
        const originalText = button.textContent;
        const loadingText = formId === 'loginForm' ? 'Signing In...' : 
                           formId === 'registerForm' ? 'Creating Account...' : 'Sending Link...';
        
        button.innerHTML = '<span class="spinner"></span>' + loadingText;
        button.disabled = true;
        
        // Simulate API call
        setTimeout(() => {
            button.textContent = originalText;
            button.disabled = false;
            
            if (formId === 'forgotForm') {
                document.getElementById('reset-success').classList.add('show');
            } else {
                alert(successMessage);
            }
        }, 2000);
    });
}

// Add input event listeners
function setupInputHandlers() {
    document.querySelectorAll('.form-input').forEach(input => {
        // Validate on blur
        input.addEventListener('blur', (e) => validateField(e.target));
        
        // Clear errors on input if field was previously invalid
        input.addEventListener('input', function() {
            if (this.classList.contains('error')) {
                validateField(this);
            }
        });
        
        // Focus effects
        input.addEventListener('focus', function() {
            this.style.transform = 'translateY(-1px)';
        });
        
        input.addEventListener('blur', function() {
            this.style.transform = 'translateY(0)';
        });
    });
}

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Setup input handlers
    setupInputHandlers();
    
    // Initialize form handlers
    handleFormSubmission('registerForm', 'registerBtn', 'Account created successfully!');
    handleFormSubmission('loginForm', 'loginBtn', 'Welcome back!');
    handleFormSubmission('forgotForm', 'forgotBtn', 'Reset link sent!');
    
    // Handle responsive layout
    updateResponsiveLayout();
    window.addEventListener('resize', updateResponsiveLayout);
});

// Responsive layout updates
function updateResponsiveLayout() {
    const formRows = document.querySelectorAll('.form-row');
    
    formRows.forEach(row => {
        if (window.innerWidth <= 768) {
            row.style.gridTemplateColumns = '1fr';
            row.style.gap = '16px';
        } else {
            row.style.gridTemplateColumns = '1fr 1fr';
            row.style.gap = '12px';
        }
    });
}