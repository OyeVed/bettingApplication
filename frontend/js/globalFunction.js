// show alert
function showAlert(type, msg) {
  console.log('alert',type,msg)
  $(".myAlert-top").hide();
  let alertComponent = document.getElementById("alert_div");
  if (type === "success") {
    alertComponent.className = "myAlert-top alert alert-success";
  } else if (type === "danger") {
    alertComponent.className = "myAlert-top alert alert-danger";
  }
  alertComponent.innerHTML = `<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>${msg}`;
  $(".myAlert-top").show();
  setTimeout(function () {
    $(".myAlert-top").hide();
  }, 3000);
}

function startLoader() {
  $("div.spanner").addClass("show");
  $("div.overlay").addClass("show");
}

function endLoader() {
  $("div.spanner").removeClass("show");
  $("div.overlay").removeClass("show");
}
