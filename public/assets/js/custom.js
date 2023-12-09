function toast(message, status) {
    if (status == "info") {
        background = "linear-gradient(to right, #00b09b, #96c93d)";
    } else if (status == "danger") {
        background = "linear-gradient(to right, #FD8D14, #C51605)";
    } else if (status == "success") {
        background = "linear-gradient(to right, #7A9D54, #557A46)";
    }
    Toastify({
        text: message,
        className: status,
        style: {
            background: background,
        },
    }).showToast();
}

async function sweetAlert(status, title, message, confirmButton) {
    const response = await Swal.fire({
        title: title,
        text: message,
        icon: status,
        showCancelButton: true,
        confirmButtonColor: "#282c34",
        confirmButtonText: confirmButton,
    });

    return response;
}

function alert(status, message){
    if(status == true){
        icon = 'success';
    }else if(status == false){
        icon = 'error';
    }
    Swal.fire({
        icon: icon,
        title: "Notification",
        text: message,
      });
}
