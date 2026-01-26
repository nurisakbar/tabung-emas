import './bootstrap';

import Alpine from 'alpinejs';
import { Chart, registerables } from 'chart.js';
import * as bootstrap from 'bootstrap';

// Register Chart.js components
Chart.register(...registerables);

window.Alpine = Alpine;
window.Chart = Chart;
window.bootstrap = bootstrap;

// Initialize Bootstrap dropdowns and collapse
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all dropdowns
    const dropdownElementList = document.querySelectorAll('.dropdown-toggle');
    dropdownElementList.forEach(function (dropdownToggleEl) {
        new bootstrap.Dropdown(dropdownToggleEl);
    });

    // Initialize collapse (for hamburger menu)
    const collapseElementList = document.querySelectorAll('.collapse');
    collapseElementList.forEach(function (collapseEl) {
        new bootstrap.Collapse(collapseEl, {
            toggle: false
        });
    });
});

Alpine.start();
