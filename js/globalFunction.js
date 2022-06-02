function startLoader() {
  $("div.spanner").addClass("show");
  $("div.overlay").addClass("show");
}

function endLoader() {
  $("div.spanner").removeClass("show");
  $("div.overlay").removeClass("show");
}

function authenticate() {
  let isLoggedIn = localStorage.getItem("isLoggedIn");
  if (isLoggedIn === "false" || isLoggedIn === null) {
    location.href = "index.html";
  }
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
function deleteCookie(name) {
  document.cookie = name + "=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;";
}
function signOut() {
  startLoader();
  let cookieData = getCookie("jwt");
  axiosInstance
    .post("logout", {
      phone_number: cookieData?.user_phonenumber,
    })
    .then(
      (response) => {
        endLoader();
        if (response.status === 200) {
          localStorage.setItem("isLoggedIn", "false");
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

function calculateTimeRemain(time) {
  var hourDiff =
    new Date("01/01/2007 " + time).getHours() - new Date().getHours();
  var minDiff =
    new Date("01/01/2007 " + time).getMinutes() - new Date().getMinutes();
  return `${hourDiff} hrs ${minDiff} min`;
}

const validateEmail = (email) => {
  var re = /\S+@\S+\.\S+/;
  return re.test(email);
};

const getDateInWord = (month) => {
  switch (month) {
    case 1:
      return "Jan";
      break;
    case 2:
      return "Feb";
      break;
    case 3:
      return "Mar";
      break;
    case 4:
      return "Apr";
      break;
    case 5:
      return "May";
      break;
    case 6:
      return "Jun";
      break;
    case 7:
      return "Jul";
      break;
    case 8:
      return "Aug";
      break;
    case 9:
      return "Sep";
      break;
    case 10:
      return "Oct";
      break;
    case 11:
      return "Nov";
    case 12:
      return "Dec";
    default:
      break;
  }
};
