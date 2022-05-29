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
  credentials: "include",
  withCredentials: true,
});

function parseJwt(token) {
  var base64Url = token.split(".")[1];
  var base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/");
  var jsonPayload = decodeURIComponent(
    atob(base64)
      .split("")
      .map(function (c) {
        return "%" + ("00" + c.charCodeAt(0).toString(16)).slice(-2);
      })
      .join("")
  );

  return JSON.parse(jsonPayload);
}

function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  let cookieNow = "";
  let cookieData = null;
  if (parts.length === 2) cookieNow = parts.pop().split(";").shift();
  if (cookieNow !== "") {
    cookieData = parseJwt(cookieNow);
    return cookieData;
  }
  return cookieData;
}

function signOut() {
  startLoader();
  let cookieData = getCookie("jwt");
  axiosInstance
    .post("logout", {
      phone_number: cookieData?.userName,
    })
    .then(
      (response) => {
        endLoader();
        if (response.status === 200) {
          location.href = "index.html";
        } else {
          $.notify(
            {
              title: "",
              message: response.data?.msg,
              icon: "fa fa-times",
            },
            {
              type: "danger",
            }
          );
        }
      },
      (error) => {
        endLoader();
        console.log("error", error);
        $.notify(
          {
            title: "",
            message: `Something went wrong! Please try again.`,
            icon: "fa fa-times",
          },
          {
            type: "danger",
          }
        );
      }
    );
}

function convertTime(time) {
  return new Date("1970-01-01T" + time + "Z").toLocaleTimeString("en-US", {
    timeZone: "UTC",
    hour12: true,
    hour: "numeric",
    minute: "numeric",
  });
}
