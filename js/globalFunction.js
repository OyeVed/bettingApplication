
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
  showAlert("success", "Copied!");
}

function navigate(path) {
  location.href = path;
}

const axiosInstance = axios.create({
  baseURL: `/bettingApplication/backend/`,
  credentials: 'include',
  withCredentials: true,
})