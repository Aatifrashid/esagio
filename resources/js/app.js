import './bootstrap';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import { planBuilder } from './plan-builder';

Alpine.plugin(collapse);
window.Alpine = Alpine;
Alpine.start();

window.planBuilder = planBuilder;
