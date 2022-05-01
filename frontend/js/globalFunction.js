// show alert
function showAlert(type, msg) {
  console.log("alert", type, msg);
  $(".myAlert-top").hide();
  let alertComponent = document.getElementById("alert_div");
  if (type === "success") {
    alertComponent.className = "myAlert-top alert alert-success";
  } else if (type === "danger") {
    alertComponent.className = "myAlert-top alert alert-danger";
  }
  alertComponent.innerHTML = `${msg}`;
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

function copyDivToClipboard() {
  var range = document.createRange();
  range.selectNode(document.getElementById("copy_id_1"));
  window.getSelection().removeAllRanges(); // clear current selection
  window.getSelection().addRange(range); // to select text
  document.execCommand("copy");
  window.getSelection().removeAllRanges(); // to deselect
  showAlert('success',"Copied!")
}
