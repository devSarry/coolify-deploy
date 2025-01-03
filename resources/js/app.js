import './bootstrap';
import 'flowbite';
import {initFlowbite} from "flowbite";

import.meta.glob([
    '../images/**',
]);

// Solution found https://github.com/themesberg/flowbite/issues/691#issuecomment-1776056038 to fix the
// issue with the dropdown not opening after a page navigation
document.addEventListener('livewire:navigated', () => {
    initFlowbite();
})





