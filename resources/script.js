function addNewWeek() {
  let r = confirm('¿Deseas agregar nueva semana?');
  if(r) {
    fetch('actions/addNewWeek.php').then(function(response) { location.reload(); })
  }
}


function addHours(hourInput) {
  let formData = new FormData();

  formData.append('cell', hourInput.name);
  if(hourInput.value != '')
    formData.append('hours', hourInput.value);
  fetch('actions/addHours.php', {
    method: 'POST',
    body: formData
  }).then(() => {
    hourInput.style.background = "#DDD";
  }).catch(() => {
    hourInput.style.background = "#ff4c4c";
  });
}