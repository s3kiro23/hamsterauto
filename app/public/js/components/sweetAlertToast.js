function sweetToast(){
    return toastMixin = Swal.mixin({
        toast: true,
        icon: 'success',
        title: 'General Title',
        animation: false,
        position: 'center',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
      }); 
}

function sweetAlertSession(msg){
  let sessionDisconnect = setTimeout(() => {
    sessionEnding()
  }, 30000);
let timerInterval;
  swal.fire({
    confirmButtonColor: "#62C462",
    confirmButtonText: "Oui",
    icon: 'question',
    title: msg,
    html: 'Déconnexion dans <strong id="timer"></strong> secondes.',
    timer: 30000,
    didOpen: () => {
      timerInterval = setInterval(() => {
        swal.getHtmlContainer().querySelector('#timer')
          .textContent = Math.ceil(swal.getTimerLeft() / 1000)
      }, 100)
    },
    willClose: () => {
      clearInterval(timerInterval)
    }
  }).then((result) => {
    if (result.isConfirmed) {
        clearTimeout(sessionDisconnect);
        sessionExtending();               
        
    }else{
        sessionEnding();
    } 
    result.dismiss === swal.DismissReason.timer
});
}
