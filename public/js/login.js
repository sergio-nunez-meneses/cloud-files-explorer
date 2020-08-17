const SIGN_TAB = getId('sign-in-tab'),
  SIGN_IN_FORM = getId('sign-in-form'),
  SIGN_UP_FORM = getId('sign-up-form'),
  CHARS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_-+=`~,<>.[]:|',
  GEN_BTN = getId('generatorButton'),
  RAND_PASS = getSel('.random-password');

SIGN_UP_FORM.onsubmit = validateForm;

function randPassGen() {
  let shuffleChars = CHARS.split('').sort(function() { return 0.5 - Math.random() }).join('');

  return shuffleChars.substring(0, (CHARS.length / 4));
}

function displayForms() {
  if (SIGN_UP_FORM.classList.contains('hidden')) {
    SIGN_UP_FORM.classList.remove('hidden');
    SIGN_IN_FORM.classList.add('hidden');
    SIGN_TAB.innerHTML = 'sign in';
  } else {
    SIGN_IN_FORM.classList.remove('hidden');
    SIGN_UP_FORM.classList.add('hidden');
    SIGN_TAB.innerHTML = 'sign up';
  }
}

function validateForm() {
  let user = getName('username').value,
    pass = getName('password').value,
    confirmPass = getName('confirm-password').value,
    displayErrors = getId('displayErrors'),
    error = false,
    errorMsg = '';

  if (user.length == 0) {
    error = true;
    errorMsg += '<p>username cannot be empty</p>';
  } else if (user.length < 6) {
    error = true;
    errorMsg += '<p>username must contain more than 6 characters</p>';
  }

  if (pass.length == 0) {
    error = true;
    errorMsg += '<p>password cannot be empty</p>';
  } else if (pass.length < 7) {
    error = true;
    errorMsg += '<p>password must contain more than 7 characters</p>';
  }

  if (confirmPass.length == 0) {
    error = true;
    errorMsg += '<p>second password cannot be empty</p>';
  } else if (confirmPass.length < 7) {
    error = true;
    errorMsg += '<p>second password must contain more than 7 characters</p>';
  }

  if (pass !== confirmPass) {
    error = true;
    errorMsg += '<p>passwords do not match</p>';
  }

  if (!error) {
    SIGN_UP_FORM.submit();
  } else {
    displayErrors.innerHTML = errorMsg;
    return false;
  }
}

SIGN_TAB.addEventListener('click', displayForms);
GEN_BTN.addEventListener('click', () => {
  let genPass = randPassGen();
  for (let i = 0; i < RAND_PASS.length; i++) {
    RAND_PASS[i].value = genPass;
  }
});
