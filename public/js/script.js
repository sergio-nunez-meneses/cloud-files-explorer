const FORM_UPLOAD = getID('file-upload'),
  FORM_UPDATE = getID('file-update');

FORM_UPLOAD.onsubmit = confirmAction;
if (FORM_UPDATE !== null) {
  FORM_UPDATE.onsubmit = confirmAction;
}

function getID(id) {
  return document.getElementById(id);
}

function ajaxResponse() {
  // let response = JSON.parse(this.responseText);
  console.log(this.responseText);
}

function ajaxSend(oFormElement) {
  if (!oFormElement.action) {
    return;
  }

  let oReq = new XMLHttpRequest();
  oReq.onload = ajaxResponse;

  if (oFormElement.method.toLowerCase() === 'post') {
    oReq.open('post', oFormElement.action);
    oReq.send(new FormData(oFormElement));
  }
}

function confirmAction() {
  if (confirm("Are you sure of what you're about to do ?") == true) return true;
  else return false;
}

function confirmDelete() {
  if (confirm('Delete file? Really?') == true) return true;
  else return false;
}
