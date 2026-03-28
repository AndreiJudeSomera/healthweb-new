import "./bootstrap";
import Alpine from "alpinejs";
import $ from "jquery";
import toastr from "toastr";
import "toastr/build/toastr.min.css"; // important for styling

window.$ = window.jQuery = $;
window.Alpine = Alpine;
window.toastr = toastr; // ✅ make it global

Alpine.start();

toastr.options = {
  closeButton: true,
  debug: true,
  newestOnTop: true,
  progressBar: true,
  positionClass: "toast-bottom-right",
  preventDuplicates: true,
  onclick: null,
  showDuration: "300",
  hideDuration: "1000",
  timeOut: "5000",
  extendedTimeOut: "1000",
  showEasing: "swing",
  hideEasing: "linear",
  showMethod: "fadeIn",
  hideMethod: "fadeOut",
};

// ✅ AUTO-IMPORT EVERYTHING
// import.meta.glob('./components/**/*.js', { eager: true });