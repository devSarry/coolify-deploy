import './bootstrap';



import { directive } from '@wireui/alpinejs-hold-directive'
Alpine.directive('hold', directive)

window.Alpine = Alpine

Alpine.start()
