import Lenis from 'lenis';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';
import { DrawSVGPlugin } from 'gsap/DrawSVGPlugin';

gsap.registerPlugin(ScrollTrigger, SplitText, DrawSVGPlugin);

const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const hasHover = window.matchMedia('(hover: hover)').matches;

/* Lenis smooth scroll */
let lenis = null;
if (!prefersReduced) {
    lenis = new Lenis({
        lerp: 0.1,
        smoothWheel: true,
    });
    lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add((time) => lenis.raf(time * 1000));
    gsap.ticker.lagSmoothing(0);
}

/* Helpers */
const isVisible = (el) => el.getClientRects().length > 0;

const mark = (el, fn) => {
    if (el._mcInit) return;
    el._mcInit = true;
    fn(el);
};

/* Scroll reveal (fade + rise) */
function initReveal(el) {
    if (prefersReduced) return;
    gsap.set(el, { autoAlpha: 0, y: 32 });
    ScrollTrigger.create({
        trigger: el,
        start: 'top 88%',
        once: true,
        onEnter: () => {
            if (!isVisible(el)) return;
            gsap.to(el, { autoAlpha: 1, y: 0, duration: 0.9, ease: 'power3.out' });
        },
    });
}

/* Split reveal por líneas/palabras (títulos y textos destacados) */
function initSplitReveal(el) {
    if (prefersReduced) return;
    if (!isVisible(el)) return;
    const split = new SplitText(el, {
        type: 'lines,words',
        mask: 'lines',
        lineThreshold: 0.1,
        wordsClass: 'split-word',
    });
    gsap.set(split.words, { yPercent: 110 });
    ScrollTrigger.create({
        trigger: el,
        start: 'top 85%',
        once: true,
        onEnter: () => {
            if (!isVisible(el)) return;
            gsap.to(split.words, {
                yPercent: 0,
                duration: 1,
                stagger: 0.05,
                ease: 'power4.out',
            });
        },
    });
}

/* Split lines simple (encabezados de sección) */
function initSplitLines(el) {
    if (prefersReduced) return;
    const split = new SplitText(el, {
        type: 'lines,words',
        mask: 'lines',
        lineThreshold: 0.1,
        wordsClass: 'split-word',
    });
    gsap.set(split.words, { yPercent: 110 });
    ScrollTrigger.create({
        trigger: el,
        start: 'top 85%',
        once: true,
        onEnter: () => {
            if (!isVisible(el)) return;
            gsap.to(split.words, { yPercent: 0, duration: 1, stagger: 0.04, ease: 'power4.out' });
        },
    });
}

/* Stagger de hijos (grillas y listas) */
function initStagger(el) {
    if (prefersReduced) return;
    const children = [...el.children];
    if (!children.length) return;
    gsap.set(children, { autoAlpha: 0, y: 32 });
    ScrollTrigger.create({
        trigger: el,
        start: 'top 85%',
        once: true,
        onEnter: () => {
            gsap.to(children, {
                autoAlpha: 1,
                y: 0,
                duration: 0.8,
                stagger: 0.1,
                ease: 'power3.out',
            });
        },
    });
}

/* Parallax sutil */
function initParallax(el) {
    if (prefersReduced) return;
    const speed = parseFloat(el.dataset.parallax) || 0.15;
    gsap.to(el, {
        yPercent: -speed * 100,
        ease: 'none',
        scrollTrigger: {
            trigger: el,
            start: 'top bottom',
            end: 'bottom top',
            scrub: true,
        },
    });
}

/* Trazo de SVG (draw path) */
function initStrokeDraw(el) {
    if (prefersReduced) return;
    const paths = el.matches('path') ? [el] : [...el.querySelectorAll('path')];
    if (!paths.length) return;
    gsap.set(paths, { drawSVG: '0%' });
    ScrollTrigger.create({
        trigger: el,
        start: 'top 85%',
        once: true,
        onEnter: () => {
            gsap.to(paths, {
                drawSVG: '100%',
                duration: 1.6,
                ease: 'power2.inOut',
                stagger: 0.1,
            });
        },
    });
}

/* Efecto magnético en botones */
function initMagnetic(el) {
    if (prefersReduced || !hasHover) return;
    const strength = 0.35;
    el.addEventListener('mousemove', (e) => {
        const rect = el.getBoundingClientRect();
        const x = e.clientX - (rect.left + rect.width / 2);
        const y = e.clientY - (rect.top + rect.height / 2);
        gsap.to(el, {
            x: x * strength,
            y: y * strength,
            duration: 0.4,
            ease: 'power3.out',
        });
    });
    el.addEventListener('mouseleave', () => {
        gsap.to(el, { x: 0, y: 0, duration: 0.5, ease: 'elastic.out(1, 0.4)' });
    });
}

/* Tilt 3D en imágenes */
function initTilt(el) {
    if (prefersReduced || !hasHover) return;
    const max = 8;
    el.addEventListener('mousemove', (e) => {
        const rect = el.getBoundingClientRect();
        const px = (e.clientX - rect.left) / rect.width - 0.5;
        const py = (e.clientY - rect.top) / rect.height - 0.5;
        gsap.to(el, {
            rotateY: px * max * 2,
            rotateX: -py * max * 2,
            transformPerspective: 600,
            duration: 0.3,
            ease: 'power2.out',
        });
    });
    el.addEventListener('mouseleave', () => {
        gsap.to(el, { rotateX: 0, rotateY: 0, duration: 0.6, ease: 'power3.out' });
    });
}

/* Navbar: sólido al hacer scroll + link activo */
function initNavbar(nav) {
    const links = [...nav.querySelectorAll('a[href]')];
    const onScroll = () => {
        const scrolled = (window.scrollY || lenis?.scroll || 0) > 40;
        nav.classList.toggle('navbar-solid', scrolled);
    };
    const setActive = () => {
        const path = window.location.pathname;
        links.forEach((a) => {
            const href = a.getAttribute('href') || '';
            const base = href.split('#')[0];
            const isActive = href.includes('#')
                ? false
                : base === path || (base !== '/' && path.startsWith(base));
            a.classList.toggle('active', isActive);
        });
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
    setActive();
}

/* Dispatcher por atributo data-* */
function initElement(el) {
    mark(el, () => {
        if (el.hasAttribute('data-split-reveal')) return initSplitReveal(el);
        if (el.hasAttribute('data-split-lines')) return initSplitLines(el);
        if (el.hasAttribute('data-reveal')) return initReveal(el);
    });
}

function initAll(scope = document) {
    scope.querySelectorAll('[data-reveal-stagger]').forEach((el) => mark(el, initStagger));
    scope.querySelectorAll('[data-parallax]').forEach((el) => mark(el, initParallax));
    scope.querySelectorAll('[data-stroke-draw]').forEach((el) => mark(el, initStrokeDraw));
    scope.querySelectorAll('[data-magnetic]').forEach((el) => mark(el, initMagnetic));
    scope.querySelectorAll('[data-tilt]').forEach((el) => mark(el, initTilt));
    scope.querySelectorAll('[data-navbar]').forEach((el) => mark(el, initNavbar));
    scope
        .querySelectorAll('[data-reveal], [data-split-reveal], [data-split-lines]')
        .forEach(initElement);
}

/* Init inicial (tras DOM listo) */
function boot() {
    initAll();
    ScrollTrigger.refresh();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
} else {
    boot();
}

/* Navegación Livewire (turbo-like) */
document.addEventListener('livewire:navigating', () => {
    ScrollTrigger.killAll();
    lenis?.stop();
});

document.addEventListener('livewire:navigated', () => {
    lenis?.start();
    requestAnimationFrame(() => {
        initAll();
        ScrollTrigger.refresh();
    });
});

/* Re-init tras actualizaciones morph de Alpine */
document.addEventListener('livewire:init', () => {
    Livewire.hook('morph.updated', (component, el) => {
        if (!el || !el.querySelector) return;
        initAll(el);
    });
});
