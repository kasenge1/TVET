import './bootstrap';

// Import Bootstrap JS
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// Import SweetAlert2
import Swal from 'sweetalert2';
window.Swal = Swal;

// Import SweetAlert2 helpers
import './sweetalert-helpers';

// Import Alpine.js for interactivity
import Alpine from 'alpinejs';
window.Alpine = Alpine;

Alpine.start();
