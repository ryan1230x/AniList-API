// Forms
const register_form = document.getElementById('register_form');

// helper text
const username_helper_text = document.getElementById('username_helper_text');
const password1_helper_text = document.getElementById('password1_helper_text');
const password2_helper_text = document.getElementById('password2_helper_text');

// helper array
const helperArray = [username_helper_text, password1_helper_text, password2_helper_text];

// Inputs
const username = document.getElementById('username');
const password1 = document.getElementById('password1');
const password2 = document.getElementById('password2');

// input array
const inputArray = [username, password1, password2];

// REGEX Expressions
const stringPattern = "^[a-z A-Z 0-9-,./€]+$";

// form styles
const red = '#dc3545';
const green = '#28a745';

// Helper functions

// Reset functions
const resetEverything = () => {
    inputArray.forEach(item => {
        item.value = null;
        item.classList.remove('is-invalid');
        item.classList.remove('is-valid');
    });
    helperArray.forEach(item => item.textContent = 'Required(*)');
};

const clearInput = () => {
    inputArray.forEach(item => item.value = null);
};

// Success functions
const setIndividualSuccess = (element) => {  
    element.classList.remove('is-invalid');    
    element.classList.add('is-valid');
    element.nextSibling.nextSibling.innerHTML = 'Looks Good!';
    element.nextSibling.nextSibling.style.color = green;
};

// Error functions
const setEmptyError = () => {
    helperArray.forEach(item => {
        item.style.color = red;
        item.textContent = 'Please fill in fields correctly';
    });
};

const setIndividualError = element => {
    element.classList.remove('is-valid');
    element.classList.add('is-invalid');
    element.nextSibling.nextSibling.innerHTML = 'Invalid Characters';
    element.nextSibling.nextSibling.style.color = red;
};

const setStringError = e => {
    let target = e.target || e.srcElement;
    !target.value.match(stringPattern) ? setIndividualError(target) : setIndividualSuccess(target);
};

// Add multiple classes
const addClasses = (targetElement, ...classes) => {
    const target = document.querySelector(targetElement);
    for(let i = 0; i < classes.length; i++) {
        target.classList.add(...classes[i]);
    }
};

username.addEventListener('keyup', setStringError, false);
password1.addEventListener('keyup', setStringError, false);
password2.addEventListener('keyup', setStringError, false);